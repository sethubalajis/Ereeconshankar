import os
import shutil

source_dir = r"c:\Users\Admin\reckon\ereckontamil\app\Filament\Resources\InventoryReports"
target_dir = r"c:\Users\Admin\reckon\ereckontamil\app\Filament\Resources\StockTransactionReports"

def copy_and_replace(src, dst):
    if not os.path.exists(dst):
        os.makedirs(dst)
    
    for item in os.listdir(src):
        s = os.path.join(src, item)
        d = os.path.join(dst, item.replace('InventoryReport', 'StockTransactionReport'))
        if os.path.isdir(s):
            copy_and_replace(s, d)
        else:
            with open(s, 'r', encoding='utf-8') as f:
                content = f.read()
            
            content = content.replace('InventoryReport', 'StockTransactionReport')
            content = content.replace('Inventory Reports', 'Stock Transaction Reports')
            content = content.replace('Inventory Report', 'Stock Transaction Report')
            content = content.replace('inventory-reports', 'stock-transaction-reports')
            content = content.replace('inventory_reports', 'stock_transaction_reports')
            content = content.replace('InventoryDataReportTable', 'StockTransactionDataReportTable')
            content = content.replace('inventory-data-report-table', 'stock-transaction-data-report-table')
            
            with open(d, 'w', encoding='utf-8') as f:
                f.write(content)

copy_and_replace(source_dir, target_dir)
print("Scaffolding completed successfully.")
