<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class ApiToken extends SanctumPersonalAccessToken
{
    protected $table = 'personal_access_tokens';

    protected $fillable = [
        'name',
        'token',
        'abilities',
        'tenant_id',
        'expires_at',
        'description',
    ];

    protected $casts = [
        'abilities' => 'json',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el tenant
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Verificar si el token ha expirado
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Verificar si el token está activo
     */
    public function isActive(): bool
    {
        return !$this->isExpired();
    }

    /**
     * Scopes disponibles para la API
     */
    public static function getAvailableScopes(): array
    {
        return [
            'leads:read' => 'Ver leads del tenant',
            'leads:write' => 'Crear y modificar leads',
            'leads:delete' => 'Eliminar leads',
            'leads:admin' => 'Acceso completo a leads (incluye gestión)',
        ];
    }

    /**
     * Obtener descripción de los scopes del token
     */
    public function getScopeDescriptions(): array
    {
        $availableScopes = self::getAvailableScopes();
        $descriptions = [];

        foreach ($this->abilities as $scope) {
            $descriptions[$scope] = $availableScopes[$scope] ?? $scope;
        }

        return $descriptions;
    }

    /**
     * Scope para filtrar por tenant
     */
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Scope para tokens activos (no expirados)
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }
}
