import os
import re

models_dir = r"d:\e-reckon-erp\app\Models"

models = [
    "GstRate", "GstCategory", "GstReturn", "TaxInvoice", "EInvoice", "EWayBill", "TdsRecord", "TcsRecord", "HsnSacCode",
    "Designation", "Employee", "EmployeeDocument", "Attendance", "Shift", "LeaveType", "EmployeeLeave", "Holiday", "EmployeeTransfer", "EmployeeExit",
    "SalaryStructure", "SalaryComponent", "EmployeeSalaryAssignment", "PayrollCycle", "Bonus", "Incentive", "Reimbursement", "Deduction", "PfContribution", "EsiContribution", "ProfessionalTax", "PayrollProcessing", "Payslip",
    "AssetCategory", "Asset", "AssetAllocation", "AssetTransfer", "AssetMaintenance", "AssetDepreciation", "AssetDisposal",
    "ComplaintCategory", "ServiceRequest", "ComplaintTicket", "WarrantyClaim", "ServiceAssignment", "TechnicianVisit", "ServiceReport",
    "DashboardWidget", "SalesReport", "PurchaseReport", "InventoryReport", "GstReport", "FinancialReport", "HrReport", "BranchPerformanceReport", "KpiReport"
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
