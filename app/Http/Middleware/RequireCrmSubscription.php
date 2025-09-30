<?php

namespace App\Http\Middleware;

use App\Support\CrmSubscription;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireCrmSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Si no hay usuario autenticado, redirigir a login
        if (!$user) {
            return redirect()->route('filament.dashboard.auth.login');
        }
        
        // Si no tiene suscripción activa, redirigir a página de upgrade
        if (CrmSubscription::isInactive()) {
            return redirect()
                ->to(CrmSubscription::getUpgradeUrl())
                ->with('error', CrmSubscription::getStatusMessage());
        }
        
        return $next($request);
    }
}
