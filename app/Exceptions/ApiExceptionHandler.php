<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use PDOException;
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
                'message' => __('An internal error has occurred'),
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
                            'message' => __('The provided data is not valid'),
                            'details' => $e->errors(),
                        ]
                    ]),
                    'status' => 422
                ];

            case $e instanceof QueryException:
            case $e instanceof PDOException:
                return self::handleDatabaseException($e, $request, $baseResponse);

            case $e instanceof AuthenticationException:
                return [
                    'data' => array_merge($baseResponse, [
                        'error' => [
                            'code' => 'UNAUTHENTICATED',
                            'message' => __('Authentication token required or invalid'),
                        ]
                    ]),
                    'status' => 401
                ];

            case $e instanceof AccessDeniedHttpException:
                return [
                    'data' => array_merge($baseResponse, [
                        'error' => [
                            'code' => 'ACCESS_DENIED',
                            'message' => __('You do not have permission to perform this action'),
                        ]
                    ]),
                    'status' => 403
                ];

            case $e instanceof ModelNotFoundException:
                return [
                    'data' => array_merge($baseResponse, [
                        'error' => [
                            'code' => 'RESOURCE_NOT_FOUND',
                            'message' => __('The requested resource was not found'),
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
                            'message' => __('The requested endpoint does not exist'),
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
                            'message' => $e->getMessage() ?: __('HTTP Error'),
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
                    : __('An internal server error has occurred');

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
     * Handle database exceptions
     */
    private static function handleDatabaseException(Throwable $e, Request $request, array $baseResponse): array
    {
        $errorMessage = $e->getMessage();
        $errorCode = 'DATABASE_ERROR';
        $userMessage = __('Database error occurred');
        $details = null;

        // Detectar tipos específicos de errores de base de datos
        if (str_contains($errorMessage, 'Data truncated for column')) {
            preg_match("/Data truncated for column '(\w+)'/", $errorMessage, $matches);
            $column = $matches[1] ?? 'unknown';
            
            $errorCode = 'INVALID_ENUM_VALUE';
            $userMessage = __('Invalid value for field: :field', ['field' => $column]);
            $details = [
                'field' => $column,
                'hint' => __('Please check the allowed values for this field'),
            ];
        } elseif (str_contains($errorMessage, 'Duplicate entry')) {
            preg_match("/Duplicate entry '(.+?)' for key '(.+?)'/", $errorMessage, $matches);
            $value = $matches[1] ?? '';
            $key = $matches[2] ?? '';
            
            $errorCode = 'DUPLICATE_ENTRY';
            $userMessage = __('A record with this value already exists');
            $details = [
                'value' => $value,
                'constraint' => $key,
            ];
        } elseif (str_contains($errorMessage, 'foreign key constraint fails')) {
            $errorCode = 'FOREIGN_KEY_VIOLATION';
            $userMessage = __('Referenced record does not exist or cannot be deleted');
        } elseif (str_contains($errorMessage, 'Unknown column')) {
            preg_match("/Unknown column '(\w+)'/", $errorMessage, $matches);
            $column = $matches[1] ?? 'unknown';
            
            $errorCode = 'UNKNOWN_FIELD';
            $userMessage = __('Unknown field: :field', ['field' => $column]);
            $details = [
                'field' => $column,
                'hint' => $column === 'tenant_id' 
                    ? 'Database migrations may not be up to date. Run: php artisan migrate'
                    : 'This field does not exist in the database table',
            ];
        }

        // Log completo del error
        Log::error('API Database Error', [
            'exception' => get_class($e),
            'message' => $errorMessage,
            'code' => $errorCode,
            'tenant' => $request->current_tenant?->id,
            'user' => $request->user()?->id,
            'endpoint' => $request->getPathInfo(),
            'data' => $request->all(),
        ]);

        return [
            'data' => array_merge($baseResponse, [
                'error' => [
                    'code' => $errorCode,
                    'message' => $userMessage,
                    'details' => $details,
                    'debug' => config('app.debug') ? [
                        'sql_error' => $errorMessage,
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ] : null,
                ]
            ]),
            'status' => 422
        ];
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
                'message' => __('Database connection error'),
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
                'message' => $message ?? __('Tenant related error'),
            ],
            'meta' => [
                'api_version' => 'v1',
                'timestamp' => now()->toISOString(),
            ]
        ], 400);
    }
}
