import os
import re

models_dir = r'd:\e-reckon-erp\app\Models'
for f in os.listdir(models_dir):
    if not f.endswith('.php'): continue
    path = os.path.join(models_dir, f)
    with open(path, 'r', encoding='utf-8') as file:
        content = file.read()
    
    if 'Reference::class' in content:
        # Remove the reference() method
        new_content = re.sub(r"\s*public function reference\(\) \{\s*return \$this->belongsTo\(Reference::class, 'reference_id'\);\s*\}", '', content)
        if new_content != content:
            with open(path, 'w', encoding='utf-8') as file:
                file.write(new_content)
            print(f'Fixed model: {f}')

filament_dir = r'd:\e-reckon-erp\app\Filament\Resources'
for root, _, files in os.walk(filament_dir):
    for f in files:
        if f.endswith('Form.php'):
            path = os.path.join(root, f)
            with open(path, 'r', encoding='utf-8') as file:
                content = file.read()
            if "relationship('reference'" in content:
                new_content = re.sub(
                    r"\\Filament\\Forms\\Components\\Select::make\('reference_id'\)->relationship\('reference', '[^']+'\)\s*",
                    r"\\Filament\\Forms\\Components\\TextInput::make('reference_id')->numeric()\n                    ",
                    content
                )
                if new_content != content:
                    with open(path, 'w', encoding='utf-8') as file:
                        file.write(new_content)
                    print(f'Fixed Form: {f}')
                    
        elif f.endswith('Table.php'):
            path = os.path.join(root, f)
            with open(path, 'r', encoding='utf-8') as file:
                content = file.read()
            if "TextColumn::make('reference." in content:
                new_content = re.sub(
                    r"TextColumn::make\('reference\.[^']+'\)\s*",
                    r"TextColumn::make('reference_id')->numeric()\n                    ",
                    content
                )
                if new_content != content:
                    with open(path, 'w', encoding='utf-8') as file:
                        file.write(new_content)
                    print(f'Fixed Table: {f}')
