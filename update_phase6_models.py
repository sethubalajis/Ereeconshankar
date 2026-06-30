import os
import re

models_dir = r"d:\e-reckon-erp\app\Models"

models = [
    "Customer", "CustomerGroup", "LoyaltyProgram", "LoyaltyCard", "LoyaltyPoint", "CustomerWallet", "MembershipPlan", "CustomerFeedback",
    "PromotionType", "DiscountRule", "Coupon", "PromoCode", "BuyXGetYOffer", "FestivalOffer", "ComboOffer", "PriceList",
    "ChartOfAccount", "AccountGroup", "Journal", "JournalEntry", "Ledger", "BankAccount", "CashBook", "Payment", "ContraEntry", "CreditNote", "TrialBalance"
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
