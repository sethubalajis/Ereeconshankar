import os
import re

base_path = r'D:\e-reckon-erp\app\Filament\Resources'

models = [
    (r'ChartOfAccounts\ChartOfAccountResource.php', '14. Finance & Accounts', 1),
    (r'AccountGroups\AccountGroupResource.php', '14. Finance & Accounts', 2),
    (r'Journals\JournalResource.php', '14. Finance & Accounts', 3),
    (r'JournalEntries\JournalEntryResource.php', '14. Finance & Accounts', 4),
    (r'Ledgers\LedgerResource.php', '14. Finance & Accounts', 5),
    (r'BankAccounts\BankAccountResource.php', '14. Finance & Accounts', 6),
    (r'CashBooks\CashBookResource.php', '14. Finance & Accounts', 7),
    (r'Payments\PaymentResource.php', '14. Finance & Accounts', 8),
    (r'Receipts\ReceiptResource.php', '14. Finance & Accounts', 9),
    (r'ContraEntries\ContraEntryResource.php', '14. Finance & Accounts', 10),
    (r'CreditNotes\CreditNoteResource.php', '14. Finance & Accounts', 11),
    (r'DebitNotes\DebitNoteResource.php', '14. Finance & Accounts', 12),
    (r'TrialBalances\TrialBalanceResource.php', '14. Finance & Accounts', 13),
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
