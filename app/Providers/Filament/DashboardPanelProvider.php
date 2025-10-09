<?php

namespace App\Providers\Filament;

use App\Constants\AnnouncementPlacement;
use App\Constants\TenancyPermissionConstants;
use App\Filament\Dashboard\Pages\TenantSettings;
use App\Filament\Dashboard\Pages\TwoFactorAuth\TwoFactorAuth;
use App\Http\Middleware\UpdateUserLastSeenAt;
use App\Livewire\AddressForm;
use App\Livewire\AvatarForm;
use App\Models\Tenant;
use App\Services\TenantPermissionService;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use App\Filament\Dashboard\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class DashboardPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('dashboard')
            ->path('dashboard')
            ->colors([
                'primary' => Color::Teal,
            ])
            ->userMenuItems([
                Action::make('admin-panel')
                    ->label(__('Admin Panel'))
                    ->visible(
                        fn () => auth()->user()->isAdmin()
                    )
                    ->url(fn () => route('filament.admin.pages.dashboard'))
                    ->icon('heroicon-s-cog-8-tooth'),
                Action::make('workspace-settings')
                    ->label(__('Workspace Settings'))
                    ->visible(
                        function () {
                            $tenantPermissionService = app(TenantPermissionService::class);

                            return $tenantPermissionService->tenantUserHasPermissionTo(
                                Filament::getTenant(),
                                auth()->user(),
                                TenancyPermissionConstants::PERMISSION_UPDATE_TENANT_SETTINGS
                            );
                        }
                    )
                    ->icon('heroicon-s-cog-8-tooth')
                    ->url(fn () => TenantSettings::getUrl()),
                Action::make('profile')
                    ->label(__('Profile'))
                    ->url(fn () => route('filament.dashboard.pages.my-profile', ['tenant' => Filament::getTenant()]))
                    ->icon('heroicon-s-user-circle'),
                Action::make('two-factor-auth')
                    ->label(__('2-Factor Authentication'))
                    ->visible(
                        fn () => config('app.two_factor_auth_enabled')
                    )
                    ->url(fn () => TwoFactorAuth::getUrl())
                    ->icon('heroicon-s-lock-closed'),
            ])
            ->discoverResources(in: app_path('Filament/Dashboard/Resources'), for: 'App\\Filament\\Dashboard\\Resources')
            ->discoverPages(in: app_path('Filament/Dashboard/Pages'), for: 'App\\Filament\\Dashboard\\Pages')
            ->discoverClusters(in: app_path('Filament/Dashboard/Clusters'), for: 'App\\Filament\\Dashboard\\Clusters')
            ->pages([
                Dashboard::class,
            ])
            ->viteTheme('resources/css/filament/dashboard/theme.css')
            ->discoverWidgets(in: app_path('Filament/Dashboard/Widgets'), for: 'App\\Filament\\Dashboard\\Widgets')
            ->widgets([
                // AccountWidget::class, // Removido - solo queremos nuestros widgets personalizados
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
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
                UpdateUserLastSeenAt::class,
            ])
            ->renderHook('panels::head.start', function () {
                return view('components.layouts.partials.analytics');
            })
            ->renderHook(PanelsRenderHook::BODY_START,
                fn (): string => Blade::render("@livewire('announcement.view', ['placement' => '".AnnouncementPlacement::USER_DASHBOARD->value."'])")
            )
            ->renderHook(PanelsRenderHook::CONTENT_START,
                fn (): string => Blade::render("@include('components.crm-subscription-banner')")
            )
            ->renderHook(PanelsRenderHook::GLOBAL_SEARCH_AFTER,
                fn (): string => Blade::render("@livewire('lead-limit-indicator')")
            )
            ->navigationGroups([
                NavigationGroup::make('CRM Principal')
                    ->collapsible(),
                NavigationGroup::make('Team')
                    ->collapsible()
                    ->collapsed(true),
                NavigationGroup::make('Gestión')
                    ->collapsible()
                    ->collapsed(true),
            ])
            ->authMiddleware([
                Authenticate::class,
            ])->plugins([
                BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: false, // We'll register it manually with icon
                        shouldRegisterNavigation: false, // Adds a main navigation item for the My Profile page (default = false)
                        hasAvatars: false, // Enables the avatar upload form component (default = false)
                        slug: 'my-profile' // Sets the slug for the profile page (default = 'my-profile')
                    )
                    ->myProfileComponents([
                        AvatarForm::class,
                        AddressForm::class,
                    ]),
            ])
            ->tenantMenu()
            ->tenantMenuItems([
                Action::make('tenant_settings')
                    ->label(__('Manage Workspaces'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ->url(fn () => \App\Filament\Dashboard\Pages\TenantSettings::getUrl()),
                Action::make('team')
                    ->label(__('Team'))
                    ->icon('heroicon-o-users')
                    ->url(fn () => \App\Filament\Dashboard\Pages\Team::getUrl()),
            ])
            ->tenant(Tenant::class, 'uuid');
    }
}
