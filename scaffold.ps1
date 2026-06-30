$models = @(
    "Company", "Branch", "BusinessUnit", "Department", "CostCenter", "FinancialYear", "Currency", "Country", "State", "City",
    "Role", "Permission", "UserRole", "RolePermission", "UserBranchAccess", "LoginHistory", "Session",
    "ProductCategory", "ProductSubCategory", "Brand", "Product", "ProductVariant", "ProductAttribute", "AttributeValue", "ProductImage", "ProductBarcode", "ProductUnit", "HsnCode", "TaxGroup",
    "Collection", "Season", "ProductLine", "Style", "Design", "Color", "Size", "FabricType", "PatternType", "MerchandisePlanning",
    "PurchaseRequisition", "Rfq", "Quotation", "PurchaseOrder", "PurchaseOrderItem", "Grn", "PurchaseReturn", "DebitNote",
    "Vendor", "VendorCategory", "VendorContact", "VendorAgreement", "VendorPriceList", "VendorPerformance", "VendorPayment",
    "Stock", "StockTransaction", "StockAdjustment", "StockLedger", "StockValuation", "StockReservation", "InventoryCount", "InventoryReconciliation",
    "Warehouse", "WarehouseZone", "Rack", "Bin", "BinStock", "WarehouseTransfer", "PickingList", "PackingList",
    "BranchSetting", "BranchInventory", "BranchUser", "BranchExpense", "BranchTarget",
    "TransferRequest", "StockTransfer", "TransferItem", "TransferReceipt", "TransitInventory", "TransferApproval",
    "PosTerminal", "PosCounter", "CashRegister", "CashOpening", "CashClosing", "PosSale", "PosSaleItem", "PosReturn", "GiftVoucher", "PaymentMode", "Receipt",
    "Customer", "CustomerGroup", "LoyaltyProgram", "LoyaltyCard", "LoyaltyPoint", "CustomerWallet", "MembershipPlan", "CustomerFeedback",
    "PromotionType", "DiscountRule", "Coupon", "PromoCode", "BuyXGetYOffer", "FestivalOffer", "ComboOffer", "PriceList",
    "ChartOfAccount", "AccountGroup", "Journal", "JournalEntry", "Ledger", "BankAccount", "CashBook", "Payment", "ContraEntry", "CreditNote", "TrialBalance",
    "GstRate", "GstCategory", "GstReturn", "TaxInvoice", "EInvoice", "EWayBill", "TdsRecord", "TcsRecord", "HsnSacCode",
    "Designation", "Employee", "EmployeeDocument", "Attendance", "Shift", "LeaveType", "EmployeeLeave", "Holiday", "EmployeeTransfer", "EmployeeExit",
    "SalaryStructure", "SalaryComponent", "EmployeeSalaryAssignment", "PayrollCycle", "Bonus", "Incentive", "Reimbursement", "Deduction", "PfContribution", "EsiContribution", "ProfessionalTax", "PayrollProcessing", "Payslip",
    "AssetCategory", "Asset", "AssetAllocation", "AssetTransfer", "AssetMaintenance", "AssetDepreciation", "AssetDisposal",
    "ComplaintCategory", "ServiceRequest", "ComplaintTicket", "WarrantyClaim", "ServiceAssignment", "TechnicianVisit", "ServiceReport",
    "DashboardWidget", "SalesReport", "PurchaseReport", "InventoryReport", "GstReport", "FinancialReport", "HrReport", "BranchPerformanceReport", "KpiReport"
)

php artisan filament:install --panels --quiet --no-interaction

foreach ($model in $models) {
    Write-Host "Creating Model & Migration for $model..."
    php artisan make:model $model -m -q
    
    Write-Host "Creating Filament Resource for $model..."
    php artisan make:filament-resource $model -q
}

Write-Host "Scaffolding complete!"
