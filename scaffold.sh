#!/bin/bash

# Array of models to create
models=(
    # 1. Organization Management
    "Company" "Branch" "BusinessUnit" "Department" "CostCenter" "FinancialYear" "Currency" "Country" "State" "City"
    # 2. User & Role Management
    "Role" "Permission" "UserRole" "RolePermission" "UserBranchAccess" "LoginHistory" "Session"
    # 3. Product Master
    "ProductCategory" "ProductSubCategory" "Brand" "Product" "ProductVariant" "ProductAttribute" "AttributeValue" "ProductImage" "ProductBarcode" "ProductUnit" "HsnCode" "TaxGroup"
    # 4. Merchandise Management
    "Collection" "Season" "ProductLine" "Style" "Design" "Color" "Size" "FabricType" "PatternType" "MerchandisePlanning"
    # 5. Procurement
    "PurchaseRequisition" "Rfq" "Quotation" "PurchaseOrder" "PurchaseOrderItem" "Grn" "PurchaseReturn" "DebitNote"
    # 6. Vendor Management
    "Vendor" "VendorCategory" "VendorContact" "VendorAgreement" "VendorPriceList" "VendorPerformance" "VendorPayment"
    # 7. Inventory Management
    "Stock" "StockTransaction" "StockAdjustment" "StockLedger" "StockValuation" "StockReservation" "InventoryCount" "InventoryReconciliation"
    # 8. Warehouse Management
    "Warehouse" "WarehouseZone" "Rack" "Bin" "BinStock" "WarehouseTransfer" "PickingList" "PackingList"
    # 9. Branch Management
    "BranchSetting" "BranchInventory" "BranchUser" "BranchExpense" "BranchTarget"
    # 10. Stock Transfer
    "TransferRequest" "StockTransfer" "TransferItem" "TransferReceipt" "TransitInventory" "TransferApproval"
    # 11. Retail POS
    "PosTerminal" "PosCounter" "CashRegister" "CashOpening" "CashClosing" "PosSale" "PosSaleItem" "PosReturn" "GiftVoucher" "PaymentMode" "Receipt"
    # 12. CRM & Loyalty
    "Customer" "CustomerGroup" "LoyaltyProgram" "LoyaltyCard" "LoyaltyPoint" "CustomerWallet" "MembershipPlan" "CustomerFeedback"
    # 13. Promotions & Discounts
    "PromotionType" "DiscountRule" "Coupon" "PromoCode" "BuyXGetYOffer" "FestivalOffer" "ComboOffer" "PriceList"
    # 14. Finance & Accounts
    "ChartOfAccount" "AccountGroup" "Journal" "JournalEntry" "Ledger" "BankAccount" "CashBook" "Payment" "ContraEntry" "CreditNote" "TrialBalance"
    # 15. GST & Taxation
    "GstRate" "GstCategory" "GstReturn" "TaxInvoice" "EInvoice" "EWayBill" "TdsRecord" "TcsRecord" "HsnSacCode"
    # 16. Human Resources
    "Designation" "Employee" "EmployeeDocument" "Attendance" "Shift" "LeaveType" "EmployeeLeave" "Holiday" "EmployeeTransfer" "EmployeeExit"
    # 17. Payroll
    "SalaryStructure" "SalaryComponent" "EmployeeSalaryAssignment" "PayrollCycle" "Bonus" "Incentive" "Reimbursement" "Deduction" "PfContribution" "EsiContribution" "ProfessionalTax" "PayrollProcessing" "Payslip"
    # 18. Asset Management
    "AssetCategory" "Asset" "AssetAllocation" "AssetTransfer" "AssetMaintenance" "AssetDepreciation" "AssetDisposal"
    # 19. Service & Complaints
    "ComplaintCategory" "ServiceRequest" "ComplaintTicket" "WarrantyClaim" "ServiceAssignment" "TechnicianVisit" "ServiceReport"
    # 20. Reports & BI Dashboard
    "DashboardWidget" "SalesReport" "PurchaseReport" "InventoryReport" "GstReport" "FinancialReport" "HrReport" "BranchPerformanceReport" "KpiReport"
)

# Initialize Filament panel
php artisan filament:install --panels --quiet --no-interaction

for model in "${models[@]}"
do
    echo "Creating Model & Migration for $model..."
    php artisan make:model "$model" -m -q
    
    echo "Creating Filament Resource for $model..."
    php artisan make:filament-resource "$model" --generate -q
done

echo "Scaffolding complete!"
