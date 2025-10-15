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
            'area_id' => 'required|exists:areas,id',
            'unidad_negocio_id' => 'required|exists:business_units,id',
            'duracion_id' => 'required|exists:durations,id',
        ];
    }

    public function messages(): array
    {
        return [
            'codigo_curso.required' => 'El código del curso es obligatorio',
            'codigo_curso.unique' => 'Este código de curso ya existe en tu organización',
            'titulacion.required' => 'La titulación es obligatoria',
            'area_id.required' => 'El área es obligatoria',
            'area_id.exists' => 'El área seleccionada no existe',
            'unidad_negocio_id.required' => 'La unidad de negocio es obligatoria',
            'unidad_negocio_id.exists' => 'La unidad de negocio seleccionada no existe',
            'duracion_id.required' => 'La duración es obligatoria',
            'duracion_id.exists' => 'La duración seleccionada no existe',
        ];
    }
}
