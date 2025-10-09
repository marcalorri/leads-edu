<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TenantSetting extends Model
{
    protected $fillable = [
        'tenant_id',
        'configuracion',
    ];

    protected $casts = [
        'configuracion' => 'array',
    ];

    protected static function booted()
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            $tenant = filament()->getTenant();
            if ($tenant) {
                $builder->where('tenant_id', $tenant->id);
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    // Helper methods para configuración
    public function getSetting(string $key, $default = null)
    {
        return data_get($this->configuracion, $key, $default);
    }

    public function setSetting(string $key, $value): void
    {
        $config = $this->configuracion ?? [];
        data_set($config, $key, $value);
        $this->configuracion = $config;
        $this->save();
    }
}
