import os
import re

base_path = r'D:\e-reckon-erp\app\Filament\Resources'

# Define all the configurations
modules = [
    {
        "group_name": "15. GST & Taxation",
        "models": [
            ("GstRates", "GstRate"),
            ("GstCategories", "GstCategory"),
            ("GstReturns", "GstReturn"),
            ("TaxInvoices", "TaxInvoice"),
            ("EInvoices", "EInvoice"),
            ("EWayBills", "EWayBill"),
            ("TdsRecords", "TdsRecord"),
            ("TcsRecords", "TcsRecord"),
            ("HsnSacCodes", "HsnSacCode"),
        ]
    },
    {
        "group_name": "16. Human Resources",
        "models": [
            ("Departments", "Department"),
            ("Designations", "Designation"),
            ("Employees", "Employee"),
            ("EmployeeDocuments", "EmployeeDocument"),
            ("Attendances", "Attendance"),
            ("Shifts", "Shift"),
            ("LeaveTypes", "LeaveType"),
            ("EmployeeLeaves", "EmployeeLeave"),
            ("Holidays", "Holiday"),
            ("EmployeeTransfers", "EmployeeTransfer"),
            ("EmployeeExits", "EmployeeExit"),
        ]
    },
    {
        "group_name": "17. Payroll",
        "models": [
            ("SalaryStructures", "SalaryStructure"),
            ("SalaryComponents", "SalaryComponent"),
            ("EmployeeSalaryAssignments", "EmployeeSalaryAssignment"),
            ("PayrollCycles", "PayrollCycle"),
            ("Bonuses", "Bonus"),
            ("Incentives", "Incentive"),
            ("Reimbursements", "Reimbursement"),
            ("Deductions", "Deduction"),
            ("PfContributions", "PfContribution"),
            ("EsiContributions", "EsiContribution"),
            ("ProfessionalTaxes", "ProfessionalTax"),
            ("TdsRecords", "TdsRecord"), # Overwrites the 15 one if same file
            ("PayrollProcessings", "PayrollProcessing"),
            ("Payslips", "Payslip"),
            ("PayrollProcesses", "PayrollProcess"), # Alternative name for PayrollProcessing
        ]
    },
    {
        "group_name": "18. Asset Management",
        "models": [
            ("AssetCategories", "AssetCategory"),
            ("Assets", "Asset"),
            ("AssetAllocations", "AssetAllocation"),
            ("AssetTransfers", "AssetTransfer"),
            ("AssetMaintenances", "AssetMaintenance"),
            ("AssetDepreciations", "AssetDepreciation"),
            ("AssetDisposals", "AssetDisposal"),
        ]
    },
    {
        "group_name": "19. Service & Complaints",
        "models": [
            ("ComplaintCategories", "ComplaintCategory"),
            ("ServiceRequests", "ServiceRequest"),
            ("ComplaintTickets", "ComplaintTicket"),
            ("WarrantyClaims", "WarrantyClaim"),
            ("ServiceAssignments", "ServiceAssignment"),
            ("TechnicianVisits", "TechnicianVisit"),
            ("ServiceReports", "ServiceReport"),
            ("CustomerFeedbacks", "CustomerFeedback"), # Might overwrite CRM one
        ]
    },
    {
        "group_name": "20. Reports & BI Dashboard",
        "models": [
            ("DashboardWidgets", "DashboardWidget"),
            ("SalesReports", "SalesReport"),
            ("PurchaseReports", "PurchaseReport"),
            ("InventoryReports", "InventoryReport"),
            ("GstReports", "GstReport"),
            ("FinancialReports", "FinancialReport"),
            ("HrReports", "HrReport"),
            ("BranchPerformanceReports", "BranchPerformanceReport"),
            ("KpiReports", "KpiReport"),
        ]
    }
]

for module in modules:
    group = module["group_name"]
    for idx, (folder, singular) in enumerate(module["models"]):
        sort_order = idx + 1
        
        rel_path = f"{folder}\\{singular}Resource.php"
        file_path = os.path.join(base_path, rel_path)
        
        # Check if file exists, sometimes plural might be different (e.g. EmployeeLeaves vs EmployeeLeafs)
        if not os.path.exists(file_path):
            # Try finding a matching resource file in the directory
            found = False
            for root, dirs, files in os.walk(base_path):
                for file in files:
                    if file == f"{singular}Resource.php":
                        file_path = os.path.join(root, file)
                        found = True
                        break
                if found:
                    break
            
            if not found:
                print(f"File not found for: {singular}Resource.php")
                continue
            
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()

        # Update or add navigationGroup
        if '$navigationGroup' in content:
            content = re.sub(r'protected\s+static\s+(?:\\?UnitEnum\|string\|null|\?string|string)\s+\$navigationGroup\s*=\s*[\'"][^\'"]*[\'"];',
                             f"protected static \\\\UnitEnum|string|null $navigationGroup = '{group}';", content)
        else:
            # Insert after $model
            content = re.sub(r'(protected\s+static\s+\?string\s+\$model\s*=\s*[^;]+;)',
                             f"\\1\n    protected static \\\\UnitEnum|string|null $navigationGroup = '{group}';", content)

        # Update or add navigationSort
        if '$navigationSort' in content:
            content = re.sub(r'protected\s+static\s+\?int\s+\$navigationSort\s*=\s*\d+;',
                             f"protected static ?int $navigationSort = {sort_order};", content)
        else:
            # Insert after $navigationGroup if possible
            if '$navigationGroup' in content:
                content = re.sub(r'(protected\s+static\s+\\\\?UnitEnum\|string\|null\s+\$navigationGroup\s*=\s*[\'"][^\'"]*[\'"];)',
                                 f"\\1\n    protected static ?int $navigationSort = {sort_order};", content)
            else:
                 content = re.sub(r'(protected\s+static\s+\?string\s+\$model\s*=\s*[^;]+;)',
                                  f"\\1\n    protected static ?int $navigationSort = {sort_order};", content)

        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(content)
            
        print(f"Updated {singular}Resource.php - Group: {group}, Sort: {sort_order}")
