import subprocess

models = [
    "ProductCategory", "ProductSubCategory", "Brand", "Product", "ProductVariant", "ProductAttribute", "AttributeValue", "ProductImage", "ProductBarcode", "ProductUnit", "HsnCode", "TaxGroup",
    "Collection", "Season", "ProductLine", "Style", "Design", "Color", "Size", "FabricType", "PatternType", "MerchandisePlanning"
]

for model in models:
    cmd = f"php artisan make:filament-resource {model} --generate --force -q"
    print(f"Generating Filament Resource for {model}...")
    subprocess.run(cmd, shell=True, cwd=r"d:\e-reckon-erp")

print("Finished generating Filament resources.")
