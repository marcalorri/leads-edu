<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class LeadEvent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'lead_id',
        'usuario_id',
        'titulo',
        'descripcion',
        'tipo',
        'estado',
        'prioridad',
        'fecha_programada',
        'fecha_completada',
        'duracion_estimada',
        'resultado',
        'requiere_recordatorio',
        'minutos_recordatorio',
    ];

    protected $casts = [
        'fecha_programada' => 'datetime',
        'fecha_completada' => 'datetime',
        'requiere_recordatorio' => 'boolean',
        'duracion_estimada' => 'integer',
        'minutos_recordatorio' => 'integer',
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
    public function scopeByEstado(Builder $query, string $estado): Builder
    {
        return $query->where('estado', $estado);
    }

    public function scopeByPrioridad(Builder $query, string $prioridad): Builder
    {
        return $query->where('prioridad', $prioridad);
    }

    public function scopePendientes(Builder $query): Builder
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeVencidos(Builder $query): Builder
    {
        return $query->where('fecha_programada', '<', now())
                    ->where('estado', 'pendiente');
    }
}
