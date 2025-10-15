<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            
            // Información personal
            'nombre' => $this->nombre,
            'apellidos' => $this->apellidos,
            'nombre_completo' => $this->nombre . ' ' . $this->apellidos,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'pais' => $this->pais,
            
            // Estado y proceso
            'estado' => $this->estado,
            'estado_label' => $this->getEstadoLabel(),
            
            // Relaciones principales
            'curso' => $this->whenLoaded('course', function () {
                return [
                    'id' => $this->course->id,
                    'codigo_curso' => $this->course->codigo_curso,
                    'titulacion' => $this->course->titulacion,
                ];
            }),
            
            'asesor' => $this->whenLoaded('asesor', function () {
                return [
                    'id' => $this->asesor->id,
                    'name' => $this->asesor->name,
                    'email' => $this->asesor->email,
                ];
            }),
            
            'sede' => $this->whenLoaded('campus', function () {
                return [
                    'id' => $this->campus->id,
                    'nombre' => $this->campus->nombre,
                    'codigo' => $this->campus->codigo,
                ];
            }),
            
            'modalidad' => $this->whenLoaded('modality', function () {
                return [
                    'id' => $this->modality->id,
                    'nombre' => $this->modality->nombre,
                    'requiere_sede' => $this->modality->requiere_sede,
                ];
            }),
            
            'provincia' => $this->whenLoaded('province', function () {
                return [
                    'id' => $this->province->id,
                    'nombre' => $this->province->nombre,
                    'codigo' => $this->province->codigo,
                ];
            }),
            
            'fase_venta' => $this->whenLoaded('salesPhase', function () {
                return [
                    'id' => $this->salesPhase->id,
                    'nombre' => $this->salesPhase->nombre,
                    'orden' => $this->salesPhase->orden,
                    'color' => $this->salesPhase->color,
                ];
            }),
            
            'origen' => $this->whenLoaded('origin', function () {
                return [
                    'id' => $this->origin->id,
                    'nombre' => $this->origin->nombre,
                    'tipo' => $this->origin->tipo,
                ];
            }),
            
            'motivo_nulo' => $this->whenLoaded('nullReason', function () {
                return $this->nullReason ? [
                    'id' => $this->nullReason->id,
                    'nombre' => $this->nullReason->nombre,
                ] : null;
            }),
            
            // Información de contacto detallada
            'contacto' => $this->whenLoaded('contact', function () {
                return $this->contact ? [
                    'id' => $this->contact->id,
                    'asesor_id' => $this->contact->asesor_id,
                    'nombre_completo' => $this->contact->nombre_completo,
                    'telefono_principal' => $this->contact->telefono_principal,
                    'telefono_secundario' => $this->contact->telefono_secundario,
                    'email_principal' => $this->contact->email_principal,
                    'email_secundario' => $this->contact->email_secundario,
                    'direccion' => $this->contact->direccion,
                    'ciudad' => $this->contact->ciudad,
                    'codigo_postal' => $this->contact->codigo_postal,
                    'provincia_id' => $this->contact->provincia_id,
                    'fecha_nacimiento' => $this->contact->fecha_nacimiento?->toDateString(),
                    'dni_nie' => $this->contact->dni_nie,
                    'profesion' => $this->contact->profesion,
                    'empresa' => $this->contact->empresa,
                    'notas_contacto' => $this->contact->notas_contacto,
                    'preferencia_comunicacion' => $this->contact->preferencia_comunicacion,
                ] : null;
            }),
            
            // Campos adicionales
            'convocatoria' => $this->convocatoria,
            'horario' => $this->horario,
            
            // Tracking UTM
            'utm' => [
                'source' => $this->utm_source,
                'medium' => $this->utm_medium,
                'campaign' => $this->utm_campaign,
            ],
            
            // Fechas importantes
            'fecha_ganado' => $this->fecha_ganado?->toISOString(),
            'fecha_perdido' => $this->fecha_perdido?->toISOString(),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            
            // Métricas calculadas
            'dias_desde_creacion' => (int) $this->created_at->diffInDays(now(), false),
            'tiempo_en_proceso' => $this->getTiempoEnProceso(),
        ];
    }
    
    /**
     * Get additional data that should be returned with the resource array.
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'tenant' => $request->current_tenant->name,
                'api_version' => 'v1',
                'timestamp' => now()->toISOString(),
            ],
        ];
    }
    
    /**
     * Get human readable estado label.
     */
    private function getEstadoLabel(): string
    {
        return match($this->estado) {
            'nuevo' => __('New'),
            'contactado' => __('Contacted'),
            'interesado' => __('Interested'),
            'matriculado' => __('Enrolled'),
            'perdido' => __('Lost'),
            default => ucfirst($this->estado),
        };
    }
    
    /**
     * Calculate time in process.
     */
    private function getTiempoEnProceso(): string
    {
        $fechaFin = $this->fecha_ganado ?? $this->fecha_perdido ?? now();
        $dias = (int) $this->created_at->diffInDays($fechaFin, false);
        
        if ($dias == 0) {
            return __('Less than 1 day');
        } elseif ($dias == 1) {
            return __('1 day');
        } elseif ($dias < 7) {
            return __(':count days', ['count' => $dias]);
        } elseif ($dias < 30) {
            $semanas = floor($dias / 7);
            return $semanas == 1 ? __('1 week') : __(':count weeks', ['count' => $semanas]);
        } else {
            $meses = floor($dias / 30);
            return $meses == 1 ? __('1 month') : __(':count months', ['count' => $meses]);
        }
    }
}
