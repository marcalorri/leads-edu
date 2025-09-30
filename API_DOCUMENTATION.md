# 📚 Leads-Edu CRM - API Documentation v1

## 🔐 Autenticación

La API utiliza **Laravel Sanctum** con Personal Access Tokens para autenticación.

### Obtener Token API

1. Accede al panel de administración de tu tenant
2. Ve a **Gestión > Tokens API**
3. Crea un nuevo token con los permisos necesarios
4. Guarda el token generado (solo se muestra una vez)

### Usar Token en Requests

Incluye el token en el header `Authorization`:

```http
Authorization: Bearer tu_token_aqui
```

## 🎯 Scopes/Permisos

| Scope | Descripción |
|-------|-------------|
| `leads:read` | Ver leads del tenant |
| `leads:write` | Crear y modificar leads |
| `leads:delete` | Eliminar leads |
| `leads:admin` | Acceso completo (incluye todas las operaciones) |

## 🌐 Base URL

```
https://tu-dominio.com/api/v1
```

## 📊 Rate Limiting

- **Por Token**: 1000 requests/hora (puede variar según permisos)
- **Por Tenant**: 10,000 requests/hora
- **Por Usuario**: 2,000 requests/hora

Headers de respuesta:
- `X-RateLimit-Limit-Token`: Límite por token
- `X-RateLimit-Remaining-Token`: Requests restantes por token
- `X-RateLimit-Limit-Tenant`: Límite por tenant
- `X-RateLimit-Remaining-Tenant`: Requests restantes por tenant

## 📋 Endpoints - Leads

### 📖 Listar Leads

```http
GET /api/v1/leads
```

**Permisos requeridos:** `leads:read`

#### Parámetros de Query

| Parámetro | Tipo | Descripción |
|-----------|------|-------------|
| `page` | integer | Número de página (default: 1) |
| `per_page` | integer | Items por página (max: 100, default: 15) |
| `estado` | string | Filtrar por estado: `nuevo`, `contactado`, `interesado`, `matriculado`, `perdido` |
| `curso_id` | integer | Filtrar por ID de curso |
| `asesor_id` | integer | Filtrar por ID de asesor |
| `fecha_desde` | date | Filtrar desde fecha (YYYY-MM-DD) |
| `fecha_hasta` | date | Filtrar hasta fecha (YYYY-MM-DD) |
| `search` | string | Búsqueda en nombre, apellidos, email, teléfono |
| `sort_by` | string | Ordenar por: `created_at`, `updated_at`, `nombre`, `apellidos`, `estado` |
| `sort_direction` | string | Dirección: `asc`, `desc` (default: desc) |

#### Ejemplo de Respuesta

```json
{
  "data": [
    {
      "id": 123,
      "tenant_id": 1,
      "nombre": "Juan",
      "apellidos": "Pérez García",
      "nombre_completo": "Juan Pérez García",
      "email": "juan.perez@email.com",
      "telefono": "+34600123456",
      "pais": "España",
      "estado": "nuevo",
      "estado_label": "Nuevo",
      "curso": {
        "id": 45,
        "codigo_curso": "PROG-2024",
        "titulacion": "Programación Web"
      },
      "asesor": {
        "id": 12,
        "name": "María González",
        "email": "maria@empresa.com"
      },
      "sede": {
        "id": 3,
        "nombre": "Madrid Centro",
        "codigo": "MAD"
      },
      "modalidad": {
        "id": 1,
        "nombre": "Online",
        "requiere_sede": false
      },
      "provincia": {
        "id": 28,
        "nombre": "Madrid",
        "codigo": "28"
      },
      "fase_venta": {
        "id": 1,
        "nombre": "Primer Contacto",
        "orden": 1,
        "color": "#3B82F6"
      },
      "origen": {
        "id": 2,
        "nombre": "Página Web",
        "tipo": "web"
      },
      "convocatoria": "Septiembre 2024",
      "horario": "Mañanas",
      "utm": {
        "source": "google",
        "medium": "cpc",
        "campaign": "cursos-programacion"
      },
      "fecha_ganado": null,
      "fecha_perdido": null,
      "created_at": "2024-01-15T10:30:00.000000Z",
      "updated_at": "2024-01-15T10:30:00.000000Z",
      "dias_desde_creacion": 5,
      "tiempo_en_proceso": "5 días"
    }
  ],
  "links": {
    "first": "https://api.example.com/v1/leads?page=1",
    "last": "https://api.example.com/v1/leads?page=10",
    "prev": null,
    "next": "https://api.example.com/v1/leads?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 10,
    "per_page": 15,
    "to": 15,
    "total": 150,
    "tenant": "mi-empresa",
    "api_version": "v1",
    "timestamp": "2024-01-15T10:30:00.000000Z"
  }
}
```

### ➕ Crear Lead

```http
POST /api/v1/leads
```

**Permisos requeridos:** `leads:write`

#### Campos Requeridos

```json
{
  "nombre": "Juan",
  "apellidos": "Pérez García",
  "email": "juan.perez@email.com",
  "telefono": "+34600123456",
  "curso_id": 45,
  "sede_id": 3,
  "modalidad_id": 1,
  "origen_id": 2
}
```

#### Campos Opcionales

```json
{
  "pais": "España",
  "asesor_id": 12,
  "provincia_id": 28,
  "fase_venta_id": 1,
  "estado": "nuevo",
  "convocatoria": "Septiembre 2024",
  "horario": "Mañanas",
  "utm_source": "google",
  "utm_medium": "cpc",
  "utm_campaign": "cursos-programacion",
  "motivo_nulo_id": null
}
```

#### Respuesta Exitosa (201)

```json
{
  "message": "Lead creado exitosamente",
  "data": {
    "id": 124,
    "nombre": "Juan",
    "apellidos": "Pérez García",
    "email": "juan.perez@email.com",
    "estado": "nuevo",
    "tenant_id": 1,
    "created_at": "2024-01-15T11:00:00.000000Z"
  }
}
```

### 👁️ Ver Lead Específico

```http
GET /api/v1/leads/{id}
```

**Permisos requeridos:** `leads:read`

#### Respuesta Exitosa (200)

```json
{
  "data": {
    "id": 123,
    "tenant_id": 1,
    "nombre": "Juan",
    "apellidos": "Pérez García",
    "email": "juan.perez@email.com",
    "contacto": {
      "id": 45,
      "telefono_secundario": "+34600987654",
      "email_secundario": "juan.personal@gmail.com",
      "direccion": "Calle Mayor 123",
      "ciudad": "Madrid",
      "codigo_postal": "28001",
      "dni_nie": "12345678A",
      "profesion": "Desarrollador",
      "empresa": "TechCorp"
    }
  },
  "meta": {
    "tenant": "mi-empresa",
    "api_version": "v1",
    "timestamp": "2024-01-15T10:30:00.000000Z"
  }
}
```

### ✏️ Actualizar Lead

```http
PUT /api/v1/leads/{id}
```

**Permisos requeridos:** `leads:write`

#### Ejemplo de Request

```json
{
  "nombre": "Juan Carlos",
  "estado": "contactado",
  "fase_venta_id": 2
}
```

#### Respuesta Exitosa (200)

```json
{
  "message": "Lead actualizado exitosamente",
  "data": {
    "id": 123,
    "nombre": "Juan Carlos",
    "estado": "contactado",
    "updated_at": "2024-01-15T11:30:00.000000Z"
  }
}
```

### 🗑️ Eliminar Lead

```http
DELETE /api/v1/leads/{id}
```

**Permisos requeridos:** `leads:delete`

#### Respuesta Exitosa (200)

```json
{
  "message": "Lead eliminado exitosamente"
}
```

### 🔍 Obtener Filtros Disponibles

```http
GET /api/v1/leads/filters
```

**Permisos requeridos:** `leads:read`

#### Respuesta Exitosa (200)

```json
{
  "data": {
    "estados": {
      "nuevo": "Nuevo",
      "contactado": "Contactado",
      "interesado": "Interesado",
      "matriculado": "Matriculado",
      "perdido": "Perdido"
    },
    "cursos": [
      {
        "id": 45,
        "codigo_curso": "PROG-2024",
        "titulacion": "Programación Web"
      }
    ],
    "asesores": [
      {
        "id": 12,
        "name": "María González"
      }
    ],
    "sedes": [
      {
        "id": 3,
        "nombre": "Madrid Centro"
      }
    ],
    "modalidades": [
      {
        "id": 1,
        "nombre": "Online"
      }
    ],
    "fases_venta": [
      {
        "id": 1,
        "nombre": "Primer Contacto",
        "color": "#3B82F6"
      }
    ]
  }
}
```

## ❌ Códigos de Error

### Errores de Autenticación

#### 401 - No Autenticado
```json
{
  "error": {
    "code": "UNAUTHENTICATED",
    "message": "Token de autenticación requerido o inválido"
  }
}
```

#### 403 - Sin Permisos
```json
{
  "error": {
    "code": "INSUFFICIENT_SCOPE",
    "message": "Token no tiene permisos para: leads:write",
    "required_scope": "leads:write",
    "token_scopes": ["leads:read"]
  }
}
```

### Errores de Validación

#### 422 - Datos Inválidos
```json
{
  "error": {
    "code": "VALIDATION_FAILED",
    "message": "Los datos proporcionados no son válidos",
    "details": {
      "email": ["Ya existe un lead con este email en el tenant"],
      "telefono": ["El formato del teléfono no es válido"]
    }
  }
}
```

### Errores de Recursos

#### 404 - No Encontrado
```json
{
  "error": {
    "code": "RESOURCE_NOT_FOUND",
    "message": "El recurso solicitado no fue encontrado",
    "resource": "Lead"
  }
}
```

### Errores de Rate Limiting

#### 429 - Límite Excedido
```json
{
  "error": {
    "code": "RATE_LIMIT_EXCEEDED",
    "message": "Límite de velocidad excedido para token",
    "details": {
      "limit_type": "token",
      "limit": 1000,
      "retry_after_seconds": 3600,
      "retry_after_human": "1 horas"
    }
  }
}
```

## 📝 Ejemplos de Uso

### JavaScript/Fetch

```javascript
// Configurar headers base
const headers = {
  'Authorization': 'Bearer tu_token_aqui',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
};

// Listar leads
const response = await fetch('/api/v1/leads?page=1&per_page=20', {
  method: 'GET',
  headers
});
const leads = await response.json();

// Crear lead
const newLead = await fetch('/api/v1/leads', {
  method: 'POST',
  headers,
  body: JSON.stringify({
    nombre: 'Juan',
    apellidos: 'Pérez',
    email: 'juan@test.com',
    telefono: '+34600123456',
    curso_id: 1,
    sede_id: 1,
    modalidad_id: 1,
    origen_id: 1
  })
});
```

### cURL

```bash
# Listar leads
curl -X GET "https://tu-dominio.com/api/v1/leads" \
  -H "Authorization: Bearer tu_token_aqui" \
  -H "Accept: application/json"

# Crear lead
curl -X POST "https://tu-dominio.com/api/v1/leads" \
  -H "Authorization: Bearer tu_token_aqui" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "nombre": "Juan",
    "apellidos": "Pérez García",
    "email": "juan@test.com",
    "telefono": "+34600123456",
    "curso_id": 1,
    "sede_id": 1,
    "modalidad_id": 1,
    "origen_id": 1
  }'
```

### PHP/Guzzle

```php
use GuzzleHttp\Client;

$client = new Client([
    'base_uri' => 'https://tu-dominio.com/api/v1/',
    'headers' => [
        'Authorization' => 'Bearer tu_token_aqui',
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ]
]);

// Listar leads
$response = $client->get('leads', [
    'query' => [
        'page' => 1,
        'per_page' => 20,
        'estado' => 'nuevo'
    ]
]);
$leads = json_decode($response->getBody(), true);

// Crear lead
$response = $client->post('leads', [
    'json' => [
        'nombre' => 'Juan',
        'apellidos' => 'Pérez García',
        'email' => 'juan@test.com',
        'telefono' => '+34600123456',
        'curso_id' => 1,
        'sede_id' => 1,
        'modalidad_id' => 1,
        'origen_id' => 1
    ]
]);
```

## 🔒 Consideraciones de Seguridad

1. **Nunca expongas tu token API** en código cliente o repositorios públicos
2. **Usa HTTPS** siempre en producción
3. **Regenera tokens** periódicamente
4. **Asigna solo los permisos necesarios** a cada token
5. **Monitorea el uso** de tus tokens API
6. **Revoca tokens** inmediatamente si sospechas compromiso

## 📞 Soporte

Para soporte técnico o preguntas sobre la API:
- Email: soporte@leads-edu.com
- Documentación: https://docs.leads-edu.com
- Panel de administración: Gestión > Tokens API
