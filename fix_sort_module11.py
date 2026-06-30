import os
import re

base_path = r'D:\e-reckon-erp\app\Filament\Resources'

models = [
    (r'PosTerminals\PosTerminalResource.php', '11. Retail POS', 1),
    (r'PosCounters\PosCounterResource.php', '11. Retail POS', 2),
    (r'CashRegisters\CashRegisterResource.php', '11. Retail POS', 3),
    (r'CashOpenings\CashOpeningResource.php', '11. Retail POS', 4),
    (r'CashClosings\CashClosingResource.php', '11. Retail POS', 5),
    (r'PosSales\PosSaleResource.php', '11. Retail POS', 6),
    (r'PosSaleItems\PosSaleItemResource.php', '11. Retail POS', 7),
    (r'PosReturns\PosReturnResource.php', '11. Retail POS', 8),
    (r'GiftVouchers\GiftVoucherResource.php', '11. Retail POS', 9),
    (r'PaymentModes\PaymentModeResource.php', '11. Retail POS', 10),
    (r'Receipts\ReceiptResource.php', '11. Retail POS', 11),
]

for rel_path, group, sort_order in models:
    file_path = os.path.join(base_path, rel_path)
    if not os.path.exists(file_path):
        print(f"File not found: {file_path}")
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
        
    print(f"Updated {rel_path} - Group: {group}, Sort: {sort_order}")
