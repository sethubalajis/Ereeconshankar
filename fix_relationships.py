import os, re

base_dir = r'd:\e-reckon-erp\app\Filament\Resources'
models_dir = r'd:\e-reckon-erp\app\Models'

def camel_case(s):
    parts = s.split('_')
    return parts[0] + ''.join(word.capitalize() for word in parts[1:])

def pascal_case(s):
    return ''.join(word.capitalize() for word in s.split('_'))

for root, _, files in os.walk(base_dir):
    for file in files:
        if file.endswith('Form.php'):
            model_name = file.replace('Form.php', '')
            model_path = os.path.join(models_dir, f"{model_name}.php")
            
            if not os.path.exists(model_path):
                continue
                
            path = os.path.join(root, file)
            with open(path, 'r', encoding='utf-8') as f:
                content = f.read()
                
            # Find all ->relationship('snake_case', 'name')
            rels = re.findall(r"->relationship\('([a-z_]+)', 'name'\)", content)
            
            if rels:
                # Add to model
                with open(model_path, 'r', encoding='utf-8') as mf:
                    model_content = mf.read()
                    
                added = False
                for rel in rels:
                    camel_rel = camel_case(rel)
                    pascal_rel = pascal_case(rel)
                    # For cases like 'parent_id', Pascal is 'Parent' which might map to Parent model.
                    # Usually, 'product_attribute' -> 'ProductAttribute'
                    
                    method_str = f"public function {camel_rel}() {{\n        return $this->belongsTo({pascal_rel}::class, '{rel}_id');\n    }}"
                    if method_str not in model_content and f"function {camel_rel}()" not in model_content:
                        # Insert before last closing brace
                        model_content = model_content.rsplit('}', 1)[0] + "    " + method_str + "\n}\n"
                        added = True
                        
                if added:
                    with open(model_path, 'w', encoding='utf-8') as mf:
                        mf.write(model_content)
                    print(f"Added relations to {model_name}.php")

            # Update Form to use camelCase in relationship()
            new_content = re.sub(
                r"->relationship\('([a-z_]+)', 'name'\)",
                lambda m: f"->relationship('{camel_case(m.group(1))}', 'name')",
                content
            )
            
            # Form might have Select::make('product_attribute_id')
            # It's fine to leave the first arg as product_attribute_id.
            if new_content != content:
                with open(path, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                print(f"Updated {file} camelCase")
                
        elif file.endswith('Table.php'):
            path = os.path.join(root, file)
            with open(path, 'r', encoding='utf-8') as f:
                content = f.read()
                
            # Update Table to use camelCase for TextColumn::make('snake_case.name')
            new_content = re.sub(
                r"TextColumn::make\('([a-z_]+)\.name'\)",
                lambda m: f"TextColumn::make('{camel_case(m.group(1))}.name')",
                content
            )
            
            if new_content != content:
                with open(path, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                print(f"Updated {file} camelCase")

