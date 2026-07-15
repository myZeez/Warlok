<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class MitraPanelProvider extends PanelProvider
{
    public function boot(): void
    {
        // The Mitra panel's own location-picker widget (TokoSaya) needs Leaflet, but Filament
        // panels don't go through the public site's Vite bundle -- registered here instead,
        // loaded from Leaflet's CDN since this is a one-off widget, not worth a real package.
        FilamentAsset::register([
            Css::make('leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css'),
            Js::make('leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'),
            Js::make('location-picker-js', asset('js/location-picker.js')),
        ]);
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('mitra')
            ->path('mitra')
            ->login()
            ->profile()
            ->colors([
                'primary' => [
                    50 => 'oklch(0.97 0.02 55)',
                    100 => 'oklch(0.94 0.045 54)',
                    200 => 'oklch(0.88 0.08 51)',
                    300 => 'oklch(0.8 0.12 47)',
                    400 => 'oklch(0.74 0.16 44)',
                    500 => 'oklch(0.68 0.19 41)',
                    600 => 'oklch(0.58 0.19 37)',
                    700 => 'oklch(0.52 0.17 34)',
                    800 => 'oklch(0.42 0.14 32)',
                    900 => 'oklch(0.34 0.11 30)',
                    950 => 'oklch(0.26 0.08 28)',
                ],
                'danger' => Color::Rose,
                'warning' => Color::Amber,
            ])
            ->font('Plus Jakarta Sans')
            ->brandName('Warlok Mitra')
            ->discoverResources(in: app_path('Filament/Mitra/Resources'), for: 'App\Filament\Mitra\Resources')
            ->discoverPages(in: app_path('Filament/Mitra/Pages'), for: 'App\Filament\Mitra\Pages')
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
