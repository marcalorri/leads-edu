<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class BusinessUnit extends Model
{
    protected $fillable = [
        'tenant_id',
        'nombre',
        'descripcion',
        'codigo',
        'responsable',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    protected static function booted()
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (Auth::check() && Auth::user()->currentTenant) {
                $builder->where('tenant_id', Auth::user()->currentTenant->id);
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

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'unidad_negocio_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('activo', true);
    }
}
