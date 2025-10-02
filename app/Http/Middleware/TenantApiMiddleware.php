<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Sanctum\PersonalAccessToken;

class TenantApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar que el usuario esté autenticado via Sanctum
        if (!$request->user()) {
            return response()->json([
                'error' => [
                    'code' => 'UNAUTHENTICATED',
                    'message' => 'Token de autenticación requerido',
                ]
            ], 401);
        }

        // Obtener el token actual
        $token = $request->user()->currentAccessToken();
        
        if (!$token) {
            return response()->json([
                'error' => [
                    'code' => 'INVALID_TOKEN',
                    'message' => 'Token de acceso inválido',
                ]
            ], 401);
        }

        // Verificar que el token tenga tenant_id
        if (!isset($token->tenant_id)) {
            return response()->json([
                'error' => [
                    'code' => 'TENANT_REQUIRED',
                    'message' => 'El token debe estar asociado a un tenant',
                ]
            ], 403);
        }

        // Obtener el tenant del token
        $tenant = Tenant::find($token->tenant_id);
        
        if (!$tenant) {
            return response()->json([
                'error' => [
                    'code' => 'TENANT_NOT_FOUND',
                    'message' => 'Tenant no encontrado',
                ]
            ], 404);
        }

        // Verificer que el usuario pertenezca al tenant
        if (!$request->user()->tenants()->where('tenant_id', $tenant->id)->exists()) {
            return response()->json([
                'error' => [
                    'code' => 'TENANT_ACCESS_DENIED',
                    'message' => 'No tienes acceso a este tenant',
                ]
            ], 403);
        }

        // Agregar tenant al request para uso posterior
        // No usamos filament()->setTenant() porque estamos en contexto API, no web
        $request->merge(['current_tenant' => $tenant]);

        return $next($request);
    }
}
