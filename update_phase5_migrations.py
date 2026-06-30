import os
import re
import glob

migrations_dir = r"d:\e-reckon-erp\database\migrations"

schemas = {
    # Branch Management
    "create_branch_settings_table.php": """$table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->string('setting_key');
            $table->text('setting_value')->nullable();
            $table->timestamps();""",

    "create_branch_inventories_table.php": """$table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants');
            $table->decimal('quantity', 15, 2)->default(0);
            $table->timestamps();""",

    "create_branch_users_table.php": """$table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();""",

    "create_branch_expenses_table.php": """$table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->date('expense_date');
            $table->decimal('amount', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();""",

    "create_branch_targets_table.php": """$table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->date('target_month');
            $table->decimal('target_amount', 15, 2)->default(0);
            $table->decimal('achieved_amount', 15, 2)->default(0);
            $table->timestamps();""",

    # Stock Transfer
    "create_transfer_requests_table.php": """$table->id();
            $table->foreignId('from_branch_id')->constrained('branches');
            $table->foreignId('to_branch_id')->constrained('branches');
            $table->date('request_date');
            $table->string('status')->default('pending');
            $table->timestamps();""",

    "create_stock_transfers_table.php": """$table->id();
            $table->foreignId('transfer_request_id')->nullable()->constrained('transfer_requests');
            $table->foreignId('from_branch_id')->constrained('branches');
            $table->foreignId('to_branch_id')->constrained('branches');
            $table->date('transfer_date');
            $table->string('vehicle_number')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('status')->default('in_transit');
            $table->timestamps();""",

    "create_transfer_items_table.php": """$table->id();
            $table->foreignId('stock_transfer_id')->constrained('stock_transfers')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants');
            $table->decimal('quantity', 15, 2)->default(0);
            $table->timestamps();""",

    "create_transfer_receipts_table.php": """$table->id();
            $table->foreignId('stock_transfer_id')->constrained('stock_transfers');
            $table->date('receive_date');
            $table->unsignedBigInteger('received_by')->nullable();
            $table->string('status')->default('received');
            $table->timestamps();""",

    "create_transit_inventories_table.php": """$table->id();
            $table->foreignId('stock_transfer_id')->constrained('stock_transfers');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants');
            $table->decimal('quantity', 15, 2)->default(0);
            $table->timestamps();""",

    "create_transfer_approvals_table.php": """$table->id();
            $table->foreignId('transfer_request_id')->constrained('transfer_requests');
            $table->unsignedBigInteger('approver_id')->nullable();
            $table->date('approval_date')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();""",

    # Retail POS
    "create_payment_modes_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();""",
            
    "create_pos_terminals_table.php": """$table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->string('terminal_name');
            $table->string('terminal_code')->unique();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_pos_counters_table.php": """$table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->string('counter_name');
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_cash_registers_table.php": """$table->id();
            $table->foreignId('pos_terminal_id')->constrained('pos_terminals');
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('closing_balance', 15, 2)->nullable();
            $table->string('status')->default('open');
            $table->timestamps();""",

    "create_cash_openings_table.php": """$table->id();
            $table->foreignId('cash_register_id')->constrained('cash_registers')->onDelete('cascade');
            $table->timestamp('opening_time');
            $table->decimal('opening_amount', 15, 2)->default(0);
            $table->timestamps();""",

    "create_cash_closings_table.php": """$table->id();
            $table->foreignId('cash_register_id')->constrained('cash_registers')->onDelete('cascade');
            $table->timestamp('closing_time');
            $table->decimal('closing_amount', 15, 2)->default(0);
            $table->decimal('variance', 15, 2)->default(0);
            $table->timestamps();""",

    "create_pos_sales_table.php": """$table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->foreignId('pos_terminal_id')->constrained('pos_terminals');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('invoice_number')->unique();
            $table->date('sale_date');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2)->default(0);
            $table->foreignId('payment_mode_id')->nullable()->constrained('payment_modes');
            $table->string('status')->default('completed');
            $table->timestamps();""",

    "create_pos_sale_items_table.php": """$table->id();
            $table->foreignId('pos_sale_id')->constrained('pos_sales')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants');
            $table->decimal('quantity', 15, 2)->default(1);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->timestamps();""",

    "create_pos_returns_table.php": """$table->id();
            $table->foreignId('pos_sale_id')->constrained('pos_sales');
            $table->date('return_date');
            $table->decimal('return_amount', 15, 2)->default(0);
            $table->text('reason')->nullable();
            $table->string('status')->default('completed');
            $table->timestamps();""",

    "create_gift_vouchers_table.php": """$table->id();
            $table->string('voucher_code')->unique();
            $table->decimal('amount', 15, 2)->default(0);
            $table->date('expiry_date')->nullable();
            $table->boolean('is_redeemed')->default(false);
            $table->timestamps();""",

    "create_receipts_table.php": """$table->id();
            $table->foreignId('pos_sale_id')->constrained('pos_sales')->onDelete('cascade');
            $table->string('receipt_number')->unique();
            $table->timestamp('printed_at')->nullable();
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
