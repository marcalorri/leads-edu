<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Origin extends Model
{
    protected $fillable = [
        'tenant_id',
        'nombre',
        'descripcion',
        'tipo',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    protected static function booted()
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            $tenant = filament()->getTenant();
            if ($tenant) {
                $builder->where('tenant_id', $tenant->id);
            }
        });

        static::creating(function ($model) {
            if (!$model->tenant_id && filament()->getTenant()) {
                $model->tenant_id = filament()->getTenant()->id;
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'origen_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    public function scopeByTipo(Builder $query, string $tipo): Builder
    {
        return $query->where('tipo', $tipo);
    }
}
