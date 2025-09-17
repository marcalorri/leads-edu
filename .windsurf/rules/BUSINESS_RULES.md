---
trigger: always_on
---

# Leads-Edu CRM - Reglas de Negocio

## Arquitectura Multi-Tenant

### Principios de Tenancy
- **Aislamiento Total**: Cada tenant tiene acceso únicamente a sus propios datos
- **Configuración Personalizable**: Cada tenant configura sus propios catálogos
- **Escalabilidad**: Soporte múltiples tenants sin degradación

## Validaciones por Resource

### Cursos
- `codigo_curso` único por tenant
- Todos los campos obligatorios excepto `deleted_at`
- Soft delete para integridad referencial

### Leads
- `email` válido y único por tenant
- `telefono` formato válido
- `motivo_nulo_id` requerido solo cuando estado = 'perdido'
- Campos UTM opcionales para tracking

### Contactos
- Un contacto por lead único
- `email_principal` válido
- `telefono_principal` obligatorio
- `dni_nie` único por tenant si se proporciona

### LeadNotes
- `contenido` obligatorio
- `usuario_id` para auditoría
- Soft delete para historial

### LeadEvents
- `titulo` y `fecha_programada` obligatorios
- `resultado` obligatorio cuando estado = 'completada'
- `fecha_completada` automática al completar
- Recordatorios según `minutos_recordatorio`

## Catálogos de Configuración

### Reglas Generales
- Todos los códigos únicos por tenant
- Campo `nombre` siempre obligatorio
- Campo `activo` para habilitar/deshabilitar

### Específicas
- **Modalidades**: `requiere_sede` para lógica de negocio
- **Provincias**: `codigo_ine` opcional para integración oficial
- **Duraciones**: Cálculo automático `horas_totales` si hay `tipo` y `valor_numerico`
- **Fases de Venta**: Campo `orden` para secuencia, `color` para UI

## Permisos y Roles

### Admin Tenant
- Acceso completo a todos los resources
- Gestión de catálogos de configuración
- Configuración de tenant settings

### Manager
- Acceso a resources principales CRM
- Visualización de métricas del equipo
- Sin acceso a configuración

### Comercial
- Acceso a sus leads asignados
- Creación de notas y eventos
- Sin acceso a configuración ni otros comerciales

## Consideraciones Técnicas

### Performance
- Índices en `tenant_id` para todas las tablas
- Cache de configuraciones por tenant
- Paginación en listados grandes

### Seguridad
- Middleware de tenant en todas las rutas
- Validación de pertenencia en queries
- Logs de auditoría por tenant

### Integridad
- Foreign keys con restricciones
- Soft deletes para mantener historial
- Validaciones en modelo y formulario