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
use Spatie\Permission\Middleware\RoleMiddleware;
use Filament\Navigation\NavigationItem;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
                RoleMiddleware::class . ':super-admin|author', // Hanya Super Admin & Author yang bisa akses
            ])
            ->navigationItems($this->getNavigationItems());
    }

    /**
     * Filter navigasi berdasarkan role.
     */
    protected function getNavigationItems(): array
{
    $user = request()->user(); // Pastikan ada user yang login

    if ($user && $user->hasRole('super-admin')) {
        return [
            NavigationItem::make()
                ->label('Users')
                ->url('/admin/users')
                ->icon('heroicon-o-users'),

            NavigationItem::make()
                ->label('Categories')
                ->url('/admin/categories')
                ->icon('heroicon-o-folder'),

            NavigationItem::make()
                ->label('Settings')
                ->url('/admin/settings')
                ->icon('heroicon-o-cog'),
        ];
    }

    if ($user && $user->hasRole('author')) {
        return [
            NavigationItem::make()
                ->label('Articles')
                ->url('/admin/article-news')
                ->icon('heroicon-o-document-text'),
        ];
    }

    return [];
}

}