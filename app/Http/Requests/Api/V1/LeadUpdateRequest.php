<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeadUpdateRequest extends FormRequest
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
        $leadId = $this->route('lead'); // Obtener ID del lead desde la ruta

        return [
            'nombre' => ['sometimes', 'required', 'string', 'max:100'],
            'apellidos' => ['sometimes', 'required', 'string', 'max:150'],
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
            ],
            'telefono' => ['sometimes', 'required', 'string', 'max:20'],
            'pais' => ['sometimes', 'nullable', 'string', 'max:100'],
            
            // Relaciones
            'curso_id' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('courses', 'id')->where(function ($query) use ($tenant) {
                    return $query->where('tenant_id', $tenant->id);
                })
            ],
            'sede_id' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('campuses', 'id')->where(function ($query) use ($tenant) {
                    return $query->where('tenant_id', $tenant->id);
                })
            ],
            'modalidad_id' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('modalities', 'id')->where(function ($query) use ($tenant) {
                    return $query->where('tenant_id', $tenant->id);
                })
            ],
            'origen_id' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('origins', 'id')->where(function ($query) use ($tenant) {
                    return $query->where('tenant_id', $tenant->id);
                })
            ],
            
            // Relaciones opcionales
            'asesor_id' => [
                'sometimes',
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) use ($tenant) {
                    return $query->whereHas('tenants', function ($q) use ($tenant) {
                        $q->where('tenant_id', $tenant->id);
                    });
                })
            ],
            'provincia_id' => [
                'sometimes',
                'nullable',
                'integer',
                Rule::exists('provinces', 'id')->where(function ($query) use ($tenant) {
                    return $query->where('tenant_id', $tenant->id);
                })
            ],
            'fase_venta_id' => [
                'sometimes',
                'nullable',
                'integer',
                Rule::exists('sales_phases', 'id')->where(function ($query) use ($tenant) {
                    return $query->where('tenant_id', $tenant->id);
                })
            ],
            
            // Campos opcionales
            'estado' => ['sometimes', 'required', 'string', Rule::in(['abierto', 'ganado', 'perdido'])],
            'convocatoria' => ['sometimes', 'nullable', 'string', 'max:100'],
            'horario' => ['sometimes', 'nullable', 'string', 'max:100'],
            
            // Campos UTM para tracking
            'utm_source' => ['sometimes', 'nullable', 'string', 'max:255'],
            'utm_medium' => ['sometimes', 'nullable', 'string', 'max:255'],
            'utm_campaign' => ['sometimes', 'nullable', 'string', 'max:255'],
            
            // Motivo nulo (solo si estado es perdido)
            'motivo_nulo_id' => [
                'sometimes',
                'nullable',
                'integer',
                Rule::exists('null_reasons', 'id')->where(function ($query) use ($tenant) {
                    return $query->where('tenant_id', $tenant->id);
                }),
                Rule::requiredIf(function () {
                    return $this->input('estado') === 'perdido';
                })
            ],
            
            // Fechas especiales (solo para admins)
            'fecha_ganado' => ['sometimes', 'nullable', 'date'],
            'fecha_perdido' => ['sometimes', 'nullable', 'date'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'Ya existe otro lead con este email en el tenant.',
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
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validación personalizada: si estado cambia a 'perdido', requiere motivo_nulo_id
            if ($this->input('estado') === 'perdido' && !$this->input('motivo_nulo_id')) {
                $validator->errors()->add('motivo_nulo_id', 'El motivo nulo es obligatorio cuando el estado es "perdido".');
            }
            
            // Si estado cambia a 'ganado', establecer fecha_ganado automáticamente
            if ($this->input('estado') === 'ganado' && !$this->has('fecha_ganado')) {
                $this->merge(['fecha_ganado' => now()]);
            }
            
            // Si estado cambia a 'perdido', establecer fecha_perdido automáticamente
            if ($this->input('estado') === 'perdido' && !$this->has('fecha_perdido')) {
                $this->merge(['fecha_perdido' => now()]);
            }
        });
    }
}
