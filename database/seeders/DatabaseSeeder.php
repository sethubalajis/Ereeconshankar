<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        
        // Seed an admin user first
        DB::table('users')->truncate();
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $faker = \Faker\Factory::create('en_IN');
        for ($i = 0; $i < 9; $i++) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        $this->call(FinancialYearSeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(ProductAttributeSeeder::class);
        $this->call(ProductUnitSeeder::class);
        $this->call(HsnCodeSeeder::class);
        $this->call(TaxGroupSeeder::class);
        $this->call(CollectionSeeder::class);
        $this->call(SeasonSeeder::class);
        $this->call(ProductLineSeeder::class);
        $this->call(StyleSeeder::class);
        $this->call(DesignSeeder::class);
        $this->call(ColorSeeder::class);
        $this->call(SizeSeeder::class);
        $this->call(FabricTypeSeeder::class);
        $this->call(PatternTypeSeeder::class);
        $this->call(VendorCategorySeeder::class);
        $this->call(VendorSeeder::class);
        $this->call(GiftVoucherSeeder::class);
        $this->call(PaymentModeSeeder::class);
        $this->call(CustomerGroupSeeder::class);
        $this->call(LoyaltyProgramSeeder::class);
        $this->call(MembershipPlanSeeder::class);
        $this->call(PromotionTypeSeeder::class);
        $this->call(CouponSeeder::class);
        $this->call(PromoCodeSeeder::class);
        $this->call(FestivalOfferSeeder::class);
        $this->call(ComboOfferSeeder::class);
        $this->call(PriceListSeeder::class);
        $this->call(JournalSeeder::class);
        $this->call(BankAccountSeeder::class);
        $this->call(CashBookSeeder::class);
        $this->call(GstRateSeeder::class);
        $this->call(GstCategorySeeder::class);
        $this->call(GstReturnSeeder::class);
        $this->call(TaxInvoiceSeeder::class);
        $this->call(TdsRecordSeeder::class);
        $this->call(TcsRecordSeeder::class);
        $this->call(HsnSacCodeSeeder::class);
        $this->call(DesignationSeeder::class);
        $this->call(ShiftSeeder::class);
        $this->call(LeaveTypeSeeder::class);
        $this->call(HolidaySeeder::class);
        $this->call(SalaryStructureSeeder::class);
        $this->call(SalaryComponentSeeder::class);
        $this->call(PayrollCycleSeeder::class);
        $this->call(AssetCategorySeeder::class);
        $this->call(ComplaintCategorySeeder::class);
        $this->call(DashboardWidgetSeeder::class);
        $this->call(GstReportSeeder::class);
        $this->call(FinancialReportSeeder::class);
        $this->call(HrReportSeeder::class);
        $this->call(KpiReportSeeder::class);
        $this->call(StateSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(AttributeValueSeeder::class);
        $this->call(MerchandisePlanningSeeder::class);
        $this->call(VendorContactSeeder::class);
        $this->call(VendorAgreementSeeder::class);
        $this->call(VendorPerformanceSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(DiscountRuleSeeder::class);
        $this->call(EInvoiceSeeder::class);
        $this->call(EWayBillSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(LoyaltyCardSeeder::class);
        $this->call(LoyaltyPointSeeder::class);
        $this->call(CustomerWalletSeeder::class);
        $this->call(CreditNoteSeeder::class);
        $this->call(ServiceRequestSeeder::class);
        $this->call(ComplaintTicketSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(ServiceReportSeeder::class);
        $this->call(BrancheSeeder::class);
        $this->call(BusinessUnitSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(CostCenterSeeder::class);
        $this->call(PurchaseOrderSeeder::class);
        $this->call(StockValuationSeeder::class);
        $this->call(WarehousSeeder::class);
        $this->call(BranchSettingSeeder::class);
        $this->call(BranchExpensSeeder::class);
        $this->call(BranchTargetSeeder::class);
        $this->call(TransferRequestSeeder::class);
        $this->call(PosTerminalSeeder::class);
        $this->call(PosCounterSeeder::class);
        $this->call(CustomerFeedbackSeeder::class);
        $this->call(AssetSeeder::class);
        $this->call(SalesReportSeeder::class);
        $this->call(PurchaseReportSeeder::class);
        $this->call(InventoryReportSeeder::class);
        $this->call(BranchPerformanceReportSeeder::class);
        $this->call(PurchaseRequisitionSeeder::class);
        $this->call(GrnSeeder::class);
        $this->call(VendorPaymentSeeder::class);
        $this->call(InventoryCountSeeder::class);
        $this->call(WarehouseZoneSeeder::class);
        $this->call(WarehouseTransferSeeder::class);
        $this->call(PickingListSeeder::class);
        $this->call(StockTransferSeeder::class);
        $this->call(TransferApprovalSeeder::class);
        $this->call(PosSaleSeeder::class);
        $this->call(AssetTransferSeeder::class);
        $this->call(AssetMaintenanceSeeder::class);
        $this->call(AssetDepreciationSeeder::class);
        $this->call(AssetDisposalSeeder::class);
        $this->call(RfqSeeder::class);
        $this->call(PurchaseReturnSeeder::class);
        $this->call(RackSeeder::class);
        $this->call(PackingListSeeder::class);
        $this->call(TransferReceiptSeeder::class);
        $this->call(PosReturnSeeder::class);
        $this->call(ReceiptSeeder::class);
        $this->call(QuotationSeeder::class);
        $this->call(DebitNoteSeeder::class);
        $this->call(BinSeeder::class);
        $this->call(UserRoleSeeder::class);
        $this->call(UserBranchAccessSeeder::class);
        $this->call(LoginHistorySeeder::class);
        $this->call(ProductCategorySeeder::class);
        $this->call(ProductSubCategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ProductVariantSeeder::class);
        $this->call(ProductVariantAttributeValueSeeder::class);
        $this->call(ProductImageSeeder::class);
        $this->call(ProductBarcodeSeeder::class);
        $this->call(PurchaseOrderItemSeeder::class);
        $this->call(VendorPriceListSeeder::class);
        $this->call(StockSeeder::class);
        $this->call(StockTransactionSeeder::class);
        $this->call(StockAdjustmentSeeder::class);
        $this->call(StockLedgerSeeder::class);
        $this->call(StockReservationSeeder::class);
        $this->call(InventoryReconciliationSeeder::class);
        $this->call(BinStockSeeder::class);
        $this->call(BranchInventorySeeder::class);
        $this->call(BranchUserSeeder::class);
        $this->call(TransferItemSeeder::class);
        $this->call(TransitInventorySeeder::class);
        $this->call(CashRegisterSeeder::class);
        $this->call(CashOpeningSeeder::class);
        $this->call(CashClosingSeeder::class);
        $this->call(PosSaleItemSeeder::class);
        $this->call(BuyXGetYOfferSeeder::class);
        $this->call(ChartOfAccountSeeder::class);
        $this->call(AccountGroupSeeder::class);
        $this->call(JournalEntrySeeder::class);
        $this->call(LedgerSeeder::class);
        $this->call(PaymentSeeder::class);
        $this->call(ContraEntrySeeder::class);
        $this->call(TrialBalanceSeeder::class);
        $this->call(EmployeeSeeder::class);
        $this->call(EmployeeDocumentSeeder::class);
        $this->call(AttendanceSeeder::class);
        $this->call(EmployeeLeaveSeeder::class);
        $this->call(EmployeeTransferSeeder::class);
        $this->call(EmployeeExitSeeder::class);
        $this->call(EmployeeSalaryAssignmentSeeder::class);
        $this->call(BonusSeeder::class);
        $this->call(IncentiveSeeder::class);
        $this->call(ReimbursementSeeder::class);
        $this->call(DeductionSeeder::class);
        $this->call(PfContributionSeeder::class);
        $this->call(EsiContributionSeeder::class);
        $this->call(ProfessionalTaxeSeeder::class);
        $this->call(PayrollProcessingSeeder::class);
        $this->call(AssetAllocationSeeder::class);
        $this->call(WarrantyClaimSeeder::class);
        $this->call(ServiceAssignmentSeeder::class);
        $this->call(TechnicianVisitSeeder::class);

        Schema::enableForeignKeyConstraints();
    }
}
