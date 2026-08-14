<?php

namespace App\Providers\Filament;

use App\Http\Middleware\ForceBranchTenantInput;
use App\Models\Branch;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class BranchPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('branch')
            ->path('branch')
            ->login()
            ->tenant(Branch::class, ownershipRelationship: 'branch')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Branch/Resources'), for: 'App\Filament\Branch\Resources')
            ->discoverPages(in: app_path('Filament/Branch/Pages'), for: 'App\Filament\Branch\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Branch/Widgets'), for: 'App\Filament\Branch\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->navigationGroups([
                '5. Procurement',
                '6. Vendor Management',
                '7. Inventory Management',
                '8. Warehouse Management',
                '9. Branch Management',
                '10. Stock Transfer',
                '11. Retail POS',
                '12. CRM & Loyalty',
                '13. Promotions & Discounts',
                '16. Human Resources',
                '17. Payroll',
                '18. Asset Management',
                '19. Service & Complaints',
                '20. Reports & BI Dashboard',
            ])
            ->brandName('Branch Admin Panel')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ForceBranchTenantInput::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
