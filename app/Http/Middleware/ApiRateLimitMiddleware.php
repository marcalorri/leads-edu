<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ApiRateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  int  $maxAttempts
     * @param  int  $decayMinutes
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, int $maxAttempts = 1000, int $decayMinutes = 60)
    {
        $user = $request->user();
        $token = $user->currentAccessToken();
        $tenant = $request->current_tenant;

        // Crear claves únicas para rate limiting
        $tokenKey = 'api_rate_limit:token:' . $token->id;
        $tenantKey = 'api_rate_limit:tenant:' . $tenant->id;
        $userKey = 'api_rate_limit:user:' . $user->id;

        // Límites por token (más restrictivo)
        $tokenLimit = $this->getTokenLimit($token, $maxAttempts);
        if (RateLimiter::tooManyAttempts($tokenKey, $tokenLimit)) {
            return $this->buildRateLimitResponse($tokenKey, $tokenLimit, 'token');
        }

        // Límites por tenant (límite global del tenant)
        $tenantLimit = $this->getTenantLimit($tenant, $maxAttempts * 10); // 10x el límite por token
        if (RateLimiter::tooManyAttempts($tenantKey, $tenantLimit)) {
            return $this->buildRateLimitResponse($tenantKey, $tenantLimit, 'tenant');
        }

        // Límites por usuario (límite por usuario individual)
        $userLimit = $this->getUserLimit($user, $maxAttempts * 2); // 2x el límite por token
        if (RateLimiter::tooManyAttempts($userKey, $userLimit)) {
            return $this->buildRateLimitResponse($userKey, $userLimit, 'user');
        }

        // Incrementar contadores
        RateLimiter::hit($tokenKey, $decayMinutes * 60);
        RateLimiter::hit($tenantKey, $decayMinutes * 60);
        RateLimiter::hit($userKey, $decayMinutes * 60);

        $response = $next($request);

        // Agregar headers de rate limiting
        $response->headers->set('X-RateLimit-Limit-Token', $tokenLimit);
        $response->headers->set('X-RateLimit-Remaining-Token', RateLimiter::remaining($tokenKey, $tokenLimit));
        $response->headers->set('X-RateLimit-Limit-Tenant', $tenantLimit);
        $response->headers->set('X-RateLimit-Remaining-Tenant', RateLimiter::remaining($tenantKey, $tenantLimit));

        return $response;
    }

    /**
     * Get rate limit for specific token
     */
    private function getTokenLimit($token, int $default): int
    {
        // Diferentes límites según los scopes del token
        $abilities = $token->abilities ?? [];
        
        if (in_array('leads:admin', $abilities)) {
            return $default * 2; // Tokens admin tienen más límite
        }
        
        if (in_array('leads:write', $abilities) || in_array('leads:delete', $abilities)) {
            return $default; // Tokens de escritura tienen límite estándar
        }
        
        return intval($default * 0.5); // Tokens de solo lectura tienen menos límite
    }

    /**
     * Get rate limit for tenant
     */
    private function getTenantLimit($tenant, int $default): int
    {
        // Aquí podrías implementar límites basados en el plan de suscripción
        // Por ahora, todos los tenants tienen el mismo límite
        return $default;
    }

    /**
     * Get rate limit for user
     */
    private function getUserLimit($user, int $default): int
    {
        // Los admins tienen límites más altos
        if ($user->isAdmin()) {
            return $default * 2;
        }
        
        return $default;
    }

    /**
     * Build rate limit exceeded response
     */
    private function buildRateLimitResponse(string $key, int $limit, string $type): Response
    {
        $retryAfter = RateLimiter::availableIn($key);
        
        return response()->json([
            'error' => [
                'code' => 'RATE_LIMIT_EXCEEDED',
                'message' => "Límite de velocidad excedido para {$type}",
                'details' => [
                    'limit_type' => $type,
                    'limit' => $limit,
                    'retry_after_seconds' => $retryAfter,
                    'retry_after_human' => $this->formatRetryAfter($retryAfter),
                ]
            ],
            'meta' => [
                'timestamp' => now()->toISOString(),
            ]
        ], 429, [
            'Retry-After' => $retryAfter,
            'X-RateLimit-Limit' => $limit,
            'X-RateLimit-Remaining' => 0,
            'X-RateLimit-Reset' => now()->addSeconds($retryAfter)->timestamp,
        ]);
    }

    /**
     * Format retry after time in human readable format
     */
    private function formatRetryAfter(int $seconds): string
    {
        if ($seconds < 60) {
            return "{$seconds} segundos";
        }
        
        $minutes = intval($seconds / 60);
        if ($minutes < 60) {
            return "{$minutes} minutos";
        }
        
        $hours = intval($minutes / 60);
        return "{$hours} horas";
    }
}
