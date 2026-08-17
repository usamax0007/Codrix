<?php

namespace App\Providers\Filament;

use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class UsersPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('users')
            ->path('users')
            ->login()

            ->brandName('XCodrix')
            ->brandLogo('/images/xcodrix-logo.png')
            ->brandLogoHeight('2rem')
            ->darkModeBrandLogo('/images/xcodrix-logo.png')
            ->favicon('/images/xcodrix-logo.png')

            ->colors([
                'primary' => Color::hex('#00E5C0'),
                'info' => Color::hex('#0066FF'),
                'gray' => Color::Slate,
            ])

            ->defaultThemeMode(ThemeMode::Dark)
            ->discoverResources(in: app_path('Filament/Users/Resources'), for: 'App\\Filament\\Users\\Resources')
            ->discoverPages(in: app_path('Filament/Users/Pages'), for: 'App\\Filament\\Users\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Users/Widgets'), for: 'App\\Filament\\Users\\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}