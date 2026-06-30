import subprocess

models = [
    "GstRate", "GstCategory", "GstReturn", "TaxInvoice", "EInvoice", "EWayBill", "TdsRecord", "TcsRecord", "HsnSacCode",
    "Designation", "Employee", "EmployeeDocument", "Attendance", "Shift", "LeaveType", "EmployeeLeave", "Holiday", "EmployeeTransfer", "EmployeeExit",
    "SalaryStructure", "SalaryComponent", "EmployeeSalaryAssignment", "PayrollCycle", "Bonus", "Incentive", "Reimbursement", "Deduction", "PfContribution", "EsiContribution", "ProfessionalTax", "PayrollProcessing", "Payslip",
    "AssetCategory", "Asset", "AssetAllocation", "AssetTransfer", "AssetMaintenance", "AssetDepreciation", "AssetDisposal",
    "ComplaintCategory", "ServiceRequest", "ComplaintTicket", "WarrantyClaim", "ServiceAssignment", "TechnicianVisit", "ServiceReport",
    "DashboardWidget", "SalesReport", "PurchaseReport", "InventoryReport", "GstReport", "FinancialReport", "HrReport", "BranchPerformanceReport", "KpiReport"
]

for model in models:
    cmd = f"php artisan make:filament-resource {model} --generate --force -q"
    print(f"Generating Filament Resource for {model}...")
    subprocess.run(cmd, shell=True, cwd=r"d:\e-reckon-erp")

print("Finished generating Filament resources.")
