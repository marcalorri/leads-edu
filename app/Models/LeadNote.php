<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class LeadNote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'lead_id',
        'usuario_id',
        'titulo',
        'contenido',
        'tipo',
        'es_importante',
        'fecha_seguimiento',
    ];

    protected $casts = [
        'es_importante' => 'boolean',
        'fecha_seguimiento' => 'datetime',
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

    // Relaciones
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Scopes
    public function scopeImportant(Builder $query): Builder
    {
        return $query->where('es_importante', true);
    }

    public function scopeByTipo(Builder $query, string $tipo): Builder
    {
        return $query->where('tipo', $tipo);
    }
}
