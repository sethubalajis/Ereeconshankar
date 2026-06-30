import os
import re

filament_dir = r'd:\e-reckon-erp\app\Filament\Resources'
false_relations = ['transporter', 'invoiceable', 'deductee', 'collectee']

for root, _, files in os.walk(filament_dir):
    for f in files:
        if f.endswith('Form.php'):
            path = os.path.join(root, f)
            with open(path, 'r', encoding='utf-8') as file:
                content = file.read()
            new_content = content
            for rel in false_relations:
                pattern = r"\\Filament\\Forms\\Components\\Select::make\('" + rel + r"_id'\)->relationship\('" + rel + r"', '[^']+'\)\s*"
                new_content = re.sub(pattern, r"\\Filament\\Forms\\Components\\TextInput::make('" + rel + r"_id')->numeric()\n                    ", new_content)
            
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
                new_content = re.sub(pattern, r"TextColumn::make('" + rel + r"_id')->numeric()\n                    ", new_content)
            
            if new_content != content:
                with open(path, 'w', encoding='utf-8') as file:
                    file.write(new_content)
                print(f'Fixed Table false relations: {f}')
