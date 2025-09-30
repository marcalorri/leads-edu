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
            'estado' => ['nullable', 'string', Rule::in(['nuevo', 'contactado', 'interesado', 'matriculado', 'perdido'])],
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
        // Establecer valores por defecto
        if (!$this->has('estado')) {
            $this->merge(['estado' => 'nuevo']);
        }
    }
}
