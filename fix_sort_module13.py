import os
import re

base_path = r'D:\e-reckon-erp\app\Filament\Resources'

models = [
    (r'PromotionTypes\PromotionTypeResource.php', '13. Promotions & Discounts', 1),
    (r'DiscountRules\DiscountRuleResource.php', '13. Promotions & Discounts', 2),
    (r'Coupons\CouponResource.php', '13. Promotions & Discounts', 3),
    (r'PromoCodes\PromoCodeResource.php', '13. Promotions & Discounts', 4),
    (r'BuyXGetYOffers\BuyXGetYOfferResource.php', '13. Promotions & Discounts', 5),
    (r'FestivalOffers\FestivalOfferResource.php', '13. Promotions & Discounts', 6),
    (r'ComboOffers\ComboOfferResource.php', '13. Promotions & Discounts', 7),
    (r'PriceLists\PriceListResource.php', '13. Promotions & Discounts', 8),
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
