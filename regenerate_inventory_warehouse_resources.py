import subprocess

models = [
    "Stock", "StockTransaction", "StockAdjustment", "StockLedger", "StockValuation", "StockReservation", "InventoryCount", "InventoryReconciliation",
    "Warehouse", "WarehouseZone", "Rack", "Bin", "BinStock", "WarehouseTransfer", "PickingList", "PackingList"
]

for model in models:
    cmd = f"php artisan make:filament-resource {model} --generate --force -q"
    print(f"Generating Filament Resource for {model}...")
    subprocess.run(cmd, shell=True, cwd=r"d:\e-reckon-erp")

print("Finished generating Filament resources.")
