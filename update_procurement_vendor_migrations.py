import os
import re
import glob

migrations_dir = r"d:\e-reckon-erp\database\migrations"

schemas = {
    # Procurement
    "create_purchase_requisitions_table.php": """$table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->foreignId('department_id')->nullable()->constrained('departments');
            $table->date('required_date');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('remarks')->nullable();
            $table->timestamps();""",

    "create_rfqs_table.php": """$table->id();
            $table->foreignId('purchase_requisition_id')->nullable()->constrained('purchase_requisitions');
            $table->date('rfq_date');
            $table->date('submission_deadline')->nullable();
            $table->string('status')->default('open');
            $table->timestamps();""",

    "create_quotations_table.php": """$table->id();
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->foreignId('rfq_id')->nullable()->constrained('rfqs');
            $table->date('quote_date');
            $table->date('validity_date')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('status')->default('received');
            $table->timestamps();""",

    "create_purchase_orders_table.php": """$table->id();
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->foreignId('branch_id')->constrained('branches');
            $table->string('po_number')->unique();
            $table->date('po_date');
            $table->date('expected_delivery_date')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('status')->default('draft');
            $table->timestamps();""",

    "create_purchase_order_items_table.php": """$table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->timestamps();""",

    "create_grns_table.php": """$table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders');
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->foreignId('branch_id')->constrained('branches');
            $table->string('grn_number')->unique();
            $table->date('receive_date');
            $table->string('status')->default('received');
            $table->timestamps();""",

    "create_purchase_returns_table.php": """$table->id();
            $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders');
            $table->foreignId('grn_id')->nullable()->constrained('grns');
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->foreignId('branch_id')->constrained('branches');
            $table->date('return_date');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->text('reason')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();""",

    "create_debit_notes_table.php": """$table->id();
            $table->foreignId('purchase_return_id')->constrained('purchase_returns');
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->string('note_number')->unique();
            $table->date('note_date');
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('status')->default('issued');
            $table->timestamps();""",

    # Vendor Management
    "create_vendors_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('pan_number')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_vendor_categories_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->timestamps();""",

    "create_vendor_contacts_table.php": """$table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('designation')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();""",

    "create_vendor_agreements_table.php": """$table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->string('title');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('document_path')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();""",

    "create_vendor_price_lists_table.php": """$table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants');
            $table->decimal('agreed_price', 15, 2)->default(0);
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->timestamps();""",

    "create_vendor_performances_table.php": """$table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->date('review_date');
            $table->decimal('rating', 3, 2)->default(0);
            $table->decimal('delivery_score', 5, 2)->nullable();
            $table->decimal('quality_score', 5, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();""",

    "create_vendor_payments_table.php": """$table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders');
            $table->date('payment_date');
            $table->decimal('amount', 15, 2)->default(0);
            $table->unsignedBigInteger('payment_mode_id')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->string('status')->default('completed');
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
            new_content = pattern.sub(schemas[matched_schema], content)
            with open(filepath, 'w') as f:
                f.write(new_content)
            updated.append(filename)

print(f"Updated {len(updated)} migrations.")
