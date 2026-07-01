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
use App\Policies\CompanyPolicy;
use App\Policies\BusinessUnitPolicy;
use App\Policies\CostCenterPolicy;
use App\Policies\FinancialYearPolicy;
use App\Policies\CurrencyPolicy;
use App\Policies\CountryPolicy;
use App\Policies\StatePolicy;
use App\Policies\CityPolicy;
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
        Gate::policy(Company::class, CompanyPolicy::class);
        Gate::policy(BusinessUnit::class, BusinessUnitPolicy::class);
        Gate::policy(CostCenter::class, CostCenterPolicy::class);
        Gate::policy(FinancialYear::class, FinancialYearPolicy::class);
        Gate::policy(Currency::class, CurrencyPolicy::class);
        Gate::policy(Country::class, CountryPolicy::class);
        Gate::policy(State::class, StatePolicy::class);
        Gate::policy(City::class, CityPolicy::class);
    }
}
