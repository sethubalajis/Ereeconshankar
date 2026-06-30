import os
import re

models_dir = r"d:\e-reckon-erp\app\Models"

models = [
    "Stock", "StockTransaction", "StockAdjustment", "StockLedger", "StockValuation", "StockReservation", "InventoryCount", "InventoryReconciliation",
    "Warehouse", "WarehouseZone", "Rack", "Bin", "BinStock", "WarehouseTransfer", "PickingList", "PackingList"
]

for model in models:
    filepath = os.path.join(models_dir, f"{model}.php")
    if not os.path.exists(filepath):
        continue
        
    with open(filepath, 'r') as f:
        original = f.read()
        
    pattern = re.compile(r'(class\s+\w+\s+extends\s+Model\s*\{)', re.DOTALL)
    if pattern.search(original):
        if "protected $guarded" not in original:
            new_original = pattern.sub(r'\1\n    protected $guarded = [];\n', original)
            with open(filepath, 'w') as f:
                f.write(new_original)
            print(f"Updated {model}.php")
    else:
        print(f"Could not find class definition in {model}.php")
