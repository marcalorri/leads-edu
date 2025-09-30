<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\CalendarEvent;

class LeadEvent extends Model implements Eventable
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

    // Implementación de la interfaz Eventable
    public function toCalendarEvent(): CalendarEvent
    {
        return CalendarEvent::make($this)
            ->title($this->titulo . ' → ' . ($this->lead?->nombre ?? 'Lead'))
            ->start($this->fecha_programada)
            ->end($this->getEndDate())
            ->backgroundColor($this->getEventColor())
            ->textColor('#ffffff')
            ->extendedProps([
                'lead_id' => $this->lead_id,
                'lead_nombre' => $this->lead?->nombre ?? 'Lead no encontrado',
                'lead_telefono' => $this->lead?->telefono ?? '',
                'lead_email' => $this->lead?->email ?? '',
                'estado' => $this->estado,
                'descripcion' => $this->descripcion,
                'usuario_nombre' => $this->usuario?->name ?? 'Usuario no encontrado',
                'duracion' => $this->duracion_estimada ? $this->duracion_estimada . ' min' : '60 min',
                'curso' => $this->lead?->curso?->titulacion ?? 'Sin curso',
                'tooltip' => $this->getEventTooltip(),
                'event_id' => $this->id, // Para identificar el evento
            ]);
    }

    // Métodos auxiliares para el calendario
    private function getEndDate(): \Carbon\Carbon
    {
        if ($this->fecha_completada) {
            return $this->fecha_completada;
        }

        if ($this->duracion_estimada) {
            return $this->fecha_programada->addMinutes($this->duracion_estimada);
        }

        // Por defecto, 1 hora de duración
        return $this->fecha_programada->addHour();
    }

    private function getEventColor(): string
    {
        return match($this->estado) {
            'pendiente' => '#f59e0b', // Amber - Pendiente
            'completada' => '#10b981', // Green - Completada
            'cancelada' => '#ef4444',  // Red - Cancelada
            default => '#6b7280'       // Gray - Por defecto
        };
    }

    // Método para obtener el color del texto basado en el estado
    public function getEventTextColor(): string
    {
        return '#ffffff'; // Texto blanco para todos los estados
    }

    // Método para obtener información adicional del evento
    public function getEventTooltip(): string
    {
        $tooltip = "📋 {$this->titulo}\n\n";
        $tooltip .= "👤 Lead: {$this->lead?->nombre}\n";
        $tooltip .= "📞 Teléfono: {$this->lead?->telefono}\n";
        $tooltip .= "📧 Email: {$this->lead?->email}\n";
        $tooltip .= "🎓 Curso: {$this->lead?->curso?->titulacion}\n\n";
        $tooltip .= "👨‍💼 Asignado a: {$this->usuario?->name}\n";
        $tooltip .= "📅 Fecha: {$this->fecha_programada->format('d/m/Y H:i')}\n";
        $tooltip .= "⏱️ Duración: " . ($this->duracion_estimada ? $this->duracion_estimada . ' min' : '60 min') . "\n";
        $tooltip .= "🔄 Estado: " . ucfirst($this->estado) . "\n";
        
        if ($this->descripcion) {
            $tooltip .= "\n📝 Descripción:\n{$this->descripcion}";
        }

        return $tooltip;
    }
}
