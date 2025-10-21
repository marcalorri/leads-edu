<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * Modelo global de países compartido entre todos los tenants.
 * Catálogo de referencia global de solo lectura.
 */
class Country extends Model
{
    protected $fillable = [
        'nombre',
        'codigo',
        'codigo_iso3',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function provinces(): HasMany
    {
        return $this->hasMany(Province::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('activo', true);
    }
}
