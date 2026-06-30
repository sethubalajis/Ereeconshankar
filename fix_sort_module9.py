import os
import re

base_path = r'D:\e-reckon-erp\app\Filament\Resources'

models = [
    (r'Branches\BranchResource.php', '9. Branch Management', 1),
    (r'BranchSettings\BranchSettingResource.php', '9. Branch Management', 2),
    (r'BranchInventories\BranchInventoryResource.php', '9. Branch Management', 3),
    (r'BranchUsers\BranchUserResource.php', '9. Branch Management', 4),
    (r'BranchExpenses\BranchExpenseResource.php', '9. Branch Management', 5),
    (r'BranchTargets\BranchTargetResource.php', '9. Branch Management', 6),
]

for rel_path, group, sort_order in models:
    file_path = os.path.join(base_path, rel_path)
    if not os.path.exists(file_path):
        print(f"File not found: {file_path}")
        continue
        
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Update or add navigationGroup
    if '$navigationGroup' in content:
        content = re.sub(r'protected\s+static\s+(?:\\?UnitEnum\|string\|null|\?string|string)\s+\$navigationGroup\s*=\s*[\'"][^\'"]*[\'"];',
                         f"protected static ?string $navigationGroup = '{group}';", content)
    else:
        # Insert after $model
        content = re.sub(r'(protected\s+static\s+\?string\s+\$model\s*=\s*[^;]+;)',
                         f"\\1\n    protected static ?string $navigationGroup = '{group}';", content)

    # Update or add navigationSort
    if '$navigationSort' in content:
        content = re.sub(r'protected\s+static\s+\?int\s+\$navigationSort\s*=\s*\d+;',
                         f"protected static ?int $navigationSort = {sort_order};", content)
    else:
        # Insert after $navigationGroup if possible
        if '$navigationGroup' in content:
            content = re.sub(r'(protected\s+static\s+\?string\s+\$navigationGroup\s*=\s*[\'"][^\'"]*[\'"];)',
                             f"\\1\n    protected static ?int $navigationSort = {sort_order};", content)
        else:
             content = re.sub(r'(protected\s+static\s+\?string\s+\$model\s*=\s*[^;]+;)',
                              f"\\1\n    protected static ?int $navigationSort = {sort_order};", content)

    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)
        
    print(f"Updated {rel_path} - Group: {group}, Sort: {sort_order}")
