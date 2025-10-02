<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $scope
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $scope)
    {
        $user = $request->user();
        $token = $user->currentAccessToken();
        $tenant = $request->current_tenant;

        // Verificar que el token tenga el scope requerido
        if (!$token->can($scope)) {
            return response()->json([
                'error' => [
                    'code' => 'INSUFFICIENT_SCOPE',
                    'message' => "Token no tiene permisos para: {$scope}",
                    'required_scope' => $scope,
                    'token_scopes' => $token->abilities ?? []
                ]
            ], 403);
        }

        // Verificar permisos específicos del usuario en el tenant
        // Para API, simplificamos: si el token tiene el scope y el usuario pertenece al tenant, permitimos
        // La validación de permisos específicos se hace en el controller según el rol
        $hasPermission = match($scope) {
            'leads:read' => true, // Cualquier usuario con el scope puede leer
            'leads:write' => $user->canManageLeads($tenant) || $user->isTenantAdmin($tenant),
            'leads:delete' => $user->canManageLeads($tenant) || $user->isTenantAdmin($tenant),
            'leads:admin' => $user->isTenantAdmin($tenant),
            default => false
        };

        if (!$hasPermission) {
            return response()->json([
                'error' => [
                    'code' => 'PERMISSION_DENIED',
                    'message' => "No tienes permisos para realizar esta acción en el tenant",
                    'required_permission' => $scope,
                    'tenant' => $tenant->name
                ]
            ], 403);
        }

        return $next($request);
    }
}
