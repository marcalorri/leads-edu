<?php

namespace App\Models;

use App\Notifications\Auth\QueuedVerifyEmail;
use App\Services\OrderService;
use App\Services\SubscriptionService;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laragear\TwoFactor\Contracts\TwoFactorAuthenticatable;
use Laragear\TwoFactor\TwoFactorAuthentication;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasTenants, MustVerifyEmail, TwoFactorAuthenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable, TwoFactorAuthentication;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'password',
        'is_admin',
        'public_name',
        'is_blocked',
        'notes',
        'phone_number',
        'phone_number_verified_at',
        'last_seen_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_number_verified_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    public function roadmapItems(): HasMany
    {
        return $this->hasMany(RoadmapItem::class);
    }

    public function roadmapItemUpvotes(): BelongsToMany
    {
        return $this->belongsToMany(RoadmapItem::class, 'roadmap_item_user_upvotes');
    }

    public function userParameters(): HasMany
    {
        return $this->hasMany(UserParameter::class);
    }

    public function stripeData(): HasMany
    {
        return $this->hasMany(UserStripeData::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function subscriptionTrials(): HasMany
    {
        return $this->hasMany(UserSubscriptionTrial::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() == 'admin' && ! $this->is_admin) {
            return false;
        }

        return true;
    }

    public function getPublicName()
    {
        return $this->public_name ?? $this->name;
    }

    public function scopeAdmin($query)
    {
        return $query->where('is_admin', true);
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }

    /**
     * Check if user is admin of a specific tenant
     */
    public function isTenantAdmin(?Tenant $tenant = null): bool
    {
        // Global admins are always tenant admins
        if ($this->is_admin) {
            return true;
        }

        $tenant = $tenant ?? filament()->getTenant();
        if (!$tenant) {
            return false;
        }

        // Get the pivot record for this tenant
        $tenantUser = $this->tenants()->where('tenant_id', $tenant->id)->first();
        if (!$tenantUser) {
            return false;
        }

        // Check if the pivot (TenantUser) has admin role
        return $tenantUser->pivot->hasRole(\App\Constants\TenancyPermissionConstants::ROLE_ADMIN);
    }

    /**
     * Check if user can view all leads in tenant (admin permission)
     */
    public function canViewAllLeads(?Tenant $tenant = null): bool
    {
        // Si no se pasa tenant, intentar obtenerlo de Filament
        if (!$tenant) {
            try {
                $tenant = filament()->getTenant();
            } catch (\Exception $e) {
                // En contexto de API u otros contextos sin Filament
                return false;
            }
        }
        
        if (!$tenant) {
            return false;
        }

        if ($this->isTenantAdmin($tenant)) {
            return true;
        }

        // Check if the TenantUser pivot has the permission
        $tenantUser = $this->tenants()->where('tenant_id', $tenant->id)->first();
        if (!$tenantUser) {
            return false;
        }

        return $tenantUser->pivot->hasPermissionTo(\App\Constants\TenancyPermissionConstants::PERMISSION_VIEW_ALL_LEADS);
    }

    /**
     * Check if user can manage leads (create, update, delete)
     */
    public function canManageLeads(?Tenant $tenant = null): bool
    {
        $tenant = $tenant ?? filament()->getTenant();
        if (!$tenant) {
            return false;
        }

        if ($this->isTenantAdmin($tenant)) {
            return true;
        }

        // Check if the TenantUser pivot has the permission
        $tenantUser = $this->tenants()->where('tenant_id', $tenant->id)->first();
        if (!$tenantUser) {
            return false;
        }

        // User can manage leads if they have create, update, or delete permissions
        return $tenantUser->pivot->hasPermissionTo(\App\Constants\TenancyPermissionConstants::PERMISSION_CREATE_LEADS) ||
               $tenantUser->pivot->hasPermissionTo(\App\Constants\TenancyPermissionConstants::PERMISSION_UPDATE_LEADS) ||
               $tenantUser->pivot->hasPermissionTo(\App\Constants\TenancyPermissionConstants::PERMISSION_DELETE_LEADS);
    }

    /**
     * Check if user can view all contacts in tenant (admin permission)
     */
    public function canViewAllContacts(?Tenant $tenant = null): bool
    {
        $tenant = $tenant ?? filament()->getTenant();
        if (!$tenant) {
            return false;
        }

        if ($this->isTenantAdmin($tenant)) {
            return true;
        }

        // Check if the TenantUser pivot has the permission
        $tenantUser = $this->tenants()->where('tenant_id', $tenant->id)->first();
        if (!$tenantUser) {
            return false;
        }

        return $tenantUser->pivot->hasPermissionTo(\App\Constants\TenancyPermissionConstants::PERMISSION_VIEW_ALL_CONTACTS);
    }

    /**
     * Check if user can manage configuration (admin only)
     */
    public function canManageConfiguration(?Tenant $tenant = null): bool
    {
        $tenant = $tenant ?? filament()->getTenant();
        if (!$tenant) {
            return false;
        }

        if ($this->isTenantAdmin($tenant)) {
            return true;
        }

        // Check if the TenantUser pivot has the permission
        $tenantUser = $this->tenants()->where('tenant_id', $tenant->id)->first();
        if (!$tenantUser) {
            return false;
        }

        return $tenantUser->pivot->hasPermissionTo(\App\Constants\TenancyPermissionConstants::PERMISSION_MANAGE_CONFIGURATION);
    }

    /**
     * Check if user can view dashboard stats (admin sees all, users see filtered)
     */
    public function canViewDashboardStats(?Tenant $tenant = null): bool
    {
        $tenant = $tenant ?? filament()->getTenant();
        if (!$tenant) {
            return false;
        }

        if ($this->isTenantAdmin($tenant)) {
            return true;
        }

        // Check if the TenantUser pivot has the permission
        $tenantUser = $this->tenants()->where('tenant_id', $tenant->id)->first();
        if (!$tenantUser) {
            return false;
        }

        return $tenantUser->pivot->hasPermissionTo(\App\Constants\TenancyPermissionConstants::PERMISSION_VIEW_DASHBOARD_STATS);
    }

    public function isPhoneNumberVerified()
    {
        return $this->phone_number_verified_at !== null;
    }

    public function canImpersonate()
    {
        return $this->hasPermissionTo('impersonate users') && $this->isAdmin();
    }

    public function isSubscribed(?string $productSlug = null, ?Tenant $tenant = null): bool
    {
        /** @var SubscriptionService $subscriptionService */
        $subscriptionService = app(SubscriptionService::class);

        return $subscriptionService->isUserSubscribed($this, $productSlug, $tenant);
    }

    public function isTrialing(?string $productSlug = null, ?Tenant $tenant = null): bool
    {
        /** @var SubscriptionService $subscriptionService */
        $subscriptionService = app(SubscriptionService::class);

        return $subscriptionService->isUserTrialing($this, $productSlug, $tenant);
    }

    public function hasPurchased(?string $productSlug = null, ?Tenant $tenant = null): bool
    {
        /** @var OrderService $orderService */
        $orderService = app(OrderService::class);

        return $orderService->hasUserOrdered($this, $productSlug, $tenant);
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new QueuedVerifyEmail);
    }

    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class)->using(TenantUser::class)->withPivot('id')->withTimestamps();
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->tenants;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->tenants()->whereKey($tenant)->exists();
    }

    /**
     * Get the avatar URL or generate initials avatar
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF&size=40';
    }

    /**
     * Get user initials
     */
    public function getInitialsAttribute(): string
    {
        $names = explode(' ', $this->name);
        $initials = '';
        
        foreach ($names as $name) {
            if (!empty($name)) {
                $initials .= strtoupper(substr($name, 0, 1));
            }
        }
        
        return substr($initials, 0, 2);
    }
}
