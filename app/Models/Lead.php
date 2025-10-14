<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Lead extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'contact_id',
        'asesor_id',
        'estado',
        'fase_venta_id',
        'curso_id',
        'sede_id',
        'modalidad_id',
        'provincia_id',
        'nombre',
        'apellidos',
        'telefono',
        'email',
        'pais',
        'motivo_nulo_id',
        'origen_id',
        'convocatoria',
        'horario',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'fecha_ganado',
        'fecha_perdido',
        'last_reminder_sent_at',
    ];

    protected $casts = [
        'fecha_ganado' => 'datetime',
        'fecha_perdido' => 'datetime',
        'last_reminder_sent_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            try {
                $tenant = filament()->getTenant();
                if ($tenant) {
                    $builder->where('tenant_id', $tenant->id);
                }
            } catch (\Exception $e) {
                // Durante importaciones u otros contextos, el tenant puede no estar disponible
                // En estos casos, no aplicamos el scope automáticamente
                Log::debug('Lead model - Global scope tenant no aplicado: ' . $e->getMessage());
            }
        });

        // Scope para filtrar por usuario (solo si no puede ver todos los leads)
        // NO se aplica en contexto de API (se maneja en el controlador)
        static::addGlobalScope('user_access', function (Builder $builder) {
            // Skip en contexto de API
            if (request()->is('api/*')) {
                return;
            }
            
            if (Auth::check()) {
                $user = Auth::user();
                // Si no puede ver todos los leads, solo ve los suyos
                if (!$user->canViewAllLeads()) {
                    $builder->where('asesor_id', $user->id);
                }
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

    public function asesor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asesor_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'curso_id');
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class, 'sede_id');
    }

    public function modality(): BelongsTo
    {
        return $this->belongsTo(Modality::class, 'modalidad_id');
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'provincia_id');
    }

    public function salesPhase(): BelongsTo
    {
        return $this->belongsTo(SalesPhase::class, 'fase_venta_id');
    }

    public function nullReason(): BelongsTo
    {
        return $this->belongsTo(NullReason::class, 'motivo_nulo_id');
    }

    public function origin(): BelongsTo
    {
        return $this->belongsTo(Origin::class, 'origen_id');
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(LeadNote::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(LeadEvent::class);
    }

    // Scopes
    public function scopeByEstado(Builder $query, string $estado): Builder
    {
        return $query->where('estado', $estado);
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        // Si es admin, puede ver todos los leads
        if ($user->isAdmin()) {
            return $query;
        }
        
        // Si no es admin, solo puede ver sus leads asignados
        return $query->where('asesor_id', $user->id);
    }
}
