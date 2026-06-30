import os
import re
import json

models_dir = r'd:\e-reckon-erp\app\Models'
valid_models = set(f[:-4] for f in os.listdir(models_dir) if f.endswith('.php'))

invalid_relations = {}

for f in os.listdir(models_dir):
    if not f.endswith('.php'): continue
    path = os.path.join(models_dir, f)
    with open(path, 'r', encoding='utf-8') as file:
        content = file.read()
    
    matches = re.findall(r"belongsTo\(([A-Za-z0-9_]+)::class,\s*'([^']+)'\)", content)
    for model_class, foreign_key in matches:
        if model_class not in valid_models and model_class != 'self':
            if f not in invalid_relations:
                invalid_relations[f] = []
            invalid_relations[f].append((model_class, foreign_key))

print(json.dumps(invalid_relations, indent=2))
