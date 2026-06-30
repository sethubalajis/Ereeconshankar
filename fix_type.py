import os
import re

base_path = r'D:\e-reckon-erp\app\Filament\Resources'

models = [
    r'Branches\BranchResource.php',
    r'BranchSettings\BranchSettingResource.php',
    r'BranchInventories\BranchInventoryResource.php',
    r'BranchUsers\BranchUserResource.php',
    r'BranchExpenses\BranchExpenseResource.php',
    r'BranchTargets\BranchTargetResource.php'
]

for rel_path in models:
    file_path = os.path.join(base_path, rel_path)
    if not os.path.exists(file_path):
        continue
        
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Fix the typing issue
    content = content.replace('protected static ?string $navigationGroup',
                              'protected static \\UnitEnum|string|null $navigationGroup')

    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)
