import subprocess

models = [
    "PurchaseRequisition", "Rfq", "Quotation", "PurchaseOrder", "PurchaseOrderItem", "Grn", "PurchaseReturn", "DebitNote",
    "Vendor", "VendorCategory", "VendorContact", "VendorAgreement", "VendorPriceList", "VendorPerformance", "VendorPayment"
]

for model in models:
    cmd = f"php artisan make:filament-resource {model} --generate --force -q"
    print(f"Generating Filament Resource for {model}...")
    subprocess.run(cmd, shell=True, cwd=r"d:\e-reckon-erp")

print("Finished generating Filament resources.")
