<?php

namespace App\Providers\Filament;

use App\Filament\Shared\Pages\MyProfilePage;
use App\Livewire\UserProfile;
use Blade;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Leandrocfe\FilamentApexCharts\FilamentApexCharts;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->navigationItems([
                NavigationItem::make(fn() => __('filament::resources.logout'))
                    ->url("javascript:document.querySelector('#logout').submit();")
                    ->icon('heroicon-m-arrow-left-on-rectangle')
                    ->sort(100)
                    ->group(fn() => __('filament::resources.settings.group'))
            ])
            ->navigationGroups(
                [
                    NavigationGroup::make(
                        fn() => __('filament::resoucres.doctors.group'),
                    ),
                    NavigationGroup::make(
                        fn() => __('filament::resources.settings.group')
                    )
                ]
            )
            ->renderHook(PanelsRenderHook::SIDEBAR_NAV_END, function () {
                return Blade::render('<form action="{{ $logoutLink }}" method="post" id="logout">@csrf</form>', [
                    'logoutLink' => route('logout'),
                ]);
            })
            ->plugins(
                [
                    BreezyCore::make()
                        ->myProfile(
                        )
                        ->customMyProfilePage(
                            MyProfilePage::class
                        )
                        ->withoutMyProfileComponents(
                            [
                                "personal_info"
                            ]
                        )
                        ->myProfileComponents(
                            [
                                UserProfile::class,
                            ]
                        ),
                    FilamentApexChartsPlugin::make(),
                ]
            )
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->discoverPages(app_path('Filament/Shared/Pages'), 'App\\Filament\Shared\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->discoverWidgets(in: app_path('Filament/Shared/Widgets'), for: 'App\\Filament\\Shared\\Widgets')
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
