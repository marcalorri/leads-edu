<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CampusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tenant = $this->current_tenant;
        $campusId = $this->route('campus');

        return [
            'codigo' => [
                'required',
                'string',
                'max:20',
                Rule::unique('campuses', 'codigo')
                    ->where('tenant_id', $tenant->id)
                    ->ignore($campusId),
            ],
            'nombre' => 'required|string|max:100',
            'direccion' => 'nullable|string',
            'ciudad' => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|string|max:10',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'responsable' => 'nullable|string|max:255',
            'activo' => 'boolean',
        ];
    }
}
