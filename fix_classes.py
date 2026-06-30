import os
import re

models_dir = r'd:\e-reckon-erp\app\Models'
filament_dir = r'd:\e-reckon-erp\app\Filament\Resources'

# Fix the Model relationships mapping to actual classes
class_fixes = {
    'FromBranch': 'Branch',
    'ToBranch': 'Branch',
    'FromWarehouse': 'Warehouse',
    'ToWarehouse': 'Warehouse',
    'FromAccount': 'ChartOfAccount',
    'ToAccount': 'ChartOfAccount',
    'BuyProduct': 'Product',
    'GetProduct': 'Product',
    'Approver': 'User'
}

for f in os.listdir(models_dir):
    if not f.endswith('.php'): continue
    path = os.path.join(models_dir, f)
    with open(path, 'r', encoding='utf-8') as file:
        content = file.read()
    
    new_content = content
    for bad_class, good_class in class_fixes.items():
        new_content = re.sub(r'belongsTo\(' + bad_class + r'::class', f'belongsTo({good_class}::class', new_content)
    
    if new_content != content:
        with open(path, 'w', encoding='utf-8') as file:
            file.write(new_content)
        print(f'Fixed Model classes: {f}')


# Revert false relationships (transporter, invoiceable, deductee, collectee)
false_relations = ['transporter', 'invoiceable', 'deductee', 'collectee']

for f in os.listdir(models_dir):
    if not f.endswith('.php'): continue
    path = os.path.join(models_dir, f)
    with open(path, 'r', encoding='utf-8') as file:
        content = file.read()
    
    new_content = content
    for rel in false_relations:
        pattern = r"\s*public function " + rel + r"\(\) \{\s*return \$this->belongsTo\([a-zA-Z0-9_]+::class, '" + rel + r"_id'\);\s*\}"
        new_content = re.sub(pattern, '', new_content)
        
    if new_content != content:
        with open(path, 'w', encoding='utf-8') as file:
            file.write(new_content)
        print(f'Removed false relationships in Model: {f}')

for root, _, files in os.walk(filament_dir):
    for f in files:
        if f.endswith('Form.php'):
            path = os.path.join(root, f)
            with open(path, 'r', encoding='utf-8') as file:
                content = file.read()
            new_content = content
            for rel in false_relations:
                pattern = r"\\Filament\\Forms\\Components\\Select::make\('" + rel + r"_id'\)->relationship\('" + rel + r"', '[^']+'\)\s*"
                new_content = re.sub(pattern, f"\\Filament\\Forms\\Components\\TextInput::make('{rel}_id')->numeric()\n                    ", new_content)
            
            if new_content != content:
                with open(path, 'w', encoding='utf-8') as file:
                    file.write(new_content)
                print(f'Fixed Form false relations: {f}')
                
        elif f.endswith('Table.php'):
            path = os.path.join(root, f)
            with open(path, 'r', encoding='utf-8') as file:
                content = file.read()
            new_content = content
            for rel in false_relations:
                pattern = r"TextColumn::make\('" + rel + r"\.[^']+'\)\s*"
                new_content = re.sub(pattern, f"TextColumn::make('{rel}_id')->numeric()\n                    ", new_content)
            
            if new_content != content:
                with open(path, 'w', encoding='utf-8') as file:
                    file.write(new_content)
                print(f'Fixed Table false relations: {f}')
