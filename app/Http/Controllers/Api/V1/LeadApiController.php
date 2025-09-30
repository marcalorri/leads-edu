<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LeadStoreRequest;
use App\Http\Requests\Api\V1\LeadUpdateRequest;
use App\Http\Resources\Api\V1\LeadResource;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LeadApiController extends Controller
{
    /**
     * Display a listing of leads for the current tenant.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $tenant = $request->current_tenant;
        $user = $request->user();

        // Construir query base
        $query = Lead::query()
            ->where('tenant_id', $tenant->id)
            ->with(['course', 'asesor', 'campus', 'modality', 'province', 'salesPhase', 'origin']);

        // Aplicar filtros de usuario (si no puede ver todos los leads)
        if (!$user->canViewAllLeads($tenant)) {
            $query->where('asesor_id', $user->id);
        }

        // Filtros opcionales
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('curso_id')) {
            $query->where('curso_id', $request->curso_id);
        }

        if ($request->filled('asesor_id')) {
            $query->where('asesor_id', $request->asesor_id);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        // Búsqueda por texto
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telefono', 'like', "%{$search}%");
            });
        }

        // Ordenamiento
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        $allowedSorts = ['created_at', 'updated_at', 'nombre', 'apellidos', 'estado'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDirection);
        }

        // Paginación
        $perPage = min($request->get('per_page', 15), 100); // Máximo 100 por página
        $leads = $query->paginate($perPage);

        return LeadResource::collection($leads);
    }

    /**
     * Store a newly created lead.
     */
    public function store(LeadStoreRequest $request): JsonResponse
    {
        $tenant = $request->current_tenant;
        
        $leadData = $request->validated();
        $leadData['tenant_id'] = $tenant->id;

        // Si no se especifica asesor, asignar al usuario actual
        if (!isset($leadData['asesor_id'])) {
            $leadData['asesor_id'] = $request->user()->id;
        }

        $lead = Lead::create($leadData);
        $lead->load(['course', 'asesor', 'campus', 'modality', 'province', 'salesPhase', 'origin']);

        return response()->json([
            'message' => 'Lead creado exitosamente',
            'data' => new LeadResource($lead)
        ], 201);
    }

    /**
     * Display the specified lead.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $tenant = $request->current_tenant;
        $user = $request->user();

        $query = Lead::where('tenant_id', $tenant->id)
            ->with(['course', 'asesor', 'campus', 'modality', 'province', 'salesPhase', 'origin', 'contact']);

        // Aplicar filtros de usuario
        if (!$user->canViewAllLeads($tenant)) {
            $query->where('asesor_id', $user->id);
        }

        $lead = $query->findOrFail($id);

        return response()->json([
            'data' => new LeadResource($lead)
        ]);
    }

    /**
     * Update the specified lead.
     */
    public function update(LeadUpdateRequest $request, int $id): JsonResponse
    {
        $tenant = $request->current_tenant;
        $user = $request->user();

        $query = Lead::where('tenant_id', $tenant->id);

        // Aplicar filtros de usuario (solo puede editar sus leads si no es admin)
        if (!$user->canManageLeads($tenant)) {
            $query->where('asesor_id', $user->id);
        }

        $lead = $query->findOrFail($id);
        
        $leadData = $request->validated();
        $lead->update($leadData);
        
        $lead->load(['course', 'asesor', 'campus', 'modality', 'province', 'salesPhase', 'origin']);

        return response()->json([
            'message' => 'Lead actualizado exitosamente',
            'data' => new LeadResource($lead)
        ]);
    }

    /**
     * Remove the specified lead (soft delete).
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $tenant = $request->current_tenant;
        $user = $request->user();

        $query = Lead::where('tenant_id', $tenant->id);

        // Solo admins pueden eliminar leads
        if (!$user->canManageLeads($tenant)) {
            return response()->json([
                'error' => [
                    'code' => 'PERMISSION_DENIED',
                    'message' => 'No tienes permisos para eliminar leads'
                ]
            ], 403);
        }

        $lead = $query->findOrFail($id);
        $lead->delete();

        return response()->json([
            'message' => 'Lead eliminado exitosamente'
        ]);
    }

    /**
     * Get available filter options for the tenant.
     */
    public function filters(Request $request): JsonResponse
    {
        $tenant = $request->current_tenant;

        $filters = [
            'estados' => [
                'nuevo' => 'Nuevo',
                'contactado' => 'Contactado', 
                'interesado' => 'Interesado',
                'matriculado' => 'Matriculado',
                'perdido' => 'Perdido'
            ],
            'cursos' => $tenant->courses()->select('id', 'codigo_curso', 'titulacion')->get(),
            'asesores' => $tenant->users()->select('id', 'name')->get(),
            'sedes' => $tenant->campuses()->select('id', 'nombre')->get(),
            'modalidades' => $tenant->modalities()->select('id', 'nombre')->get(),
            'fases_venta' => $tenant->salesPhases()->select('id', 'nombre', 'color')->orderBy('orden')->get()
        ];

        return response()->json([
            'data' => $filters
        ]);
    }
}
