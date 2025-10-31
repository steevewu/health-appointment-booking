<?php

namespace App\Providers\Filament;

use App\Livewire\UserAddress;
use App\Livewire\UserProfile;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;

class OfficerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('officer')
            ->path('officer')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->plugins(
                [
                    FilamentFullCalendarPlugin::make(),
                    BreezyCore::make()
                        ->myProfile(
                            shouldRegisterUserMenu: false,
                            shouldRegisterNavigation: true,
                            hasAvatars: false,
                            navigationGroup: 'Settings'
                        )
                        ->myProfileComponents(
                            [
                                UserProfile::class,
                                UserAddress::class,
                            ]
                        )
                        ->withoutMyProfileComponents(
                            [
                                'personal_info'
                            ]
                        ),
                    FilamentApexChartsPlugin::make()
                ]
            )
            ->discoverResources(in: app_path('Filament/Officer/Resources'), for: 'App\\Filament\\Officer\\Resources')
            ->discoverPages(in: app_path('Filament/Officer/Pages'), for: 'App\\Filament\\Officer\\Pages')
            ->discoverPages(app_path('Filament/Shared/Pages'), 'App\\Filament\Shared\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Officer/Widgets'), for: 'App\\Filament\\Officer\\Widgets')
            ->widgets([
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
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
