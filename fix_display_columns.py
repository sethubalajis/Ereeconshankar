import os
import re
import json
import inflection

migrations_dir = r'd:\e-reckon-erp\database\migrations'

table_display_col = {}
priority_cols = ['name', 'title', 'sku', 'code', 'po_number', 'order_number', 'invoice_number', 'receipt_no', 'first_name', 'email']

for f in os.listdir(migrations_dir):
    if not f.endswith('.php'): continue
    path = os.path.join(migrations_dir, f)
    with open(path, 'r', encoding='utf-8') as file:
        content = file.read()
        
    table_match = re.search(r"Schema::create\('([^']+)'", content)
    if not table_match: continue
    
    table_name = table_match.group(1)
    
    cols = re.findall(r"\$table->(?:string|text)\('([^']+)'", content)
    
    best_col = 'id'
    for pc in priority_cols:
        if pc in cols:
            best_col = pc
            break
            
    if best_col == 'id' and len(cols) > 0:
        best_col = cols[0]
        
    table_display_col[table_name] = best_col

model_display_col = {}
for table, col in table_display_col.items():
    model_name = inflection.camelize(inflection.singularize(table))
    model_display_col[model_name] = col

# Add overrides or missing models
model_display_col['ProductVariant'] = 'sku'
model_display_col['ProductAttribute'] = 'name'
model_display_col['User'] = 'name'

# Now let's fix the Filament schema files
base_dir = r'd:\e-reckon-erp\app\Filament\Resources'

for root, _, files in os.walk(base_dir):
    for file in files:
        if file.endswith('Form.php'):
            path = os.path.join(root, file)
            with open(path, 'r', encoding='utf-8') as f:
                content = f.read()
                
            def form_replacer(match):
                rel_name = match.group(1)
                # Determine Model Name from relation name
                # E.g., productAttribute -> ProductAttribute
                target_model_name = inflection.camelize(rel_name)
                # Default to 'name' if not found
                col = model_display_col.get(target_model_name, 'name')
                return f"->relationship('{rel_name}', '{col}')"
                
            new_content = re.sub(
                r"->relationship\('([a-zA-Z0-9_]+)', 'name'\)",
                form_replacer,
                content
            )
            
            if new_content != content:
                with open(path, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                print(f"Updated {file} display column")
                
        elif file.endswith('Table.php'):
            path = os.path.join(root, file)
            with open(path, 'r', encoding='utf-8') as f:
                content = f.read()
                
            def table_replacer(match):
                rel_name = match.group(1)
                target_model_name = inflection.camelize(rel_name)
                col = model_display_col.get(target_model_name, 'name')
                return f"TextColumn::make('{rel_name}.{col}')"
                
            new_content = re.sub(
                r"TextColumn::make\('([a-zA-Z0-9_]+)\.name'\)",
                table_replacer,
                content
            )
            
            if new_content != content:
                with open(path, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                print(f"Updated {file} display column")

