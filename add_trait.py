import os

models = [
    'JournalEntry', 'Payment', 'Receipt', 'ContraEntry', 'DebitNote', 'CreditNote',
    'Grn', 'StockTransfer', 'WarehouseTransfer', 'StockAdjustment', 'InventoryCount',
    'PurchaseOrder', 'PurchaseRequisition', 'VendorPayment', 'PurchaseReturn',
    'PosSale', 'PosReturn', 'Quotation', 'TaxInvoice', 'EInvoice', 'EWayBill',
    'PayrollProcessing', 'AssetDepreciation', 'AssetDisposal', 'AssetTransfer', 'AssetMaintenance',
    'GstReturn'
]

model_dir = "app/Models"

for model_name in models:
    file_path = os.path.join(model_dir, f"{model_name}.php")
    if os.path.exists(file_path):
        with open(file_path, 'r') as f:
            content = f.read()

        if 'use App\\Traits\\BelongsToFinancialYear;' not in content:
            # find namespace
            content = content.replace("namespace App\\Models;", "namespace App\\Models;\n\nuse App\\Traits\\BelongsToFinancialYear;")
            
            # find class definition
            class_def = f"class {model_name} extends Model\n{{"
            if class_def in content:
                content = content.replace(class_def, f"{class_def}\n    use BelongsToFinancialYear;")
            else:
                class_def = f"class {model_name} extends Authenticatable\n{{"
                if class_def in content:
                    content = content.replace(class_def, f"{class_def}\n    use BelongsToFinancialYear;")
                else:
                    class_def = f"class {model_name} extends Model"
                    # try a regex or simpler replace
                    import re
                    content = re.sub(r'(class\s+' + model_name + r'\s+extends\s+[^{]+\{)', r'\1\n    use BelongsToFinancialYear;', content)

        with open(file_path, 'w') as f:
            f.write(content)
        print(f"Updated {model_name}.php")
    else:
        print(f"Model {model_name}.php not found")

