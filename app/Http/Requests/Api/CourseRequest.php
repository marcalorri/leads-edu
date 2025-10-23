<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // La autorización se maneja en el middleware
    }

    public function rules(): array
    {
        $tenant = $this->current_tenant;
        $courseId = $this->route('course');

        return [
            'codigo_curso' => [
                'required',
                'string',
                'max:50',
                Rule::unique('courses', 'codigo_curso')
                    ->where('tenant_id', $tenant->id)
                    ->ignore($courseId),
            ],
            'titulacion' => 'required|string|max:255',
            'area_id' => 'required', // Puede ser ID, código o nombre
            'unidad_negocio_id' => 'required', // Puede ser ID, código o nombre
            'duracion_id' => 'required', // Puede ser ID o nombre
        ];
    }
    
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $tenant = $this->current_tenant;
        
        // Resolver area_id
        if ($this->has('area_id') && !is_numeric($this->area_id)) {
            $area = \App\Models\Area::where('tenant_id', $tenant->id)
                ->where(function ($query) {
                    $query->where('codigo', $this->area_id)
                          ->orWhere('nombre', $this->area_id);
                })
                ->first();
            
            if ($area) {
                $this->merge(['area_id' => $area->id]);
            }
        }
        
        // Resolver unidad_negocio_id
        if ($this->has('unidad_negocio_id') && !is_numeric($this->unidad_negocio_id)) {
            $businessUnit = \App\Models\BusinessUnit::where('tenant_id', $tenant->id)
                ->where(function ($query) {
                    $query->where('codigo', $this->unidad_negocio_id)
                          ->orWhere('nombre', $this->unidad_negocio_id);
                })
                ->first();
            
            if ($businessUnit) {
                $this->merge(['unidad_negocio_id' => $businessUnit->id]);
            }
        }
        
        // Resolver duracion_id
        if ($this->has('duracion_id') && !is_numeric($this->duracion_id)) {
            $duration = \App\Models\Duration::where('tenant_id', $tenant->id)
                ->where('nombre', $this->duracion_id)
                ->first();
            
            if ($duration) {
                $this->merge(['duracion_id' => $duration->id]);
            }
        }
    }
    
    /**
     * Get custom validation rules after field resolution.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $tenant = $this->current_tenant;
            
            // Validar que area_id existe y pertenece al tenant
            if ($this->has('area_id')) {
                $exists = \App\Models\Area::where('tenant_id', $tenant->id)
                    ->where('id', $this->area_id)
                    ->exists();
                
                if (!$exists) {
                    $validator->errors()->add('area_id', __('The selected area does not exist in your organization'));
                }
            }
            
            // Validar que unidad_negocio_id existe y pertenece al tenant
            if ($this->has('unidad_negocio_id')) {
                $exists = \App\Models\BusinessUnit::where('tenant_id', $tenant->id)
                    ->where('id', $this->unidad_negocio_id)
                    ->exists();
                
                if (!$exists) {
                    $validator->errors()->add('unidad_negocio_id', __('The selected business unit does not exist in your organization'));
                }
            }
            
            // Validar que duracion_id existe y pertenece al tenant
            if ($this->has('duracion_id')) {
                $exists = \App\Models\Duration::where('tenant_id', $tenant->id)
                    ->where('id', $this->duracion_id)
                    ->exists();
                
                if (!$exists) {
                    $validator->errors()->add('duracion_id', __('The selected duration does not exist in your organization'));
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'codigo_curso.required' => __('The course code is required'),
            'codigo_curso.unique' => __('This course code already exists in your organization'),
            'titulacion.required' => __('The degree is required'),
            'area_id.required' => __('The area is required'),
            'area_id.exists' => __('The selected area does not exist'),
            'unidad_negocio_id.required' => __('The business unit is required'),
            'unidad_negocio_id.exists' => __('The selected business unit does not exist'),
            'duracion_id.required' => __('The duration is required'),
            'duracion_id.exists' => __('The selected duration does not exist'),
        ];
    }
}
