<?php

namespace App\Providers\Filament;

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
use App\Http\Middleware\SetLocale;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->passwordReset()
            ->homeUrl(function (): string {
                if (Auth::check()) {
                    $user = Auth::user();
                    if ($user && method_exists($user, 'isCandidate') && $user->isCandidate()) {
                        return \App\Filament\Resources\ApplicationResource::getUrl('index');
                    }
                }
                return \App\Filament\Pages\Dashboard::getUrl();
            })
            ->colors([
                'primary' => [
                    50 => '#f0fdfa',
                    100 => '#ccfbf1',
                    200 => '#99f6e4',
                    300 => '#5eead4',
                    400 => '#2dd4bf',
                    500 => '#14b8a6', // Teal primary
                    600 => '#0d9488',
                    700 => '#0f766e',
                    800 => '#115e59',
                    900 => '#134e4a',
                    950 => '#042f2e',
                ],
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_START,
                fn (): View => view('filament.components.gradient-theme')
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
                \App\Filament\Pages\Chat::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                SetLocale::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                PanelsRenderHook::TOPBAR_END,
                fn (): View => view('filament.components.language-switcher-navbar')
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): View => view('filament.components.wirechat-styles')
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): View => view('filament.components.wirechat-assets')
            );
    }
}
