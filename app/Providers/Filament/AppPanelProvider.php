<?php

namespace App\Providers\Filament;

use App\Filament\App\Pages\AirtimeCash;
use App\Filament\App\Pages\BuyAirtime;
use App\Filament\App\Pages\BuyData;
use App\Filament\Pages\Auth\EmailVerification;
use App\Filament\Pages\Auth\RegisterUser;
use App\Filament\Pages\Settings;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('app')
            ->path('app')
            ->login()
            ->registration(RegisterUser::class)
            ->emailVerification()
            ->passwordReset()
            ->profile()
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => '#674CC4',
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Shop')
                    ->icon('heroicon-o-shopping-cart')
            ])
            ->viteTheme('resources/css/app.css')
            ->font('poppins')
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')
            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->pages([
                Pages\Dashboard::class,
                BuyAirtime::class,
                AirtimeCash::class,
                BuyData::class
            ])
            ->discoverWidgets(in: app_path('Filament/App/Widgets'), for: 'App\\Filament\\App\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                    // EnsureEmailIsVerified::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                EnsureEmailIsVerified::class,
            ]);
    }
}
