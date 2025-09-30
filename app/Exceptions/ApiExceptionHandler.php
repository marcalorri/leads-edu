<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiExceptionHandler
{
    /**
     * Render API exception response
     */
    public static function render(Request $request, Throwable $e): JsonResponse
    {
        // Solo manejar rutas de API
        if (!$request->is('api/*')) {
            throw $e;
        }

        $response = self::getApiErrorResponse($e, $request);
        
        return response()->json($response['data'], $response['status']);
    }

    /**
     * Get structured API error response
     */
    private static function getApiErrorResponse(Throwable $e, Request $request): array
    {
        $tenant = $request->current_tenant ?? null;
        
        $baseResponse = [
            'error' => [
                'code' => 'INTERNAL_ERROR',
                'message' => 'Ha ocurrido un error interno',
            ],
            'meta' => [
                'tenant' => $tenant?->name,
                'api_version' => 'v1',
                'timestamp' => now()->toISOString(),
            ]
        ];

        // Manejar diferentes tipos de excepciones
        switch (true) {
            case $e instanceof ValidationException:
                return [
                    'data' => array_merge($baseResponse, [
                        'error' => [
                            'code' => 'VALIDATION_FAILED',
                            'message' => 'Los datos proporcionados no son válidos',
                            'details' => $e->errors(),
                        ]
                    ]),
                    'status' => 422
                ];

            case $e instanceof AuthenticationException:
                return [
                    'data' => array_merge($baseResponse, [
                        'error' => [
                            'code' => 'UNAUTHENTICATED',
                            'message' => 'Token de autenticación requerido o inválido',
                        ]
                    ]),
                    'status' => 401
                ];

            case $e instanceof AccessDeniedHttpException:
                return [
                    'data' => array_merge($baseResponse, [
                        'error' => [
                            'code' => 'ACCESS_DENIED',
                            'message' => 'No tienes permisos para realizar esta acción',
                        ]
                    ]),
                    'status' => 403
                ];

            case $e instanceof ModelNotFoundException:
                return [
                    'data' => array_merge($baseResponse, [
                        'error' => [
                            'code' => 'RESOURCE_NOT_FOUND',
                            'message' => 'El recurso solicitado no fue encontrado',
                            'resource' => self::getModelName($e),
                        ]
                    ]),
                    'status' => 404
                ];

            case $e instanceof NotFoundHttpException:
                return [
                    'data' => array_merge($baseResponse, [
                        'error' => [
                            'code' => 'ENDPOINT_NOT_FOUND',
                            'message' => 'El endpoint solicitado no existe',
                            'endpoint' => $request->getPathInfo(),
                        ]
                    ]),
                    'status' => 404
                ];

            case $e instanceof HttpException:
                return [
                    'data' => array_merge($baseResponse, [
                        'error' => [
                            'code' => 'HTTP_ERROR',
                            'message' => $e->getMessage() ?: 'Error HTTP',
                        ]
                    ]),
                    'status' => $e->getStatusCode()
                ];

            default:
                // Log error for debugging
                Log::error('API Error', [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'tenant' => $tenant?->id,
                    'user' => $request->user()?->id,
                    'endpoint' => $request->getPathInfo(),
                    'method' => $request->getMethod(),
                ]);

                $message = config('app.debug') 
                    ? $e->getMessage() 
                    : 'Ha ocurrido un error interno del servidor';

                return [
                    'data' => array_merge($baseResponse, [
                        'error' => [
                            'code' => 'INTERNAL_SERVER_ERROR',
                            'message' => $message,
                            'debug' => config('app.debug') ? [
                                'exception' => get_class($e),
                                'file' => $e->getFile(),
                                'line' => $e->getLine(),
                                'trace' => $e->getTraceAsString(),
                            ] : null,
                        ]
                    ]),
                    'status' => 500
                ];
        }
    }

    /**
     * Get model name from ModelNotFoundException
     */
    private static function getModelName(ModelNotFoundException $e): string
    {
        $model = $e->getModel();
        
        if (is_string($model)) {
            return class_basename($model);
        }
        
        return 'Resource';
    }

    /**
     * Handle database connection errors
     */
    public static function handleDatabaseError(Throwable $e, Request $request): JsonResponse
    {
        Log::error('Database connection error in API', [
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'tenant' => $request->current_tenant?->id,
        ]);

        return response()->json([
            'error' => [
                'code' => 'DATABASE_ERROR',
                'message' => 'Error de conexión con la base de datos',
            ],
            'meta' => [
                'tenant' => $request->current_tenant?->name,
                'api_version' => 'v1',
                'timestamp' => now()->toISOString(),
            ]
        ], 503);
    }

    /**
     * Handle tenant resolution errors
     */
    public static function handleTenantError(Request $request, string $message = null): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 'TENANT_ERROR',
                'message' => $message ?? 'Error relacionado con el tenant',
            ],
            'meta' => [
                'api_version' => 'v1',
                'timestamp' => now()->toISOString(),
            ]
        ], 400);
    }
}
