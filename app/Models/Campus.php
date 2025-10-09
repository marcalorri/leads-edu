<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Campus extends Model
{
    protected $fillable = [
        'tenant_id',
        'nombre',
        'codigo',
        'direccion',
        'ciudad',
        'codigo_postal',
        'telefono',
        'email',
        'responsable',
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
        return $this->hasMany(Lead::class, 'sede_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('activo', true);
    }
}
