<?php

namespace App\Providers\Filament;

use App\Filament\App\Pages\AirtimeCash;
use App\Filament\App\Pages\BuyAirtime;
use App\Filament\App\Pages\BuyData;
use App\Filament\App\Pages\Dashboard;
use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Pages\Auth\RegisterUser;
use App\Filament\Pages\Auth\RequestPasswordReset;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Notifications\Livewire\Notifications;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\VerticalAlignment;
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
            ->sidebarWidth('15rem')
            ->path('app')
            ->login()
            ->registration(RegisterUser::class)
            ->emailVerification()
            ->passwordReset(RequestPasswordReset::class)
            ->profile(EditProfile::class, isSimple: false)
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => '#674CC4',
            ])
            ->bootUsing(function () {
                Notifications::alignment(Alignment::Center);
                Notifications::verticalAlignment(VerticalAlignment::Center);
            })
            ->renderHook('panels::body.end', fn() => view('customFooter'))
            ->navigationGroups([
                'Wallet',
                'Services',
            ])
            ->authGuard('web')
            
            ->viteTheme('resources/css/app.css')
            ->font('poppins')
            ->discoverResources(in: app_path('Filament/App/Resources'), for: 'App\\Filament\\App\\Resources')

            ->discoverPages(in: app_path('Filament/App/Pages'), for: 'App\\Filament\\App\\Pages')
            ->pages([
                Dashboard::class,
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
