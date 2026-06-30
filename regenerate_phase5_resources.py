import subprocess

models = [
    "BranchSetting", "BranchInventory", "BranchUser", "BranchExpense", "BranchTarget",
    "TransferRequest", "StockTransfer", "TransferItem", "TransferReceipt", "TransitInventory", "TransferApproval",
    "PaymentMode", "PosTerminal", "PosCounter", "CashRegister", "CashOpening", "CashClosing", "PosSale", "PosSaleItem", "PosReturn", "GiftVoucher", "Receipt"
]

for model in models:
    cmd = f"php artisan make:filament-resource {model} --generate --force -q"
    print(f"Generating Filament Resource for {model}...")
    subprocess.run(cmd, shell=True, cwd=r"d:\e-reckon-erp")

print("Finished generating Filament resources.")
