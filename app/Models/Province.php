<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * Modelo global de provincias/estados compartido entre todos los tenants.
 * No tiene tenant_id - es un catálogo de referencia global de solo lectura.
 */
class Province extends Model
{
    protected $fillable = [
        'country_id',
        'nombre',
        'codigo',
        'codigo_ine',
        'comunidad_autonoma',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'provincia_id');
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'provincia_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('activo', true);
    }
}
