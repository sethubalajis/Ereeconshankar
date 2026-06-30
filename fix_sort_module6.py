import os

resources_dir = r'd:\e-reckon-erp\app\Filament\Resources'

order = {
    'Vendor': 1,
    'VendorCategory': 2,
    'VendorContact': 3,
    'VendorAgreement': 4,
    'VendorPriceList': 5,
    'VendorPerformance': 6,
    'VendorPayment': 7
}

for root, _, files in os.walk(resources_dir):
    for f in files:
        if f.endswith('Resource.php'):
            model_name = f.replace('Resource.php', '')
            if model_name in order:
                sort_num = order[model_name]
                path = os.path.join(root, f)
                with open(path, 'r', encoding='utf-8') as file:
                    lines = file.readlines()
                
                new_lines = []
                replaced = False
                for line in lines:
                    new_lines.append(line)
                    if not replaced and 'protected static ?string $model =' in line:
                        new_lines.append(f'    protected static ?int $navigationSort = {sort_num};\n')
                        replaced = True
                
                if replaced:
                    with open(path, 'w', encoding='utf-8') as file:
                        file.writelines(new_lines)
                    print(f'Successfully set {model_name} sort to {sort_num}')
                else:
                    print(f'Could not replace in {model_name}')
