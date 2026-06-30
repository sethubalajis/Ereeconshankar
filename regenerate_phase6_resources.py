import subprocess

models = [
    "Customer", "CustomerGroup", "LoyaltyProgram", "LoyaltyCard", "LoyaltyPoint", "CustomerWallet", "MembershipPlan", "CustomerFeedback",
    "PromotionType", "DiscountRule", "Coupon", "PromoCode", "BuyXGetYOffer", "FestivalOffer", "ComboOffer", "PriceList",
    "ChartOfAccount", "AccountGroup", "Journal", "JournalEntry", "Ledger", "BankAccount", "CashBook", "Payment", "ContraEntry", "CreditNote", "TrialBalance"
]

for model in models:
    cmd = f"php artisan make:filament-resource {model} --generate --force -q"
    print(f"Generating Filament Resource for {model}...")
    subprocess.run(cmd, shell=True, cwd=r"d:\e-reckon-erp")

print("Finished generating Filament resources.")
