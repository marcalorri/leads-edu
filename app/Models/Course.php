<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'codigo_curso',
        'titulacion',
        'area_id',
        'unidad_negocio_id',
        'duracion_id',
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

    // Relaciones
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class, 'unidad_negocio_id');
    }

    public function duration(): BelongsTo
    {
        return $this->belongsTo(Duration::class, 'duracion_id');
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'curso_id');
    }
}
