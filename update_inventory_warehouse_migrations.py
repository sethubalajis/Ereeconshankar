import os
import re
import glob

migrations_dir = r"d:\e-reckon-erp\database\migrations"

schemas = {
    # Inventory Management
    "create_stocks_table.php": """$table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants');
            $table->foreignId('branch_id')->constrained('branches');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->decimal('quantity', 15, 2)->default(0);
            $table->decimal('avg_cost', 15, 2)->default(0);
            $table->timestamps();""",

    "create_stock_transactions_table.php": """$table->id();
            $table->foreignId('stock_id')->constrained('stocks')->onDelete('cascade');
            $table->string('transaction_type'); // in, out, adjustment, transfer
            $table->decimal('quantity', 15, 2)->default(0);
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('reference_type')->nullable(); // e.g. App\\Models\\PurchaseOrder
            $table->timestamps();""",

    "create_stock_adjustments_table.php": """$table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants');
            $table->decimal('quantity', 15, 2)->default(0);
            $table->string('reason')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();""",

    "create_stock_ledgers_table.php": """$table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants');
            $table->foreignId('branch_id')->constrained('branches');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->date('transaction_date');
            $table->decimal('opening_qty', 15, 2)->default(0);
            $table->decimal('inward_qty', 15, 2)->default(0);
            $table->decimal('outward_qty', 15, 2)->default(0);
            $table->decimal('closing_qty', 15, 2)->default(0);
            $table->timestamps();""",

    "create_stock_valuations_table.php": """$table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->date('valuation_date');
            $table->decimal('total_value', 15, 2)->default(0);
            $table->timestamps();""",

    "create_stock_reservations_table.php": """$table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants');
            $table->foreignId('branch_id')->constrained('branches');
            $table->decimal('quantity', 15, 2)->default(0);
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();""",

    "create_inventory_counts_table.php": """$table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->date('count_date');
            $table->string('status')->default('draft');
            $table->timestamps();""",

    "create_inventory_reconciliations_table.php": """$table->id();
            $table->foreignId('inventory_count_id')->constrained('inventory_counts')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants');
            $table->decimal('system_qty', 15, 2)->default(0);
            $table->decimal('physical_qty', 15, 2)->default(0);
            $table->decimal('variance', 15, 2)->default(0);
            $table->timestamps();""",

    # Warehouse Management
    "create_warehouses_table.php": """$table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->text('address')->nullable();
            $table->decimal('capacity', 15, 2)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_warehouse_zones_table.php": """$table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->string('type')->nullable(); // e.g. Receiving, Storage, Shipping
            $table->timestamps();""",

    "create_racks_table.php": """$table->id();
            $table->foreignId('warehouse_zone_id')->constrained('warehouse_zones')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->timestamps();""",

    "create_bins_table.php": """$table->id();
            $table->foreignId('rack_id')->constrained('racks')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('max_weight', 10, 2)->nullable();
            $table->timestamps();""",

    "create_bin_stocks_table.php": """$table->id();
            $table->foreignId('bin_id')->constrained('bins')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants');
            $table->decimal('quantity', 15, 2)->default(0);
            $table->timestamps();""",

    "create_warehouse_transfers_table.php": """$table->id();
            $table->foreignId('from_warehouse_id')->constrained('warehouses');
            $table->foreignId('to_warehouse_id')->constrained('warehouses');
            $table->date('transfer_date');
            $table->string('status')->default('pending');
            $table->timestamps();""",

    "create_picking_lists_table.php": """$table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses');
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('reference_type')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();""",

    "create_packing_lists_table.php": """$table->id();
            $table->foreignId('picking_list_id')->constrained('picking_lists')->onDelete('cascade');
            $table->string('status')->default('packed');
            $table->timestamps();"""
}

updated = []
for filepath in glob.glob(os.path.join(migrations_dir, "*.php")):
    filename = os.path.basename(filepath)
    matched_schema = None
    for key in schemas.keys():
        if filename.endswith(key):
            matched_schema = key
            break
            
    if matched_schema:
        with open(filepath, 'r') as f:
            content = f.read()
            
        pattern = re.compile(r'(\$table->id\(\);).*?(\$table->timestamps\(\);)', re.DOTALL)
        if pattern.search(content):
            new_content = pattern.sub(lambda m: schemas[matched_schema], content)
            with open(filepath, 'w') as f:
                f.write(new_content)
            updated.append(filename)

print(f"Updated {len(updated)} migrations.")
