import os
import glob
import re

directory = r"c:\Users\Admin\reckon\ereckontamil\app\Filament\Branch\Resources"

# Find all Table.php files
table_files = glob.glob(os.path.join(directory, "**", "Tables", "*Table.php"), recursive=True)

searchable_fields = ['name', 'code', 'sku', 'email', 'phone', 'number', 'reference', 'title']

def process_file(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    modified = False
    
    lines = content.split('\n')
    new_lines = []
    
    for i, line in enumerate(lines):
        new_lines.append(line)
        
        match = re.search(r"TextColumn::make\(['\"]([^'\"]+)['\"]\)", line)
        if match:
            field = match.group(1)
            
            if any(field.lower().endswith(s) for s in searchable_fields) or any(field.lower() == s for s in searchable_fields):
                
                if '->searchable()' in line:
                    continue
                
                already_searchable = False
                for j in range(1, 6):
                    if i + j < len(lines):
                        next_line = lines[i+j].strip()
                        if next_line.startswith('->searchable('):
                            already_searchable = True
                            break
                        if next_line.startswith('TextColumn::make') or next_line.startswith('ImageColumn') or next_line.startswith('IconColumn') or next_line.startswith('])'):
                            break
                
                if not already_searchable:
                    indent_match = re.match(r"^(\s*)", line)
                    indent = len(indent_match.group(1)) if indent_match else 0
                    
                    new_line_str = " " * (indent + 4) + "->searchable()"
                    new_lines.append(new_line_str)
                    modified = True

    if modified:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write('\n'.join(new_lines))
        print(f"Updated {os.path.basename(filepath)}")

for filepath in table_files:
    process_file(filepath)
