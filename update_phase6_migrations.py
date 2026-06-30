import os
import re
import glob

migrations_dir = r"d:\e-reckon-erp\database\migrations"

schemas = {
    # CRM & Loyalty
    "create_customers_table.php": """$table->id();
            $table->foreignId('customer_group_id')->nullable()->constrained('customer_groups');
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->date('dob')->nullable();
            $table->date('anniversary')->nullable();
            $table->string('gst_number')->nullable();
            $table->integer('total_points')->default(0);
            $table->decimal('wallet_balance', 15, 2)->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_customer_groups_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_loyalty_programs_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('points_per_amount', 10, 2)->default(1);
            $table->integer('minimum_points_to_redeem')->default(100);
            $table->decimal('value_per_point', 10, 2)->default(1);
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_loyalty_cards_table.php": """$table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('card_number')->unique();
            $table->date('issue_date');
            $table->date('expiry_date')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_loyalty_points_table.php": """$table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('transaction_type'); // earn, redeem
            $table->integer('points')->default(0);
            $table->string('description')->nullable();
            $table->date('transaction_date');
            $table->timestamps();""",

    "create_customer_wallets_table.php": """$table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('transaction_type'); // credit, debit
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('description')->nullable();
            $table->date('transaction_date');
            $table->timestamps();""",

    "create_membership_plans_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('fee', 15, 2)->default(0);
            $table->integer('duration_months')->default(12);
            $table->text('benefits_description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_customer_feedback_table.php": """$table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('branch_id')->nullable()->constrained('branches');
            $table->integer('rating')->default(5);
            $table->text('comments')->nullable();
            $table->date('feedback_date');
            $table->timestamps();""",

    # Promotions & Discounts
    "create_promotion_types_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->timestamps();""",

    "create_discount_rules_table.php": """$table->id();
            $table->foreignId('promotion_type_id')->constrained('promotion_types');
            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->decimal('discount_amount', 15, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('min_purchase_amount', 15, 2)->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_coupons_table.php": """$table->id();
            $table->string('code')->unique();
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->decimal('discount_amount', 15, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('min_purchase_amount', 15, 2)->default(0);
            $table->integer('max_usage')->default(1);
            $table->integer('current_usage')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_promo_codes_table.php": """$table->id();
            $table->string('code')->unique();
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->decimal('discount_amount', 15, 2)->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_buy_x_get_y_offers_table.php": """$table->id();
            $table->foreignId('buy_product_id')->constrained('products');
            $table->foreignId('get_product_id')->constrained('products');
            $table->integer('buy_quantity')->default(1);
            $table->integer('get_quantity')->default(1);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_festival_offers_table.php": """$table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_combo_offers_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('total_price', 15, 2)->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_price_lists_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    # Finance & Accounts
    "create_account_groups_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->foreignId('parent_id')->nullable()->constrained('account_groups');
            $table->string('type'); // asset, liability, equity, revenue, expense
            $table->timestamps();""",
            
    "create_chart_of_accounts_table.php": """$table->id();
            $table->foreignId('account_group_id')->constrained('account_groups');
            $table->string('account_code')->unique();
            $table->string('account_name');
            $table->string('account_type');
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->timestamps();""",

    "create_journals_table.php": """$table->id();
            $table->string('journal_number')->unique();
            $table->date('journal_date');
            $table->string('reference')->nullable();
            $table->decimal('total_debit', 15, 2)->default(0);
            $table->decimal('total_credit', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('status')->default('posted');
            $table->timestamps();""",

    "create_journal_entries_table.php": """$table->id();
            $table->foreignId('journal_id')->constrained('journals')->onDelete('cascade');
            $table->foreignId('chart_of_account_id')->constrained('chart_of_accounts');
            $table->string('description')->nullable();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->timestamps();""",

    "create_ledgers_table.php": """$table->id();
            $table->foreignId('chart_of_account_id')->constrained('chart_of_accounts');
            $table->date('transaction_date');
            $table->string('description')->nullable();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->decimal('balance', 15, 2)->default(0);
            $table->timestamps();""",

    "create_bank_accounts_table.php": """$table->id();
            $table->string('bank_name');
            $table->string('account_number')->unique();
            $table->string('ifsc_code')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('account_type')->nullable(); // Current, Savings
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->boolean('is_default')->default(false);
            $table->timestamps();""",

    "create_cash_books_table.php": """$table->id();
            $table->date('date');
            $table->string('description')->nullable();
            $table->decimal('receipt_amount', 15, 2)->default(0);
            $table->decimal('payment_amount', 15, 2)->default(0);
            $table->decimal('balance', 15, 2)->default(0);
            $table->timestamps();""",

    "create_payments_table.php": """$table->id();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreignId('chart_of_account_id')->nullable()->constrained('chart_of_accounts');
            $table->date('payment_date');
            $table->decimal('amount', 15, 2)->default(0);
            $table->foreignId('payment_mode_id')->nullable()->constrained('payment_modes');
            $table->string('reference_no')->nullable();
            $table->string('status')->default('completed');
            $table->timestamps();""",

    "create_contra_entries_table.php": """$table->id();
            $table->date('date');
            $table->foreignId('from_account_id')->constrained('chart_of_accounts');
            $table->foreignId('to_account_id')->constrained('chart_of_accounts');
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('description')->nullable();
            $table->timestamps();""",

    "create_credit_notes_table.php": """$table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('note_number')->unique();
            $table->date('note_date');
            $table->decimal('amount', 15, 2)->default(0);
            $table->text('reason')->nullable();
            $table->string('status')->default('issued');
            $table->timestamps();""",

    "create_trial_balances_table.php": """$table->id();
            $table->date('as_of_date');
            $table->foreignId('chart_of_account_id')->constrained('chart_of_accounts');
            $table->decimal('debit_balance', 15, 2)->default(0);
            $table->decimal('credit_balance', 15, 2)->default(0);
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
