<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Branch;
use App\Models\FinancialYear;
use App\Models\AccountGroup;
use App\Models\ChartOfAccount;
use App\Models\GstRate;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\PurchaseOrder;
use App\Models\Grn;
use App\Models\TaxInvoice;
use App\Models\PosSale;
use App\Models\StockTransaction;
use App\Models\JournalEntry;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use App\Services\AccountingService;
use App\Services\GstService;
use App\Services\InventoryService;

class ErpSampleDataSeeder extends Seeder
{
    protected $company;
    protected $hqBranch;
    protected $storeBranch;
    protected $activeFy;
    
    // Accounts
    protected $accCash;
    protected $accBank;
    protected $accAr;
    protected $accAp;
    protected $accSales;
    protected $accPurchases;
    protected $accCgstIn;
    protected $accSgstIn;
    protected $accIgstIn;
    protected $accCgstOut;
    protected $accSgstOut;
    protected $accIgstOut;
    protected $accInventory;
    protected $accRetainedEarnings;
    protected $accShareCapital;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            $this->command->info('Starting ERP Sample Data Generation...');
            
            $this->setupMasterData();
            $this->setupChartOfAccounts();
            $this->setupOpeningBalances();
            $this->setupGstData();
            
            $vendors = $this->setupVendors();
            $customers = $this->setupCustomers();
            $products = $this->setupProducts();
            
            $this->generatePurchaseTransaction($vendors['intra'], $products, false);
            $this->generatePurchaseTransaction($vendors['inter'], $products, true);
            
            $this->generateSalesTransaction($customers['intra'], $products, false);
            $this->generateSalesTransaction($customers['inter'], $products, true);
            
            DB::commit();
            $this->command->info('ERP Sample Data Generation Completed Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error occurred: ' . $e->getMessage());
            $this->command->error($e->getTraceAsString());
        }
    }

    private function setupMasterData()
    {
        $this->command->info('Setting up Master Data (Company, Branch, FY)...');
        
        $stateId = \App\Models\State::first()->id ?? 1;

        $this->company = Company::firstOrCreate(
            ['name' => 'Sample ERP Company Ltd.'],
            [
                'code' => 'SMPL-CMP-01',
                'email' => 'contact@sampleerp.com',
                'phone' => '9876543210',
                'gst_number' => '27AAACA1234A1Z5',
                'state_id' => $stateId,
                'status' => 1
            ]
        );

        $this->hqBranch = Branch::firstOrCreate(
            ['company_id' => $this->company->id, 'name' => 'HQ Branch'],
            ['code' => 'HQ-01']
        );
        $this->storeBranch = Branch::firstOrCreate(
            ['company_id' => $this->company->id, 'name' => 'Store Branch'],
            ['code' => 'ST-01']
        );

        FinancialYear::firstOrCreate(
            ['name' => 'FY 2025-26', 'company_id' => $this->company->id],
            ['start_date' => '2025-04-01', 'end_date' => '2026-03-31', 'is_active' => false]
        );

        $this->activeFy = FinancialYear::firstOrCreate(
            ['name' => 'FY 2026-27', 'company_id' => $this->company->id],
            ['start_date' => '2026-04-01', 'end_date' => '2027-03-31', 'is_active' => true]
        );
    }

    private function setupChartOfAccounts()
    {
        $this->command->info('Setting up Chart of Accounts...');
        
        $agAssets = AccountGroup::firstOrCreate(['name' => 'Assets', 'type' => 'asset', 'code' => 'AG-0001']);
        $agCurrentAssets = AccountGroup::firstOrCreate(['name' => 'Current Assets', 'type' => 'asset', 'parent_id' => $agAssets->id, 'code' => 'AG-0002']);
        
        $agLiab = AccountGroup::firstOrCreate(['name' => 'Liabilities', 'type' => 'liability', 'code' => 'AG-0003']);
        $agCurrentLiab = AccountGroup::firstOrCreate(['name' => 'Current Liabilities', 'type' => 'liability', 'parent_id' => $agLiab->id, 'code' => 'AG-0004']);
        
        $agEquity = AccountGroup::firstOrCreate(['name' => 'Equity', 'type' => 'equity', 'code' => 'AG-0005']);
        $agRevenue = AccountGroup::firstOrCreate(['name' => 'Revenue', 'type' => 'revenue', 'code' => 'AG-0006']);
        $agExpense = AccountGroup::firstOrCreate(['name' => 'Expenses', 'type' => 'expense', 'code' => 'AG-0007']);

        $this->accCash = ChartOfAccount::firstOrCreate(['account_name' => 'Cash in Hand'], ['account_group_id' => $agCurrentAssets->id, 'account_code' => 'SMPL-1001', 'account_type' => 'asset']);
        $this->accBank = ChartOfAccount::firstOrCreate(['account_name' => 'HDFC Bank'], ['account_group_id' => $agCurrentAssets->id, 'account_code' => 'SMPL-1002', 'account_type' => 'asset']);
        $this->accAr = ChartOfAccount::firstOrCreate(['account_name' => 'Accounts Receivable'], ['account_group_id' => $agCurrentAssets->id, 'account_code' => 'SMPL-1003', 'account_type' => 'asset']);
        $this->accInventory = ChartOfAccount::firstOrCreate(['account_name' => 'Inventory'], ['account_group_id' => $agCurrentAssets->id, 'account_code' => 'SMPL-1004', 'account_type' => 'asset']);
        
        $this->accCgstIn = ChartOfAccount::firstOrCreate(['account_name' => 'Input CGST'], ['account_group_id' => $agCurrentAssets->id, 'account_code' => 'SMPL-1010', 'account_type' => 'asset']);
        $this->accSgstIn = ChartOfAccount::firstOrCreate(['account_name' => 'Input SGST'], ['account_group_id' => $agCurrentAssets->id, 'account_code' => 'SMPL-1011', 'account_type' => 'asset']);
        $this->accIgstIn = ChartOfAccount::firstOrCreate(['account_name' => 'Input IGST'], ['account_group_id' => $agCurrentAssets->id, 'account_code' => 'SMPL-1012', 'account_type' => 'asset']);

        $this->accAp = ChartOfAccount::firstOrCreate(['account_name' => 'Accounts Payable'], ['account_group_id' => $agCurrentLiab->id, 'account_code' => 'SMPL-2001', 'account_type' => 'liability']);
        $this->accCgstOut = ChartOfAccount::firstOrCreate(['account_name' => 'Output CGST'], ['account_group_id' => $agCurrentLiab->id, 'account_code' => 'SMPL-2010', 'account_type' => 'liability']);
        $this->accSgstOut = ChartOfAccount::firstOrCreate(['account_name' => 'Output SGST'], ['account_group_id' => $agCurrentLiab->id, 'account_code' => 'SMPL-2011', 'account_type' => 'liability']);
        $this->accIgstOut = ChartOfAccount::firstOrCreate(['account_name' => 'Output IGST'], ['account_group_id' => $agCurrentLiab->id, 'account_code' => 'SMPL-2012', 'account_type' => 'liability']);

        $this->accShareCapital = ChartOfAccount::firstOrCreate(['account_name' => 'Share Capital'], ['account_group_id' => $agEquity->id, 'account_code' => 'SMPL-3001', 'account_type' => 'equity']);
        $this->accRetainedEarnings = ChartOfAccount::firstOrCreate(['account_name' => 'Retained Earnings'], ['account_group_id' => $agEquity->id, 'account_code' => 'SMPL-3002', 'account_type' => 'equity']);

        $this->accSales = ChartOfAccount::firstOrCreate(['account_name' => 'Sales Revenue'], ['account_group_id' => $agRevenue->id, 'account_code' => 'SMPL-4001', 'account_type' => 'revenue']);
        
        $this->accPurchases = ChartOfAccount::firstOrCreate(['account_name' => 'Purchase Expense'], ['account_group_id' => $agExpense->id, 'account_code' => 'SMPL-5001', 'account_type' => 'expense']);
    }

    private function setupOpeningBalances()
    {
        $this->command->info('Setting up Opening Balances...');
        $accService = new AccountingService();
        
        // Let's create an opening balance journal entry
        $journalData = [
            'journal_number' => 'JRN-OP-001',
            'journal_date' => $this->activeFy->start_date,
            'reference' => 'Opening Balances',
            'status' => 'posted',
        ];
        
        $entries = [
            ['chart_of_account_id' => $this->accCash->id, 'debit' => 75000, 'credit' => 0],
            ['chart_of_account_id' => $this->accBank->id, 'debit' => 850000, 'credit' => 0],
            ['chart_of_account_id' => $this->accInventory->id, 'debit' => 1200000, 'credit' => 0],
            ['chart_of_account_id' => $this->accAr->id, 'debit' => 650000, 'credit' => 0],
            ['chart_of_account_id' => $this->accAp->id, 'debit' => 0, 'credit' => 500000],
            ['chart_of_account_id' => $this->accShareCapital->id, 'debit' => 0, 'credit' => 1500000],
            ['chart_of_account_id' => $this->accRetainedEarnings->id, 'debit' => 0, 'credit' => 775000],
        ];
        
        try {
            $accService->postJournalEntry($journalData, $entries);
        } catch (\Exception $e) {
            // Might already exist due to unique journal number
        }
    }
    
    private function setupGstData()
    {
        $this->command->info('Setting up GST Data...');
        $gst18 = GstRate::firstOrCreate(['igst_percentage' => 18.0], ['cgst_percentage' => 9.0, 'sgst_percentage' => 9.0]);
        $gst12 = GstRate::firstOrCreate(['igst_percentage' => 12.0], ['cgst_percentage' => 6.0, 'sgst_percentage' => 6.0]);
        
        return ['18' => $gst18, '12' => $gst12];
    }

    private function setupVendors()
    {
        $this->command->info('Setting up Vendors...');
        
        $intraStateId = \App\Models\State::first()->id ?? 1;
        $interStateId = \App\Models\State::where('id', '!=', $intraStateId)->first()->id ?? 2;

        $intra = Vendor::firstOrCreate(
            ['name' => 'Sample Intra-State Vendor'],
            ['gst_number' => '27VEND1234A1Z5']
        );
        $inter = Vendor::firstOrCreate(
            ['name' => 'Sample Inter-State Vendor'],
            ['gst_number' => '29VEND5678B1Z5']
        );
        return ['intra' => $intra, 'inter' => $inter];
    }

    private function setupCustomers()
    {
        $this->command->info('Setting up Customers...');
        
        $intraStateId = \App\Models\State::first()->id ?? 1;
        $interStateId = \App\Models\State::where('id', '!=', $intraStateId)->first()->id ?? 2;

        $intra = Customer::firstOrCreate(
            ['name' => 'Sample Intra-State Customer'],
            ['phone' => '9999999991', 'gst_number' => '27CUST1234A1Z5', 'state_id' => $intraStateId]
        );
        $inter = Customer::firstOrCreate(
            ['name' => 'Sample Inter-State Customer'],
            ['phone' => '9999999992', 'gst_number' => '29CUST5678B1Z5', 'state_id' => $interStateId]
        );
        return ['intra' => $intra, 'inter' => $inter];
    }

    private function setupProducts()
    {
        $this->command->info('Setting up Products...');
        $cat = ProductCategory::firstOrCreate(['name' => 'Electronics']);
        
        $p1 = Product::firstOrCreate(
            ['name' => 'Sample Product A'],
            ['product_category_id' => $cat->id, 'base_price' => 1000, 'product_type' => 1]
        );
        $v1 = ProductVariant::firstOrCreate(
            ['product_id' => $p1->id, 'sku' => 'SMPL-A-01'],
            ['cost_price' => 1000, 'selling_price' => 1500]
        );
        
        $p2 = Product::firstOrCreate(
            ['name' => 'Sample Product B'],
            ['product_category_id' => $cat->id, 'base_price' => 2000, 'product_type' => 1]
        );
        $v2 = ProductVariant::firstOrCreate(
            ['product_id' => $p2->id, 'sku' => 'SMPL-B-01'],
            ['cost_price' => 2000, 'selling_price' => 3000]
        );
        
        return [['product' => $p1, 'variant' => $v1], ['product' => $p2, 'variant' => $v2]];
    }

    private function generatePurchaseTransaction($vendor, $products, $isInterState)
    {
        $this->command->info('Generating Purchase Transaction for Vendor: ' . $vendor->name);
        
        // PO
        $po = PurchaseOrder::create([
            'po_number' => 'PO-' . time() . '-' . rand(10, 99),
            'vendor_id' => $vendor->id,
            'branch_id' => $this->hqBranch->id,
            'po_date' => now(),
            'status' => 'completed',
            'total_amount' => 0
        ]);

        $items = [];
        $totalAmount = 0;
        foreach($products as $p) {
            $qty = 10;
            $unitPrice = $p['variant']->cost_price;
            $items[] = [
                'product_id' => $p['product']->id,
                'product_variant_id' => $p['variant']->id,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'total_price' => $qty * $unitPrice
            ];
            $totalAmount += ($qty * $unitPrice);
        }
        $po->update(['total_amount' => $totalAmount]);

        // GRN triggers stock update and accounting logic due to our previous Grn logic updates
        $grn = Grn::create([
            'purchase_order_id' => $po->id,
            'vendor_id' => $vendor->id,
            'branch_id' => $this->hqBranch->id,
            'financial_year_id' => $this->activeFy->id,
            'receive_date' => now(),
            'status' => 'draft',
            'received_items' => $items
        ]);
        
        // Update to received to trigger accounting
        $grn->update(['status' => 'received']);
    }

    private function generateSalesTransaction($customer, $products, $isInterState)
    {
        $this->command->info('Generating Sales Transaction for Customer: ' . $customer->name);
        
        // Just directly simulating POS Sale or Sales Invoice for simplicity
        $totalTaxable = 0;
        $items = [];
        foreach($products as $p) {
            $qty = 2;
            $unitPrice = $p['variant']->selling_price;
            $items[] = [
                'product_id' => $p['product']->id,
                'product_variant_id' => $p['variant']->id,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'total_price' => $qty * $unitPrice,
            ];
            $totalTaxable += ($qty * $unitPrice);
            
            // Stock reduction
            $invService = new InventoryService();
            $invService->updateStock(
                $p['product']->id,
                $p['variant']->id,
                $this->hqBranch->id,
                null,
                'out',
                $qty,
                $unitPrice,
                'SALE-' . time(),
                'App\\Models\\PosSale'
            );
        }

        // Acc & GST
        $gstService = app(GstService::class);
        $accService = app(AccountingService::class);

        // Assume standard 18% for simple sample
        $gstData = $gstService->calculateGst((float)$totalTaxable, 18.0); 
        $totalGst = $gstData['total_gst'];
        $totalAmount = $totalTaxable + $totalGst;
        
        $saleId = rand(1000, 9999);
        
        // Accounting
        $accService->postJournalEntry([
            'journal_number' => 'JRN-SALE-' . $saleId . time(),
            'journal_date' => now(),
            'reference' => 'Sales ' . $saleId,
            'status' => 'posted',
        ], [
            [
                'chart_of_account_id' => $this->accAr->id,
                'debit' => $totalAmount,
                'credit' => 0,
            ],
            [
                'chart_of_account_id' => $this->accSales->id,
                'debit' => 0,
                'credit' => $totalTaxable,
            ],
            [
                'chart_of_account_id' => $isInterState ? $this->accIgstOut->id : $this->accCgstOut->id,
                'debit' => 0,
                'credit' => $isInterState ? $totalGst : $totalGst/2,
            ],
            [
                'chart_of_account_id' => $isInterState ? $this->accIgstOut->id : $this->accSgstOut->id,
                'debit' => 0,
                'credit' => $isInterState ? 0 : $totalGst/2, // 0 for IGST 2nd leg
            ]
        ]);
    }
}
