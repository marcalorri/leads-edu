<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Campus;
use App\Models\Modality;
use App\Models\Province;
use App\Models\SalesPhase;
use App\Models\Origin;
use App\Http\Requests\Api\CourseRequest;
use App\Http\Requests\Api\CampusRequest;
use App\Http\Requests\Api\CatalogRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CatalogApiController extends Controller
{
    /**
     * Get all courses for the current tenant.
     */
    public function courses(Request $request): JsonResponse
    {
        $tenant = $request->current_tenant;
        
        $courses = Course::where('tenant_id', $tenant->id)
            ->with(['area', 'businessUnit', 'duration'])
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'codigo_curso' => $course->codigo_curso,
                    'titulacion' => $course->titulacion,
                    'area' => $course->area ? [
                        'id' => $course->area->id,
                        'nombre' => $course->area->nombre,
                        'codigo' => $course->area->codigo,
                    ] : null,
                    'unidad_negocio' => $course->businessUnit ? [
                        'id' => $course->businessUnit->id,
                        'nombre' => $course->businessUnit->nombre,
                        'codigo' => $course->businessUnit->codigo,
                    ] : null,
                    'duracion' => $course->duration ? [
                        'id' => $course->duration->id,
                        'nombre' => $course->duration->nombre,
                    ] : null,
                ];
            });

        return response()->json([
            'data' => $courses,
            'meta' => [
                'total' => $courses->count(),
            ],
        ]);
    }

    /**
     * Get all areas for the current tenant.
     */
    public function areas(Request $request): JsonResponse
    {
        $tenant = $request->current_tenant;
        
        $areas = \App\Models\Area::where('tenant_id', $tenant->id)
            ->where('activo', true)
            ->get()
            ->map(function ($area) {
                return [
                    'id' => $area->id,
                    'codigo' => $area->codigo,
                    'nombre' => $area->nombre,
                    'descripcion' => $area->descripcion,
                ];
            });

        return response()->json([
            'data' => $areas,
            'meta' => [
                'total' => $areas->count(),
            ],
        ]);
    }

    /**
     * Get all business units for the current tenant.
     */
    public function businessUnits(Request $request): JsonResponse
    {
        $tenant = $request->current_tenant;
        
        $businessUnits = \App\Models\BusinessUnit::where('tenant_id', $tenant->id)
            ->where('activo', true)
            ->get()
            ->map(function ($unit) {
                return [
                    'id' => $unit->id,
                    'codigo' => $unit->codigo,
                    'nombre' => $unit->nombre,
                    'descripcion' => $unit->descripcion,
                    'responsable' => $unit->responsable,
                ];
            });

        return response()->json([
            'data' => $businessUnits,
            'meta' => [
                'total' => $businessUnits->count(),
            ],
        ]);
    }

    /**
     * Get all durations for the current tenant.
     */
    public function durations(Request $request): JsonResponse
    {
        $tenant = $request->current_tenant;
        
        $durations = \App\Models\Duration::where('tenant_id', $tenant->id)
            ->where('activo', true)
            ->get()
            ->map(function ($duration) {
                return [
                    'id' => $duration->id,
                    'nombre' => $duration->nombre,
                    'descripcion' => $duration->descripcion,
                    'horas_totales' => $duration->horas_totales,
                    'tipo' => $duration->tipo,
                    'valor_numerico' => $duration->valor_numerico,
                ];
            });

        return response()->json([
            'data' => $durations,
            'meta' => [
                'total' => $durations->count(),
            ],
        ]);
    }

    /**
     * Get all asesores (users) for the current tenant.
     */
    public function asesores(Request $request): JsonResponse
    {
        $tenant = $request->current_tenant;
        
        $asesores = $tenant->users()
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            });

        return response()->json([
            'data' => $asesores,
            'meta' => [
                'total' => $asesores->count(),
            ],
        ]);
    }

    /**
     * Get all campuses for the current tenant.
     */
    public function campuses(Request $request): JsonResponse
    {
        $tenant = $request->current_tenant;
        
        $campuses = Campus::where('tenant_id', $tenant->id)
            ->where('activo', true)
            ->get()
            ->map(function ($campus) {
                return [
                    'id' => $campus->id,
                    'codigo' => $campus->codigo,
                    'nombre' => $campus->nombre,
                    'ciudad' => $campus->ciudad,
                    'direccion' => $campus->direccion,
                ];
            });

        return response()->json([
            'data' => $campuses,
            'meta' => [
                'total' => $campuses->count(),
            ],
        ]);
    }

    /**
     * Get all modalities for the current tenant.
     */
    public function modalities(Request $request): JsonResponse
    {
        $tenant = $request->current_tenant;
        
        $modalities = Modality::where('tenant_id', $tenant->id)
            ->where('activo', true)
            ->get()
            ->map(function ($modality) {
                return [
                    'id' => $modality->id,
                    'codigo' => $modality->codigo,
                    'nombre' => $modality->nombre,
                    'requiere_sede' => $modality->requiere_sede,
                ];
            });

        return response()->json([
            'data' => $modalities,
            'meta' => [
                'total' => $modalities->count(),
            ],
        ]);
    }

    /**
     * Get all provinces for the current tenant.
     */
    public function provinces(Request $request): JsonResponse
    {
        $tenant = $request->current_tenant;
        
        $provinces = Province::where('tenant_id', $tenant->id)
            ->where('activo', true)
            ->get()
            ->map(function ($province) {
                return [
                    'id' => $province->id,
                    'codigo' => $province->codigo,
                    'nombre' => $province->nombre,
                    'comunidad_autonoma' => $province->comunidad_autonoma,
                ];
            });

        return response()->json([
            'data' => $provinces,
            'meta' => [
                'total' => $provinces->count(),
            ],
        ]);
    }

    /**
     * Get all sales phases for the current tenant.
     */
    public function salesPhases(Request $request): JsonResponse
    {
        $tenant = $request->current_tenant;
        
        $phases = SalesPhase::where('tenant_id', $tenant->id)
            ->where('activo', true)
            ->orderBy('orden')
            ->get()
            ->map(function ($phase) {
                return [
                    'id' => $phase->id,
                    'nombre' => $phase->nombre,
                    'orden' => $phase->orden,
                    'color' => $phase->color,
                ];
            });

        return response()->json([
            'data' => $phases,
            'meta' => [
                'total' => $phases->count(),
            ],
        ]);
    }

    /**
     * Get all origins for the current tenant.
     */
    public function origins(Request $request): JsonResponse
    {
        $tenant = $request->current_tenant;
        
        $origins = Origin::where('tenant_id', $tenant->id)
            ->where('activo', true)
            ->get()
            ->map(function ($origin) {
                return [
                    'id' => $origin->id,
                    'nombre' => $origin->nombre,
                    'tipo' => $origin->tipo,
                ];
            });

        return response()->json([
            'data' => $origins,
            'meta' => [
                'total' => $origins->count(),
            ],
        ]);
    }

    /**
     * Get available estados for leads.
     */
    public function estados(Request $request): JsonResponse
    {
        $estados = [
            ['value' => 'abierto', 'label' => __('Open')],
            ['value' => 'ganado', 'label' => __('Won')],
            ['value' => 'perdido', 'label' => __('Lost')],
        ];

        return response()->json([
            'data' => $estados,
            'meta' => [
                'total' => count($estados),
            ],
        ]);
    }

    // ========================================
    // MÉTODOS DE ESCRITURA (CREATE/UPDATE)
    // ========================================

    /**
     * Create a new course.
     */
    public function storeCourse(CourseRequest $request): JsonResponse
    {
        $tenant = $request->current_tenant;

        $course = Course::create([
            'tenant_id' => $tenant->id,
            'codigo_curso' => $request->codigo_curso,
            'titulacion' => $request->titulacion,
            'area_id' => $request->area_id,
            'unidad_negocio_id' => $request->unidad_negocio_id,
            'duracion_id' => $request->duracion_id,
        ]);

        $course->load(['area', 'businessUnit', 'duration']);

        return response()->json([
            'data' => [
                'id' => $course->id,
                'codigo_curso' => $course->codigo_curso,
                'titulacion' => $course->titulacion,
                'area' => $course->area ? [
                    'id' => $course->area->id,
                    'nombre' => $course->area->nombre,
                    'codigo' => $course->area->codigo,
                ] : null,
                'unidad_negocio' => $course->businessUnit ? [
                    'id' => $course->businessUnit->id,
                    'nombre' => $course->businessUnit->nombre,
                    'codigo' => $course->businessUnit->codigo,
                ] : null,
                'duracion' => $course->duration ? [
                    'id' => $course->duration->id,
                    'nombre' => $course->duration->nombre,
                ] : null,
            ],
            'message' => __('Course created successfully'),
        ], 201);
    }

    /**
     * Update an existing course.
     */
    public function updateCourse(CourseRequest $request, int $id): JsonResponse
    {
        $tenant = $request->current_tenant;

        $course = Course::where('tenant_id', $tenant->id)->findOrFail($id);

        $course->update([
            'codigo_curso' => $request->codigo_curso,
            'titulacion' => $request->titulacion,
            'area_id' => $request->area_id,
            'unidad_negocio_id' => $request->unidad_negocio_id,
            'duracion_id' => $request->duracion_id,
        ]);

        $course->load(['area', 'businessUnit', 'duration']);

        return response()->json([
            'data' => [
                'id' => $course->id,
                'codigo_curso' => $course->codigo_curso,
                'titulacion' => $course->titulacion,
                'area' => $course->area ? [
                    'id' => $course->area->id,
                    'nombre' => $course->area->nombre,
                    'codigo' => $course->area->codigo,
                ] : null,
                'unidad_negocio' => $course->businessUnit ? [
                    'id' => $course->businessUnit->id,
                    'nombre' => $course->businessUnit->nombre,
                    'codigo' => $course->businessUnit->codigo,
                ] : null,
                'duracion' => $course->duration ? [
                    'id' => $course->duration->id,
                    'nombre' => $course->duration->nombre,
                ] : null,
            ],
            'message' => __('Course updated successfully'),
        ]);
    }

    /**
     * Create a new campus.
     */
    public function storeCampus(CampusRequest $request): JsonResponse
    {
        $tenant = $request->current_tenant;

        $campus = Campus::create(array_merge(
            $request->validated(),
            ['tenant_id' => $tenant->id]
        ));

        return response()->json([
            'data' => [
                'id' => $campus->id,
                'codigo' => $campus->codigo,
                'nombre' => $campus->nombre,
                'ciudad' => $campus->ciudad,
                'direccion' => $campus->direccion,
            ],
            'message' => __('Campus created successfully'),
        ], 201);
    }

    /**
     * Update an existing campus.
     */
    public function updateCampus(CampusRequest $request, int $id): JsonResponse
    {
        $tenant = $request->current_tenant;

        $campus = Campus::where('tenant_id', $tenant->id)->findOrFail($id);
        $campus->update($request->validated());

        return response()->json([
            'data' => [
                'id' => $campus->id,
                'codigo' => $campus->codigo,
                'nombre' => $campus->nombre,
                'ciudad' => $campus->ciudad,
                'direccion' => $campus->direccion,
            ],
            'message' => __('Campus updated successfully'),
        ]);
    }

    /**
     * Create a new modality.
     */
    public function storeModality(CatalogRequest $request): JsonResponse
    {
        $tenant = $request->current_tenant;

        $modality = Modality::create(array_merge(
            $request->validated(),
            ['tenant_id' => $tenant->id]
        ));

        return response()->json([
            'data' => [
                'id' => $modality->id,
                'codigo' => $modality->codigo,
                'nombre' => $modality->nombre,
                'requiere_sede' => $modality->requiere_sede,
            ],
            'message' => __('Modality created successfully'),
        ], 201);
    }

    /**
     * Update an existing modality.
     */
    public function updateModality(CatalogRequest $request, int $id): JsonResponse
    {
        $tenant = $request->current_tenant;

        $modality = Modality::where('tenant_id', $tenant->id)->findOrFail($id);
        $modality->update($request->validated());

        return response()->json([
            'data' => [
                'id' => $modality->id,
                'codigo' => $modality->codigo,
                'nombre' => $modality->nombre,
                'requiere_sede' => $modality->requiere_sede,
            ],
            'message' => __('Modality updated successfully'),
        ]);
    }

    /**
     * Create a new province.
     */
    public function storeProvince(CatalogRequest $request): JsonResponse
    {
        $tenant = $request->current_tenant;

        $province = Province::create(array_merge(
            $request->validated(),
            ['tenant_id' => $tenant->id]
        ));

        return response()->json([
            'data' => [
                'id' => $province->id,
                'codigo' => $province->codigo,
                'nombre' => $province->nombre,
                'comunidad_autonoma' => $province->comunidad_autonoma,
            ],
            'message' => __('Province created successfully'),
        ], 201);
    }

    /**
     * Update an existing province.
     */
    public function updateProvince(CatalogRequest $request, int $id): JsonResponse
    {
        $tenant = $request->current_tenant;

        $province = Province::where('tenant_id', $tenant->id)->findOrFail($id);
        $province->update($request->validated());

        return response()->json([
            'data' => [
                'id' => $province->id,
                'codigo' => $province->codigo,
                'nombre' => $province->nombre,
                'comunidad_autonoma' => $province->comunidad_autonoma,
            ],
            'message' => __('Province updated successfully'),
        ]);
    }

    /**
     * Create a new sales phase.
     */
    public function storeSalesPhase(CatalogRequest $request): JsonResponse
    {
        $tenant = $request->current_tenant;

        $phase = SalesPhase::create(array_merge(
            $request->validated(),
            ['tenant_id' => $tenant->id]
        ));

        return response()->json([
            'data' => [
                'id' => $phase->id,
                'nombre' => $phase->nombre,
                'orden' => $phase->orden,
                'color' => $phase->color,
            ],
            'message' => __('Sales phase created successfully'),
        ], 201);
    }

    /**
     * Update an existing sales phase.
     */
    public function updateSalesPhase(CatalogRequest $request, int $id): JsonResponse
    {
        $tenant = $request->current_tenant;

        $phase = SalesPhase::where('tenant_id', $tenant->id)->findOrFail($id);
        $phase->update($request->validated());

        return response()->json([
            'data' => [
                'id' => $phase->id,
                'nombre' => $phase->nombre,
                'orden' => $phase->orden,
                'color' => $phase->color,
            ],
            'message' => __('Sales phase updated successfully'),
        ]);
    }

    /**
     * Create a new origin.
     */
    public function storeOrigin(CatalogRequest $request): JsonResponse
    {
        $tenant = $request->current_tenant;

        $origin = Origin::create(array_merge(
            $request->validated(),
            ['tenant_id' => $tenant->id]
        ));

        return response()->json([
            'data' => [
                'id' => $origin->id,
                'nombre' => $origin->nombre,
                'tipo' => $origin->tipo,
            ],
            'message' => __('Origin created successfully'),
        ], 201);
    }

    /**
     * Update an existing origin.
     */
    public function updateOrigin(CatalogRequest $request, int $id): JsonResponse
    {
        $tenant = $request->current_tenant;

        $origin = Origin::where('tenant_id', $tenant->id)->findOrFail($id);
        $origin->update($request->validated());

        return response()->json([
            'data' => [
                'id' => $origin->id,
                'nombre' => $origin->nombre,
                'tipo' => $origin->tipo,
            ],
            'message' => __('Origin updated successfully'),
        ]);
    }
}
