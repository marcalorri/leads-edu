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
