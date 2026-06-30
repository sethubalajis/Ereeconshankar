import os
import re
import glob

migrations_dir = r"d:\e-reckon-erp\database\migrations"

schemas = {
    "create_companies_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('gst_number')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->text('address')->nullable();
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->foreignId('state_id')->nullable()->constrained('states');
            $table->foreignId('country_id')->nullable()->constrained('countries');
            $table->string('pin_code')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",
            
    "create_branches_table.php": """$table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->string('type')->nullable(); // e.g. Warehouse, Retail, Office
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->foreignId('state_id')->nullable()->constrained('states');
            $table->foreignId('country_id')->nullable()->constrained('countries');
            $table->string('pin_code')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_business_units_table.php": """$table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_departments_table.php": """$table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_cost_centers_table.php": """$table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_financial_years_table.php": """$table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_closed')->default(false);
            $table->timestamps();""",

    "create_currencies_table.php": """$table->id();
            $table->string('name');
            $table->string('code', 3)->unique();
            $table->string('symbol', 10)->nullable();
            $table->decimal('exchange_rate', 15, 6)->default(1);
            $table->boolean('is_default')->default(false);
            $table->timestamps();""",

    "create_countries_table.php": """$table->id();
            $table->string('name');
            $table->string('code', 3)->unique();
            $table->string('phone_code', 10)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_states_table.php": """$table->id();
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_cities_table.php": """$table->id();
            $table->foreignId('state_id')->constrained('states')->onDelete('cascade');
            $table->string('name');
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_roles_table.php": """$table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_permissions_table.php": """$table->id();
            $table->string('name')->unique();
            $table->string('module')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();""",

    "create_user_roles_table.php": """$table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->timestamps();""",

    "create_role_permissions_table.php": """$table->id();
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');
            $table->timestamps();""",

    "create_user_branch_accesses_table.php": """$table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->boolean('is_default')->default(false);
            $table->timestamps();""",

    "create_login_histories_table.php": """$table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('login_at')->nullable();
            $table->timestamp('logout_at')->nullable();
            $table->timestamps();""",
            
    "create_sessions_table.php": "DELETE",
}

for filepath in glob.glob(os.path.join(migrations_dir, "*.php")):
    filename = os.path.basename(filepath)
    # Match the end of the filename (e.g. 2026_06_29_070132_create_companies_table.php)
    matched_schema = None
    for key in schemas.keys():
        if filename.endswith(key):
            matched_schema = key
            break
            
    if matched_schema:
        if schemas[matched_schema] == "DELETE":
            print(f"Deleting {filename}")
            os.remove(filepath)
            continue
            
        with open(filepath, 'r') as f:
            content = f.read()
            
        # Replace the inside of Schema::create
        # Find $table->id(); ... $table->timestamps();
        pattern = re.compile(r'(\$table->id\(\);).*?(\$table->timestamps\(\);)', re.DOTALL)
        if pattern.search(content):
            new_content = pattern.sub(schemas[matched_schema], content)
            with open(filepath, 'w') as f:
                f.write(new_content)
            print(f"Updated {filename}")
        else:
            print(f"Could not find schema block in {filename}")

