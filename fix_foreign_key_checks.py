import glob

files = glob.glob('database/seeders/*.php')
updated_count = 0

for file_path in files:
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    new_content = content
    if 'FOREIGN_KEY_CHECKS' in new_content:
        new_content = new_content.replace("DB::statement('SET FOREIGN_KEY_CHECKS=0;');", r"\Schema::disableForeignKeyConstraints();")
        new_content = new_content.replace("DB::statement('SET FOREIGN_KEY_CHECKS = 0;');", r"\Schema::disableForeignKeyConstraints();")
        new_content = new_content.replace('DB::statement("SET FOREIGN_KEY_CHECKS=0;");', r"\Schema::disableForeignKeyConstraints();")
        new_content = new_content.replace('DB::statement("SET FOREIGN_KEY_CHECKS = 0;");', r"\Schema::disableForeignKeyConstraints();")
        
        new_content = new_content.replace("DB::statement('SET FOREIGN_KEY_CHECKS=1;');", r"\Schema::enableForeignKeyConstraints();")
        new_content = new_content.replace("DB::statement('SET FOREIGN_KEY_CHECKS = 1;');", r"\Schema::enableForeignKeyConstraints();")
        new_content = new_content.replace('DB::statement("SET FOREIGN_KEY_CHECKS=1;");', r"\Schema::enableForeignKeyConstraints();")
        new_content = new_content.replace('DB::statement("SET FOREIGN_KEY_CHECKS = 1;");', r"\Schema::enableForeignKeyConstraints();")
        
    if new_content != content:
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(new_content)
        updated_count += 1

print(f'Updated {updated_count} seeder files.')
