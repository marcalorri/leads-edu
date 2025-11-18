# API Error Handling - Guía de Implementación

## Cambios Realizados

### 1. Exception Handler Global (`bootstrap/app.php`)
Se registró el `ApiExceptionHandler` para capturar todas las excepciones en rutas `/api/*`:

```php
->withExceptions(function (Exceptions $exceptions) {
    $exceptions->render(function (Throwable $e, $request) {
        return \App\Exceptions\ApiExceptionHandler::render($request, $e);
    });
})
```

### 2. Manejo de Errores de Base de Datos (`ApiExceptionHandler`)
Se agregó detección inteligente de errores SQL comunes:

#### Errores Detectados:
- **INVALID_ENUM_VALUE**: Valor no permitido en campo ENUM
- **DUPLICATE_ENTRY**: Registro duplicado (unique constraint)
- **FOREIGN_KEY_VIOLATION**: Referencia a registro inexistente
- **UNKNOWN_FIELD**: Campo desconocido en la tabla

#### Ejemplo de Respuesta (Enum Inválido):
```json
{
  "error": {
    "code": "INVALID_ENUM_VALUE",
    "message": "Invalid value for field: estado",
    "details": {
      "field": "estado",
      "hint": "Please check the allowed values for this field"
    },
    "debug": {
      "sql_error": "SQLSTATE[01000]: Warning: 1265 Data truncated for column 'estado' at row 1",
      "file": "/path/to/file.php",
      "line": 123
    }
  },
  "meta": {
    "tenant": "Tenant Name",
    "api_version": "v1",
    "timestamp": "2025-11-18T17:00:00.000000Z"
  }
}
```

### 3. Try-Catch en Controlador (`LeadApiController::store`)
Se agregó manejo específico de errores al crear leads:

```php
try {
    // Crear lead...
} catch (\Illuminate\Database\QueryException $e) {
    // Error de base de datos
    return response()->json([
        'error' => [
            'code' => 'DATABASE_ERROR',
            'message' => 'Error creating lead. Please check the provided data.',
            'details' => config('app.debug') ? $e->getMessage() : null,
        ]
    ], 422);
} catch (\Exception $e) {
    // Otros errores
    return response()->json([
        'error' => [
            'code' => 'INTERNAL_ERROR',
            'message' => 'An error occurred while creating the lead',
            'details' => config('app.debug') ? $e->getMessage() : null,
        ]
    ], 500);
}
```

## Tipos de Errores y Códigos HTTP

| Tipo de Error | Código HTTP | Error Code | Descripción |
|--------------|-------------|------------|-------------|
| Validación | 422 | VALIDATION_FAILED | Datos no válidos |
| Autenticación | 401 | UNAUTHENTICATED | Token inválido/faltante |
| Permisos | 403 | ACCESS_DENIED | Sin permisos |
| No encontrado | 404 | RESOURCE_NOT_FOUND | Recurso no existe |
| Enum inválido | 422 | INVALID_ENUM_VALUE | Valor no permitido |
| Duplicado | 422 | DUPLICATE_ENTRY | Registro ya existe |
| Foreign Key | 422 | FOREIGN_KEY_VIOLATION | Referencia inválida |
| BD General | 422 | DATABASE_ERROR | Error de base de datos |
| Servidor | 500 | INTERNAL_SERVER_ERROR | Error interno |

## Ejemplos de Respuestas de Error

### Error de Validación
```json
{
  "error": {
    "code": "VALIDATION_FAILED",
    "message": "The provided data is not valid",
    "details": {
      "email": ["The email field is required."],
      "curso_id": ["The selected curso_id is invalid."]
    }
  },
  "meta": {
    "tenant": "Universidad XYZ",
    "api_version": "v1",
    "timestamp": "2025-11-18T17:00:00.000000Z"
  }
}
```

### Error de Asesor No Existente
Si envías un `asesor_id` que no existe, recibirás:

```json
{
  "error": {
    "code": "VALIDATION_FAILED",
    "message": "The provided data is not valid",
    "details": {
      "asesor_id": ["El asesor seleccionado no existe o no pertenece a tu organización."]
    }
  },
  "meta": {
    "tenant": "Universidad XYZ",
    "api_version": "v1",
    "timestamp": "2025-11-18T17:00:00.000000Z"
  }
}
```

### Error de Estado Inválido (Antes de la migración)
```json
{
  "error": {
    "code": "INVALID_ENUM_VALUE",
    "message": "Invalid value for field: estado",
    "details": {
      "field": "estado",
      "hint": "Please check the allowed values for this field"
    }
  },
  "meta": {
    "tenant": "Universidad XYZ",
    "api_version": "v1",
    "timestamp": "2025-11-18T17:00:00.000000Z"
  }
}
```

### Error de Email Duplicado
```json
{
  "error": {
    "code": "DUPLICATE_ENTRY",
    "message": "A record with this value already exists",
    "details": {
      "value": "test@example.com",
      "constraint": "leads_tenant_id_email_unique"
    }
  },
  "meta": {
    "tenant": "Universidad XYZ",
    "api_version": "v1",
    "timestamp": "2025-11-18T17:00:00.000000Z"
  }
}
```

## Modo Debug

Cuando `APP_DEBUG=true` en `.env`, las respuestas incluyen información adicional:

```json
{
  "error": {
    "code": "INTERNAL_SERVER_ERROR",
    "message": "Error detallado del servidor",
    "debug": {
      "exception": "Illuminate\\Database\\QueryException",
      "file": "/var/www/app/Http/Controllers/Api/V1/LeadApiController.php",
      "line": 114,
      "trace": "Stack trace completo..."
    }
  }
}
```

⚠️ **IMPORTANTE**: En producción, asegúrate de tener `APP_DEBUG=false` para no exponer información sensible.

## Testing

### Probar Error de Validación (Asesor Inexistente)
```bash
curl -X POST https://leads-edu.com/api/v1/leads \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Test",
    "apellidos": "User",
    "email": "test@example.com",
    "telefono": "123456789",
    "curso_id": 1,
    "sede_id": 1,
    "modalidad_id": 1,
    "origen_id": 1,
    "fase_venta_id": 1,
    "asesor_id": 99999
  }'
```

**Respuesta esperada**: HTTP 422 con detalles del error de validación.

### Probar Error de Estado Inválido (Antes de migración)
```bash
curl -X POST https://leads-edu.com/api/v1/leads \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Test",
    "apellidos": "User",
    "email": "test2@example.com",
    "telefono": "123456789",
    "curso_id": 1,
    "sede_id": 1,
    "modalidad_id": 1,
    "origen_id": 1,
    "fase_venta_id": 1,
    "estado": "nuevo"
  }'
```

**Respuesta esperada**: HTTP 422 con `INVALID_ENUM_VALUE` (antes de migración) o éxito (después de migración).

### Probar Email Duplicado
```bash
# Crear lead
curl -X POST https://leads-edu.com/api/v1/leads \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Test",
    "apellidos": "User",
    "email": "duplicate@example.com",
    "telefono": "123456789",
    "curso_id": 1,
    "sede_id": 1,
    "modalidad_id": 1,
    "origen_id": 1,
    "fase_venta_id": 1
  }'

# Intentar crear otro con mismo email
curl -X POST https://leads-edu.com/api/v1/leads \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Test2",
    "apellidos": "User2",
    "email": "duplicate@example.com",
    "telefono": "987654321",
    "curso_id": 1,
    "sede_id": 1,
    "modalidad_id": 1,
    "origen_id": 1,
    "fase_venta_id": 1
  }'
```

**Respuesta esperada**: HTTP 422 con `DUPLICATE_ENTRY`.

## Logs

Todos los errores se registran en `storage/logs/laravel.log` con contexto completo:

```
[2025-11-18 17:00:00] production.ERROR: API Database Error
{
  "exception": "Illuminate\\Database\\QueryException",
  "message": "SQLSTATE[01000]: Warning: 1265 Data truncated for column 'estado' at row 1",
  "code": "INVALID_ENUM_VALUE",
  "tenant": 4,
  "user": 2,
  "endpoint": "/api/v1/leads",
  "data": {
    "nombre": "Carlos Gabriel",
    "apellidos": "Calsina Chambi",
    "email": "test@example.com",
    ...
  }
}
```

## Próximos Pasos

1. **Ejecutar migración** en producción:
   ```bash
   php artisan migrate
   ```

2. **Probar endpoints** con diferentes escenarios de error

3. **Actualizar documentación** de la API para clientes externos

4. **Configurar alertas** para errores 500 en producción (opcional)
