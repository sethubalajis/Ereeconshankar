<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\BusinessUnit;
use App\Models\CostCenter;
use App\Models\FinancialYear;
use App\Models\Currency;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Brand;
use App\Models\HsnCode;
use App\Models\AttributeValue;
use App\Models\TaxGroup;
use App\Models\ProductVariant;
use App\Models\ProductUnit;
use App\Models\ProductSubCategory;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductCategory;
use App\Models\ProductBarcode;
use App\Models\ProductAttribute;
use App\Models\User;
use App\Policies\CompanyPolicy;
use App\Policies\BusinessUnitPolicy;
use App\Policies\CostCenterPolicy;
use App\Policies\FinancialYearPolicy;
use App\Policies\CurrencyPolicy;
use App\Policies\CountryPolicy;
use App\Policies\StatePolicy;
use App\Policies\CityPolicy;
use App\Policies\BrandPolicy;
use App\Policies\HsnCodePolicy;
use App\Policies\AttributeValuePolicy;
use App\Policies\TaxGroupPolicy;
use App\Policies\ProductVariantPolicy;
use App\Policies\ProductUnitPolicy;
use App\Policies\ProductSubCategoryPolicy;
use App\Policies\ProductPolicy;
use App\Policies\ProductImagePolicy;
use App\Policies\ProductCategoryPolicy;
use App\Policies\ProductBarcodePolicy;
use App\Policies\ProductAttributePolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Gate::before(function (User $user, string $ability): ?bool {
            if ($user->hasAnyRole(['Super Admin', 'super admin'])) {
                return true;
            }

            return null;
        });

        Gate::policy(Company::class, CompanyPolicy::class);
        Gate::policy(BusinessUnit::class, BusinessUnitPolicy::class);
        Gate::policy(CostCenter::class, CostCenterPolicy::class);
        Gate::policy(FinancialYear::class, FinancialYearPolicy::class);
        Gate::policy(Currency::class, CurrencyPolicy::class);
        Gate::policy(Country::class, CountryPolicy::class);
        Gate::policy(State::class, StatePolicy::class);
        Gate::policy(City::class, CityPolicy::class);
        Gate::policy(Brand::class, BrandPolicy::class);
        Gate::policy(HsnCode::class, HsnCodePolicy::class);
        Gate::policy(AttributeValue::class, AttributeValuePolicy::class);
        Gate::policy(TaxGroup::class, TaxGroupPolicy::class);
        Gate::policy(ProductVariant::class, ProductVariantPolicy::class);
        Gate::policy(ProductUnit::class, ProductUnitPolicy::class);
        Gate::policy(ProductSubCategory::class, ProductSubCategoryPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(ProductImage::class, ProductImagePolicy::class);
        Gate::policy(ProductCategory::class, ProductCategoryPolicy::class);
        Gate::policy(ProductBarcode::class, ProductBarcodePolicy::class);
        Gate::policy(ProductAttribute::class, ProductAttributePolicy::class);
    }
}
