<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeadStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // La autorización se maneja en el middleware
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $tenant = $this->current_tenant;

        return [
            'nombre' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:150'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('leads')->where(function ($query) use ($tenant) {
                    return $query->where('tenant_id', $tenant->id);
                })
            ],
            'telefono' => ['required', 'string', 'max:20'],
            'pais' => ['nullable', 'string', 'max:100'],
            
            // Relaciones obligatorias
            'curso_id' => [
                'required',
                'integer',
                Rule::exists('courses', 'id')->where(function ($query) use ($tenant) {
                    return $query->where('tenant_id', $tenant->id);
                })
            ],
            'sede_id' => [
                'required',
                'integer',
                Rule::exists('campuses', 'id')->where(function ($query) use ($tenant) {
                    return $query->where('tenant_id', $tenant->id);
                })
            ],
            'modalidad_id' => [
                'required',
                'integer',
                Rule::exists('modalities', 'id')->where(function ($query) use ($tenant) {
                    return $query->where('tenant_id', $tenant->id);
                })
            ],
            'origen_id' => [
                'required',
                'integer',
                Rule::exists('origins', 'id')->where(function ($query) use ($tenant) {
                    return $query->where('tenant_id', $tenant->id);
                })
            ],
            
            // Relaciones opcionales
            'asesor_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) use ($tenant) {
                    return $query->whereHas('tenants', function ($q) use ($tenant) {
                        $q->where('tenant_id', $tenant->id);
                    });
                })
            ],
            'provincia_id' => [
                'nullable',
                'integer',
                Rule::exists('provinces', 'id')->where(function ($query) use ($tenant) {
                    return $query->where('tenant_id', $tenant->id);
                })
            ],
            'fase_venta_id' => [
                'nullable',
                'integer',
                Rule::exists('sales_phases', 'id')->where(function ($query) use ($tenant) {
                    return $query->where('tenant_id', $tenant->id);
                })
            ],
            
            // Campos opcionales
            'estado' => ['nullable', 'string', Rule::in(['abierto', 'ganado', 'perdido'])],
            'convocatoria' => ['nullable', 'string', 'max:100'],
            'horario' => ['nullable', 'string', 'max:100'],
            
            // Campos UTM para tracking
            'utm_source' => ['nullable', 'string', 'max:255'],
            'utm_medium' => ['nullable', 'string', 'max:255'],
            'utm_campaign' => ['nullable', 'string', 'max:255'],
            
            // Motivo nulo (solo si estado es perdido)
            'motivo_nulo_id' => [
                'nullable',
                'integer',
                Rule::exists('null_reasons', 'id')->where(function ($query) use ($tenant) {
                    return $query->where('tenant_id', $tenant->id);
                }),
                Rule::requiredIf(function () {
                    return $this->input('estado') === 'perdido';
                })
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'Ya existe un lead con este email en el tenant.',
            'curso_id.exists' => 'El curso seleccionado no existe o no pertenece a tu organización.',
            'sede_id.exists' => 'La sede seleccionada no existe o no pertenece a tu organización.',
            'modalidad_id.exists' => 'La modalidad seleccionada no existe o no pertenece a tu organización.',
            'origen_id.exists' => 'El origen seleccionado no existe o no pertenece a tu organización.',
            'asesor_id.exists' => 'El asesor seleccionado no existe o no pertenece a tu organización.',
            'provincia_id.exists' => 'La provincia seleccionada no existe o no pertenece a tu organización.',
            'fase_venta_id.exists' => 'La fase de venta seleccionada no existe o no pertenece a tu organización.',
            'motivo_nulo_id.exists' => 'El motivo nulo seleccionado no existe o no pertenece a tu organización.',
            'motivo_nulo_id.required_if' => 'El motivo nulo es obligatorio cuando el estado es "perdido".',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $tenant = $this->current_tenant;
        $data = [];

        // Establecer valores por defecto
        if (!$this->has('estado')) {
            $data['estado'] = 'abierto';
        }

        // Resolver curso_id si se proporciona código o nombre
        if ($this->has('curso_id') && !is_numeric($this->curso_id)) {
            $cursoIdentifier = $this->curso_id;
            
            // Buscar por código exacto
            $course = \App\Models\Course::where('tenant_id', $tenant->id)
                ->where('codigo_curso', $cursoIdentifier)
                ->first();
            
            // Si no se encuentra, buscar por código parcial
            if (!$course) {
                $course = \App\Models\Course::where('tenant_id', $tenant->id)
                    ->where('codigo_curso', 'like', "%{$cursoIdentifier}%")
                    ->first();
            }
            
            // Si no se encuentra, buscar por título
            if (!$course) {
                $course = \App\Models\Course::where('tenant_id', $tenant->id)
                    ->where('titulacion', 'like', "%{$cursoIdentifier}%")
                    ->first();
            }
            
            if ($course) {
                $data['curso_id'] = $course->id;
            }
        }

        // Resolver sede_id si se proporciona nombre
        if ($this->has('sede_id') && !is_numeric($this->sede_id)) {
            $campus = \App\Models\Campus::where('tenant_id', $tenant->id)
                ->where('nombre', 'like', "%{$this->sede_id}%")
                ->first();
            
            if ($campus) {
                $data['sede_id'] = $campus->id;
            }
        }

        // Resolver modalidad_id si se proporciona nombre
        if ($this->has('modalidad_id') && !is_numeric($this->modalidad_id)) {
            $modality = \App\Models\Modality::where('tenant_id', $tenant->id)
                ->where('nombre', 'like', "%{$this->modalidad_id}%")
                ->first();
            
            if ($modality) {
                $data['modalidad_id'] = $modality->id;
            }
        }

        // Resolver provincia_id si se proporciona nombre (con normalización inteligente)
        if ($this->has('provincia_id') && !is_numeric($this->provincia_id)) {
            $normalizer = app(\App\Services\LocationNormalizerService::class);
            // El normalizador trabaja con catálogo global; opcionalmente se puede filtrar por country_id si existe en el tenant
            $province = $normalizer->resolveProvince($this->provincia_id, null);
            
            if ($province) {
                $data['provincia_id'] = $province->id;
            }
            // Si no se encuentra, el log ya se registró en el servicio
        }

        // Resolver asesor_id si se proporciona email o nombre
        if ($this->has('asesor_id') && !is_numeric($this->asesor_id)) {
            // Buscar por email exacto
            $user = \App\Models\User::whereHas('tenants', function($query) use ($tenant) {
                $query->where('tenant_id', $tenant->id);
            })->where('email', $this->asesor_id)->first();
            
            // Si no se encuentra, buscar por nombre
            if (!$user) {
                $user = \App\Models\User::whereHas('tenants', function($query) use ($tenant) {
                    $query->where('tenant_id', $tenant->id);
                })->where('name', 'like', "%{$this->asesor_id}%")->first();
            }
            
            if ($user) {
                $data['asesor_id'] = $user->id;
            }
        }

        // Resolver fase_venta_id si se proporciona nombre
        if ($this->has('fase_venta_id') && !is_numeric($this->fase_venta_id)) {
            $salesPhase = \App\Models\SalesPhase::where('tenant_id', $tenant->id)
                ->where('nombre', 'like', "%{$this->fase_venta_id}%")
                ->first();
            
            if ($salesPhase) {
                $data['fase_venta_id'] = $salesPhase->id;
            }
        }

        // Resolver origen_id si se proporciona nombre
        if ($this->has('origen_id') && !is_numeric($this->origen_id)) {
            $origin = \App\Models\Origin::where('tenant_id', $tenant->id)
                ->where('nombre', 'like', "%{$this->origen_id}%")
                ->first();
            
            if ($origin) {
                $data['origen_id'] = $origin->id;
            }
        }

        // Resolver motivo_nulo_id si se proporciona nombre
        if ($this->has('motivo_nulo_id') && !is_numeric($this->motivo_nulo_id)) {
            $nullReason = \App\Models\NullReason::where('tenant_id', $tenant->id)
                ->where('nombre', 'like', "%{$this->motivo_nulo_id}%")
                ->first();
            
            if ($nullReason) {
                $data['motivo_nulo_id'] = $nullReason->id;
            }
        }

        if (!empty($data)) {
            $this->merge($data);
        }
    }
}
