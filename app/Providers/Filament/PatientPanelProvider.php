<?php

namespace App\Providers\Filament;

use App\Filament\Shared\Pages\MyProfilePage;
use App\Livewire\UserAddress;
use App\Livewire\UserProfile;
use Blade;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
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
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;

class PatientPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('patient')
            ->path('patient')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->navigationGroups(
                [
                    NavigationGroup::make(
                        fn() => __('filament::resources.doctors.group')
                    ),
                    NavigationGroup::make(
                        fn() => __('filament::resources.settings.group')
                    )

                ]
            )
            ->navigationItems([
                NavigationItem::make(fn() => __('filament::resources.logout'))
                    ->url("javascript:document.querySelector('#logout').submit();")
                    ->icon('heroicon-m-arrow-left-on-rectangle')
                    ->sort(100)
                    ->group(fn() => __('filament::resources.settings.group'))
            ])
            ->renderHook(PanelsRenderHook::SIDEBAR_NAV_END, function () {
                return Blade::render('<form action="{{ $logoutLink }}" method="post" id="logout">@csrf</form>', [
                    'logoutLink' => route('logout'),
                ]);
            })
            ->plugins(
                [
                    BreezyCore::make()
                        ->myProfile()
                        ->customMyProfilePage(
                            MyProfilePage::class
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
                    FilamentFullCalendarPlugin::make()
                ]
            )
            ->discoverResources(in: app_path('Filament/Patient/Resources'), for: 'App\\Filament\\Patient\\Resources')
            ->discoverPages(in: app_path('Filament/Patient/Pages'), for: 'App\\Filament\\Patient\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Patient/Widgets'), for: 'App\\Filament\\Patient\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
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
