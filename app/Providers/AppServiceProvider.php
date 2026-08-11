<?php

namespace App\Providers;

use App\Models\User;
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

        Gate::guessPolicyNamesUsing(function (string $modelClass): ?string {
            if (! str_starts_with($modelClass, 'App\\Models\\')) {
                return null;
            }

            $policyClass = 'App\\Policies\\' . class_basename($modelClass) . 'Policy';

            return class_exists($policyClass) ? $policyClass : null;
        });
    }
}
