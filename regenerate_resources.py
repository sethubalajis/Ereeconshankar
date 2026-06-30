import subprocess

models = [
    "Company", "Branch", "BusinessUnit", "Department", "CostCenter", "FinancialYear", "Currency", "Country", "State", "City",
    "Role", "Permission", "UserRole", "RolePermission", "UserBranchAccess", "LoginHistory"
]

for model in models:
    cmd = f"php artisan make:filament-resource {model} --generate --force -q"
    print(f"Generating Filament Resource for {model}...")
    subprocess.run(cmd, shell=True, cwd=r"d:\e-reckon-erp")

print("Finished generating Filament resources.")
