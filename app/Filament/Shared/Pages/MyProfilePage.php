<?php

namespace App\Filament\Shared\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Jeffgreco13\FilamentBreezy\Pages\MyProfilePage as BreezyProfilePage;

class MyProfilePage extends BreezyProfilePage
{
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static string $view = 'filament-breezy::filament.pages.my-profile';


    public function getTitle(): string|Htmlable
    {
        return __('filament-breezy::default.profile.my_profile');
    }

    public function getHeading(): string|Htmlable
    {
        return __('filament-breezy::default.profile.my_profile');
    }


    public function getSubheading(): Htmlable|string|null{
        return null;
    }


    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }



    public static function getNavigationGroup(): ?string
    {
        return __('filament::resources.settings.group');
    }



}
