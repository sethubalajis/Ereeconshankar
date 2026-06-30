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
    
    # Extract string columns
    cols = re.findall(r"\$table->(?:string|text)\('([^']+)'", content)
    
    best_col = 'id'
    for pc in priority_cols:
        if pc in cols:
            best_col = pc
            break
            
    if best_col == 'id' and len(cols) > 0:
        best_col = cols[0]
        
    table_display_col[table_name] = best_col

# We want to map Model name to display column
# e.g. "ProductVariant" -> "sku"

model_display_col = {}
for table, col in table_display_col.items():
    # Convert table name to model name
    model_name = inflection.camelize(inflection.singularize(table))
    model_display_col[model_name] = col

print(json.dumps(model_display_col, indent=2))
