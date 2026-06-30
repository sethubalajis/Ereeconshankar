import os
import re
import glob

migrations_dir = r"d:\e-reckon-erp\database\migrations"

schemas = {
    # Product Master
    "create_product_categories_table.php": """$table->id();
            $table->foreignId('parent_id')->nullable()->constrained('product_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",
            
    "create_product_sub_categories_table.php": """$table->id();
            $table->foreignId('product_category_id')->constrained('product_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_brands_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_product_units_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->timestamps();""",

    "create_hsn_codes_table.php": """$table->id();
            $table->string('hsn_code')->unique();
            $table->text('description')->nullable();
            $table->timestamps();""",

    "create_tax_groups_table.php": """$table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('total_tax_percentage', 5, 2)->default(0);
            $table->timestamps();""",

    "create_products_table.php": """$table->id();
            $table->foreignId('brand_id')->nullable()->constrained('brands');
            $table->foreignId('product_category_id')->nullable()->constrained('product_categories');
            $table->foreignId('product_sub_category_id')->nullable()->constrained('product_sub_categories');
            $table->foreignId('product_unit_id')->nullable()->constrained('product_units');
            $table->foreignId('hsn_code_id')->nullable()->constrained('hsn_codes');
            $table->foreignId('tax_group_id')->nullable()->constrained('tax_groups');
            $table->string('name');
            $table->string('code')->unique();
            $table->string('sku')->unique()->nullable();
            $table->text('description')->nullable();
            $table->decimal('base_price', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();""",

    "create_product_variants_table.php": """$table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('sku')->unique();
            $table->decimal('mrp', 15, 2)->default(0);
            $table->decimal('selling_price', 15, 2)->default(0);
            $table->decimal('cost_price', 15, 2)->default(0);
            $table->string('barcode')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();""",

    "create_product_attributes_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('type')->default('text'); // text, color, select
            $table->timestamps();""",

    "create_attribute_values_table.php": """$table->id();
            $table->foreignId('product_attribute_id')->constrained('product_attributes')->onDelete('cascade');
            $table->string('value');
            $table->timestamps();""",

    "create_product_images_table.php": """$table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->onDelete('cascade');
            $table->string('image_path');
            $table->boolean('is_primary')->default(false);
            $table->timestamps();""",

    "create_product_barcodes_table.php": """$table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->onDelete('cascade');
            $table->string('barcode')->unique();
            $table->string('barcode_type')->nullable(); // EAN13, UPC, Code128
            $table->timestamps();""",

    # Merchandise Management
    "create_collections_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_seasons_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_product_lines_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_styles_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_designs_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_colors_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('hex_code')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_sizes_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_fabric_types_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_pattern_types_table.php": """$table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();""",

    "create_merchandise_plannings_table.php": """$table->id();
            $table->foreignId('financial_year_id')->constrained('financial_years');
            $table->foreignId('season_id')->constrained('seasons');
            $table->decimal('target_sales', 15, 2)->default(0);
            $table->decimal('planned_budget', 15, 2)->default(0);
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
