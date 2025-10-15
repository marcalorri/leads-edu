<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Request genérico para catálogos simples (Modality, Province, SalesPhase, Origin)
 */
class CatalogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tenant = $this->current_tenant;
        $table = $this->getTableName();
        $id = $this->route($this->getRouteName());

        $rules = [
            'nombre' => 'required|string|max:100',
            'activo' => 'boolean',
        ];

        // Agregar regla de código único si el catálogo lo requiere
        if ($this->requiresCode()) {
            $rules['codigo'] = [
                'required',
                'string',
                'max:20',
                Rule::unique($table, 'codigo')
                    ->where('tenant_id', $tenant->id)
                    ->ignore($id),
            ];
        }

        // Reglas específicas por tipo de catálogo
        $specificRules = $this->getSpecificRules();
        
        return array_merge($rules, $specificRules);
    }

    protected function getTableName(): string
    {
        return match($this->route()->getName()) {
            'api.v1.catalogs.modalities.store', 'api.v1.catalogs.modalities.update' => 'modalities',
            'api.v1.catalogs.provinces.store', 'api.v1.catalogs.provinces.update' => 'provinces',
            'api.v1.catalogs.sales-phases.store', 'api.v1.catalogs.sales-phases.update' => 'sales_phases',
            'api.v1.catalogs.origins.store', 'api.v1.catalogs.origins.update' => 'origins',
            default => 'unknown',
        };
    }

    protected function getRouteName(): string
    {
        return match($this->route()->getName()) {
            'api.v1.catalogs.modalities.store', 'api.v1.catalogs.modalities.update' => 'modality',
            'api.v1.catalogs.provinces.store', 'api.v1.catalogs.provinces.update' => 'province',
            'api.v1.catalogs.sales-phases.store', 'api.v1.catalogs.sales-phases.update' => 'salesPhase',
            'api.v1.catalogs.origins.store', 'api.v1.catalogs.origins.update' => 'origin',
            default => 'id',
        };
    }

    protected function requiresCode(): bool
    {
        $table = $this->getTableName();
        return in_array($table, ['modalities', 'provinces']);
    }

    protected function getSpecificRules(): array
    {
        $table = $this->getTableName();

        return match($table) {
            'modalities' => [
                'descripcion' => 'nullable|string',
                'requiere_sede' => 'boolean',
            ],
            'provinces' => [
                'codigo_ine' => 'nullable|string|max:5',
                'comunidad_autonoma' => 'nullable|string|max:100',
            ],
            'sales_phases' => [
                'descripcion' => 'nullable|string',
                'orden' => 'required|integer|min:0',
                'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            ],
            'origins' => [
                'descripcion' => 'nullable|string',
                'tipo' => 'required|in:web,telefono,email,redes_sociales,referido,evento,publicidad,otro',
            ],
            default => [],
        };
    }
}
