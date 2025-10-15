# Leads-Edu API Reference

## Autenticación

Todas las rutas requieren autenticación mediante **Bearer Token** (Sanctum).

```bash
Authorization: Bearer {tu_token_api}
```

### Scopes Disponibles

- `leads:read` - Ver leads y catálogos
- `leads:write` - Crear y modificar leads
- `leads:delete` - Eliminar leads
- `leads:admin` - Acceso completo (incluye gestión)

---

## Endpoints de Leads

### 1. Listar Leads

```http
GET /api/v1/leads
```

**Scope requerido**: `leads:read`

**Parámetros de Query** (opcionales):
- `estado` - Filtrar por estado (nuevo, contactado, interesado, matriculado, perdido)
- `curso_id` - Filtrar por ID de curso
- `asesor_id` - Filtrar por ID de asesor
- `fecha_desde` - Filtrar desde fecha (YYYY-MM-DD)
- `fecha_hasta` - Filtrar hasta fecha (YYYY-MM-DD)
- `page` - Número de página (default: 1)
- `per_page` - Registros por página (default: 15, max: 100)

**Respuesta**:
```json
{
  "data": [
    {
      "id": 1,
      "tenant_id": 1,
      "nombre": "Juan",
      "apellidos": "Pérez",
      "email": "juan@example.com",
      "telefono": "612345678",
      "estado": "nuevo",
      "curso": { "id": 1, "codigo_curso": "ABC", "titulacion": "..." },
      "asesor": { "id": 2, "name": "María", "email": "..." },
      "contacto": { ... },
      ...
    }
  ],
  "links": { ... },
  "meta": { ... }
}
```

---

### 2. Ver Lead Específico

```http
GET /api/v1/leads/{id}
```

**Scope requerido**: `leads:read`

**Respuesta**: Objeto lead completo con todas las relaciones.

---

### 3. Crear Lead

```http
POST /api/v1/leads
```

**Scope requerido**: `leads:write`

**Body** (JSON):
```json
{
  "nombre": "Juan",
  "apellidos": "Pérez",
  "email": "juan@example.com",
  "telefono": "612345678",
  "pais": "España",
  "estado": "nuevo",
  "curso_id": 1,
  "asesor_id": 2,           // Opcional - Ver lógica de asignación
  "sede_id": 1,             // Opcional
  "modalidad_id": 1,        // Opcional
  "provincia_id": 28,       // Opcional
  "fase_venta_id": 1,       // Opcional
  "origen_id": 1,           // Opcional
  "convocatoria": "2024-1", // Opcional
  "horario": "Mañana",      // Opcional
  "utm_source": "google",   // Opcional
  "utm_medium": "cpc",      // Opcional
  "utm_campaign": "verano"  // Opcional
}
```

**Campos Obligatorios**:
- `nombre`
- `email` o `telefono` (al menos uno)

**Lógica de Asignación de Asesor**:
1. Si existe contacto con asesor → Usa asesor del contacto
2. Si se especifica `asesor_id` → Usa ese asesor
3. Si hay usuario autenticado → Usa usuario actual
4. Si no → Deja NULL

**Respuesta**: Lead creado con código 201.

---

### 4. Actualizar Lead

```http
PUT /api/v1/leads/{id}
```

**Scope requerido**: `leads:write`

**Body**: Mismos campos que crear (todos opcionales).

**Respuesta**: Lead actualizado.

---

### 5. Eliminar Lead

```http
DELETE /api/v1/leads/{id}
```

**Scope requerido**: `leads:delete`

**Respuesta**: 204 No Content

---

## Endpoints de Catálogos

### Permisos por Operación
- **Lectura (GET)**: Requiere scope `leads:read`
- **Escritura (POST/PUT)**: Requiere scope `leads:admin`

### 1. Cursos

#### Listar Cursos
```http
GET /api/v1/catalogs/courses
```

**Scope**: `leads:read`

**Respuesta**:
```json
{
  "data": [
    {
      "id": 1,
      "codigo_curso": "ABC123",
      "titulacion": "Grado en Informática",
      "area": { "id": 1, "nombre": "Tecnología", "codigo": "TEC" },
      "unidad_negocio": { "id": 1, "nombre": "Grados", "codigo": "GRD" },
      "duracion": { "id": 1, "nombre": "4 años" }
    }
  ],
  "meta": { "total": 10 }
}
```

#### Crear Curso
```http
POST /api/v1/catalogs/courses
```

**Scope**: `leads:admin`

**Body**:
```json
{
  "codigo_curso": "ABC123",
  "titulacion": "Grado en Informática",
  "area_id": 1,
  "unidad_negocio_id": 1,
  "duracion_id": 1
}
```

**Respuesta**: 201 Created

#### Actualizar Curso
```http
PUT /api/v1/catalogs/courses/{id}
```

**Scope**: `leads:admin`

**Body**: Mismos campos que crear (todos obligatorios)

---

### 2. Asesores

**Nota**: Los asesores son **solo lectura** (se gestionan desde el panel).

```http
GET /api/v1/catalogs/asesores
```

**Scope**: `leads:read`

**Respuesta**:
```json
{
  "data": [
    {
      "id": 2,
      "name": "María García",
      "email": "maria@tenant.com"
    }
  ],
  "meta": { "total": 5 }
}
```

---

### 3. Sedes

```http
GET /api/v1/catalogs/campuses
```

**Respuesta**:
```json
{
  "data": [
    {
      "id": 1,
      "codigo": "BCN",
      "nombre": "Barcelona Centro",
      "ciudad": "Barcelona",
      "direccion": "Calle Mayor 123"
    }
  ],
  "meta": { "total": 3 }
}
```

---

### 4. Modalidades

```http
GET /api/v1/catalogs/modalities
```

**Respuesta**:
```json
{
  "data": [
    {
      "id": 1,
      "codigo": "ONL",
      "nombre": "Online",
      "requiere_sede": false
    }
  ],
  "meta": { "total": 3 }
}
```

---

### 5. Provincias

```http
GET /api/v1/catalogs/provinces
```

**Respuesta**:
```json
{
  "data": [
    {
      "id": 28,
      "codigo": "M",
      "nombre": "Madrid",
      "comunidad_autonoma": "Comunidad de Madrid"
    }
  ],
  "meta": { "total": 52 }
}
```

---

### 6. Fases de Venta

```http
GET /api/v1/catalogs/sales-phases
```

**Respuesta**:
```json
{
  "data": [
    {
      "id": 1,
      "nombre": "Hot",
      "orden": 0,
      "color": "#ff0000"
    }
  ],
  "meta": { "total": 5 }
}
```

---

### 7. Orígenes

```http
GET /api/v1/catalogs/origins
```

**Respuesta**:
```json
{
  "data": [
    {
      "id": 1,
      "nombre": "Web",
      "tipo": "web"
    }
  ],
  "meta": { "total": 8 }
}
```

---

### 8. Estados Disponibles

```http
GET /api/v1/catalogs/estados
```

**Respuesta**:
```json
{
  "data": [
    { "value": "nuevo", "label": "Nuevo" },
    { "value": "contactado", "label": "Contactado" },
    { "value": "interesado", "label": "Interesado" },
    { "value": "matriculado", "label": "Matriculado" },
    { "value": "perdido", "label": "Perdido" }
  ],
  "meta": { "total": 5 }
}
```

---

## Rate Limiting

- **Por Token**: 1000 requests/hora
- **Por Tenant**: 10000 requests/hora

**Headers de respuesta**:
```
X-RateLimit-Limit: 1000
X-RateLimit-Remaining: 999
```

---

## Códigos de Error

| Código | Descripción |
|--------|-------------|
| 400 | Bad Request - Datos inválidos |
| 401 | Unauthorized - Token inválido o expirado |
| 403 | Forbidden - Sin permisos (scope incorrecto) |
| 404 | Not Found - Recurso no encontrado |
| 422 | Unprocessable Entity - Validación fallida |
| 429 | Too Many Requests - Rate limit excedido |
| 500 | Internal Server Error |

**Formato de error**:
```json
{
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "Los datos proporcionados no son válidos",
    "errors": {
      "email": ["El email ya está en uso"]
    }
  }
}
```

---

## Ejemplo Completo: Crear Lead

```bash
curl -X POST https://tu-dominio.com/api/v1/leads \
  -H "Authorization: Bearer tu_token_api" \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Juan",
    "apellidos": "Pérez García",
    "email": "juan.perez@example.com",
    "telefono": "612345678",
    "pais": "España",
    "estado": "nuevo",
    "curso_id": 1,
    "sede_id": 1,
    "modalidad_id": 1,
    "provincia_id": 28,
    "fase_venta_id": 1,
    "origen_id": 1,
    "utm_source": "google",
    "utm_medium": "cpc",
    "utm_campaign": "verano2024"
  }'
```

---

## Flujo Recomendado

1. **Obtener catálogos** (cursos, sedes, modalidades, etc.)
2. **Crear lead** con los IDs obtenidos
3. **Consultar lead** para verificar creación
4. **Actualizar lead** según evolucione el proceso

---

## Resumen de Endpoints de Escritura para Catálogos

Todos requieren **scope**: `leads:admin`

### Cursos
```bash
POST   /api/v1/catalogs/courses
PUT    /api/v1/catalogs/courses/{id}
```

**Campos obligatorios**: `codigo_curso`, `titulacion`, `area_id`, `unidad_negocio_id`, `duracion_id`

### Sedes
```bash
POST   /api/v1/catalogs/campuses
PUT    /api/v1/catalogs/campuses/{id}
```

**Campos obligatorios**: `codigo`, `nombre`  
**Campos opcionales**: `direccion`, `ciudad`, `codigo_postal`, `telefono`, `email`, `responsable`, `activo`

### Modalidades
```bash
POST   /api/v1/catalogs/modalities
PUT    /api/v1/catalogs/modalities/{id}
```

**Campos obligatorios**: `codigo`, `nombre`  
**Campos opcionales**: `descripcion`, `requiere_sede`, `activo`

### Provincias
```bash
POST   /api/v1/catalogs/provinces
PUT    /api/v1/catalogs/provinces/{id}
```

**Campos obligatorios**: `codigo`, `nombre`  
**Campos opcionales**: `codigo_ine`, `comunidad_autonoma`, `activo`

### Fases de Venta
```bash
POST   /api/v1/catalogs/sales-phases
PUT    /api/v1/catalogs/sales-phases/{id}
```

**Campos obligatorios**: `nombre`, `orden`, `color` (hex: #RRGGBB)  
**Campos opcionales**: `descripcion`, `activo`

### Orígenes
```bash
POST   /api/v1/catalogs/origins
PUT    /api/v1/catalogs/origins/{id}
```

**Campos obligatorios**: `nombre`, `tipo`  
**Tipos válidos**: `web`, `telefono`, `email`, `redes_sociales`, `referido`, `evento`, `publicidad`, `otro`  
**Campos opcionales**: `descripcion`, `activo`

---

## Notas Importantes

- ✅ **Lectura**: Scope `leads:read` para GET
- ✅ **Escritura**: Scope `leads:admin` para POST/PUT
- ✅ **Asesores**: Solo lectura (se gestionan desde el panel)
- ✅ **Estados**: Solo lectura (es un enum fijo)
- ✅ El `tenant_id` se obtiene automáticamente del token
- ✅ Los contactos se crean/actualizan automáticamente
- ✅ El asesor se asigna según la lógica de prioridad
- ✅ Los campos UTM son opcionales para tracking
- ✅ Validación de códigos únicos por tenant
- ✅ Todos los catálogos devuelven mensaje de éxito
