---
trigger: always_on
---

# Leads-Edu CRM - Reglas de Funcionalidades

## Descripción del Sistema
**Leads-Edu CRM** es un sistema de gestión de relaciones con clientes especializado para el sector educativo, construido sobre SaaSykit Tenancy para proporcionar aislamiento completo entre diferentes instituciones educativas.

## Arquitectura Multi-Tenant

### Principios de Tenancy
- **Aislamiento Total**: Cada tenant (institución educativa) tiene acceso únicamente a sus propios datos
- **Configuración Personalizable**: Cada tenant puede configurar sus propios catálogos y parámetros
- **Escalabilidad**: El sistema debe soportar múltiples tenants sin degradación de rendimient

## Recursos Principales

### 1. Resource: Cursos (Courses)

#### Campos Principales
```php
- id: bigint (PK, auto-increment)
- tenant_id: bigint (FK, índice)
- codigo_curso: string(50) (único por tenant)
- titulacion: string(255)
- area_id: bigint (FK)
- unidad_negocio_id: bigint (FK)
- duracion_id: bigint (FK)
- created_at: timestamp
- updated_at: timestamp
- deleted_at: timestamp (soft delete)
```

#### Reglas de Negocio
- El `codigo_curso` debe ser único dentro del tenant
- Todos los campos son obligatorios excepto `deleted_at`
- Implementar soft delete para mantener integridad referencial
- Los catálogos (Areas, Unidades de Negocio, Duraciones) son gestionables por el admin

#### Relaciones
- Pertenece a un Tenant (belongsTo)
- Pertenece a un Área (belongsTo)
- Pertenece a una Unidad de Negocio (belongsTo)
- Pertenece a una Duración (belongsTo)
- Tiene muchos Leads (hasMany)

### 2. Resource: Leads

#### Campos Principales
```php
- id: bigint (PK, auto-increment)
- tenant_id: bigint (FK, índice)
- asesor_id: bigint (FK) // Usuario asignado como asesor
- estado: enum('nuevo', 'contactado', 'interesado', 'matriculado', 'perdido')
- fase_venta_id: bigint (FK a configuración tenant)
- curso_id: bigint (FK)
- sede_id: bigint (FK)
- modalidad_id: bigint (FK)
- provincia_id: bigint (FK)
- nombre: string(100)
- apellidos: string(150)
- telefono: string(20)
- email: string(255)
- motivo_nulo_id: bigint (FK a configuración tenant, nullable)
- origen_id: bigint (FK a configuración tenant)
- convocatoria: string(100)
- horario: string(100)
- utm_source: string(255) (nullable)
- utm_medium: string(255) (nullable)
- utm_campaign: string(255) (nullable)
- created_at: timestamp
- updated_at: timestamp
- deleted_at: timestamp (soft delete)
```

#### Reglas de Negocio
- El `email` debe ser válido y único por tenant
- El `telefono` debe seguir formato válido
- `estado` tiene valores predefinidos pero `fase_venta` es configurable por tenant
- Los campos UTM son opcionales para tracking de marketing
- `motivo_nulo_id` solo es requerido cuando el estado es 'perdido'

#### Relaciones
- Pertenece a un Tenant (belongsTo)
- Pertenece a un Curso (belongsTo)
- Pertenece a una Fase de Venta (belongsTo)
- Pertenece a un Origen (belongsTo)
- Pertenece a una Sede (belongsTo)
- Pertenece a una Modalidad (belongsTo)
- Pertenece a una Provincia (belongsTo)
- Pertenece a un Asesor/Usuario (belongsTo)
- Puede tener un Motivo Nulo (belongsTo, nullable)
- Tiene un Contacto (hasOne)
- Tiene muchas Notas (hasMany LeadNotes)
- Tiene muchos Eventos/Acciones (hasMany LeadEvents)

### 3. Resource: Contactos (Contacts)

#### Campos Principales
```php
- id: bigint (PK, auto-increment)
- tenant_id: bigint (FK, índice)
- lead_id: bigint (FK, único)
- nombre_completo: string(255)
- telefono_principal: string(20)
- telefono_secundario: string(20) (nullable)
- email_principal: string(255)
- email_secundario: string(255) (nullable)
- direccion: text (nullable)
- ciudad: string(100) (nullable)
- codigo_postal: string(10) (nullable)
- provincia_id: bigint (FK, nullable)
- fecha_nacimiento: date (nullable)
- dni_nie: string(20) (nullable)
- profesion: string(100) (nullable)
- empresa: string(150) (nullable)
- notas_contacto: text (nullable)
- preferencia_comunicacion: enum('email', 'telefono', 'whatsapp', 'sms')
- created_at: timestamp
- updated_at: timestamp
- deleted_at: timestamp (soft delete)
```

#### Reglas de Negocio
- Un contacto pertenece a un único lead
- `email_principal` debe ser válido
- `telefono_principal` es obligatorio
- `dni_nie` debe ser único por tenant si se proporciona
- Los campos secundarios son opcionales para flexibilidad

#### Relaciones
- Pertenece a un Tenant (belongsTo)
- Pertenece a un Lead (belongsTo)

### 4. Resource: Notas de Lead (LeadNotes)

#### Campos Principales
```php
- id: bigint (PK, auto-increment)
- tenant_id: bigint (FK, índice)
- lead_id: bigint (FK)
- usuario_id: bigint (FK) // Usuario que creó la nota
- titulo: string(255) (nullable)
- contenido: text
- tipo: enum('llamada', 'email', 'reunion', 'seguimiento', 'observacion', 'otro')
- es_importante: boolean (default false)
- fecha_seguimiento: datetime (nullable) // Para programar recordatorios
- created_at: timestamp
- updated_at: timestamp
- deleted_at: timestamp (soft delete)
```

#### Reglas de Negocio
- El `contenido` es obligatorio
- `usuario_id` registra quién creó la nota para auditoría
- `tipo` permite categorizar las interacciones
- `es_importante` permite marcar notas críticas
- `fecha_seguimiento` opcional para programar recordatorios
- Implementar soft delete para mantener historial

#### Relaciones
- Pertenece a un Tenant (belongsTo)
- Pertenece a un Lead (belongsTo)
- Pertenece a un Usuario (belongsTo)

### 5. Resource: Eventos/Acciones (LeadEvents)

#### Campos Principales
```php
- id: bigint (PK, auto-increment)
- tenant_id: bigint (FK, índice)
- lead_id: bigint (FK)
- usuario_id: bigint (FK) // Usuario asignado a la acción
- titulo: string(255)
- descripcion: text (nullable)
- tipo: enum('llamada', 'email', 'reunion', 'whatsapp', 'visita', 'seguimiento', 'otro')
- estado: enum('pendiente', 'en_progreso', 'completada', 'cancelada')
- prioridad: enum('baja', 'media', 'alta', 'urgente')
- fecha_programada: datetime
- fecha_completada: datetime (nullable)
- duracion_estimada: integer (nullable) // En minutos
- resultado: text (nullable) // Resultado de la acción completada
- requiere_recordatorio: boolean (default true)
- minutos_recordatorio: integer (default 15) // Minutos antes para recordatorio
- created_at: timestamp
- updated_at: timestamp
- deleted_at: timestamp (soft delete)
```

#### Reglas de Negocio
- El `titulo` y `fecha_programada` son obligatorios
- `usuario_id` puede ser diferente al creador para asignar tareas
- `estado` se actualiza automáticamente según el flujo de trabajo
- `fecha_completada` se establece automáticamente al marcar como completada
- `resultado` es obligatorio cuando el estado es 'completada'
- Los recordatorios se envían según `minutos_recordatorio`

#### Relaciones
- Pertenece a un Tenant (belongsTo)
- Pertenece a un Lead (belongsTo)
- Pertenece a un Usuario Asignado (belongsTo)

## Resources de Configuración por Tenant

### 6. Resource: Áreas (Areas)

#### Campos
```php
- id: bigint (PK)
- tenant_id: bigint (FK)
- nombre: string(100)
- descripcion: text (nullable)
- codigo: string(20) (único por tenant)
- activo: boolean (default true)
- created_at: timestamp
- updated_at: timestamp
```

#### Reglas de Negocio
- El `codigo` debe ser único dentro del tenant
- El `nombre` es obligatorio
- Implementar soft delete opcional

### 7. Resource: Unidades de Negocio (BusinessUnits)

#### Campos
```php
- id: bigint (PK)
- tenant_id: bigint (FK)
- nombre: string(100)
- descripcion: text (nullable)
- codigo: string(20) (único por tenant)
- responsable: string(255) (nullable)
- activo: boolean (default true)
- created_at: timestamp
- updated_at: timestamp
```

#### Reglas de Negocio
- El `codigo` debe ser único dentro del tenant
- El `nombre` es obligatorio
- El `responsable` es opcional

### 8. Resource: Duraciones (Durations)

#### Campos
```php
- id: bigint (PK)
- tenant_id: bigint (FK)
- nombre: string(50) (ej: "6 meses", "1 año", "300 horas")
- descripcion: text (nullable)
- horas_totales: integer (nullable)
- tipo: enum('horas', 'dias', 'semanas', 'meses', 'años')
- valor_numerico: integer (nullable)
- activo: boolean (default true)
- created_at: timestamp
- updated_at: timestamp
```

#### Reglas de Negocio
- El `nombre` debe ser único dentro del tenant
- Si se especifica `tipo` y `valor_numerico`, calcular `horas_totales` automáticamente
- Permitir duraciones personalizadas en formato texto

### 9. Resource: Sedes (Campuses)

#### Campos
```php
- id: bigint (PK)
- tenant_id: bigint (FK)
- nombre: string(100)
- codigo: string(20) (único por tenant)
- direccion: text (nullable)
- ciudad: string(100) (nullable)
- codigo_postal: string(10) (nullable)
- telefono: string(20) (nullable)
- email: string(255) (nullable)
- responsable: string(255) (nullable)
- activo: boolean (default true)
- created_at: timestamp
- updated_at: timestamp
```

#### Reglas de Negocio
- El `codigo` debe ser único dentro del tenant
- El `nombre` es obligatorio
- Los campos de contacto son opcionales

### 10. Resource: Modalidades (Modalities)

#### Campos
```php
- id: bigint (PK)
- tenant_id: bigint (FK)
- nombre: string(50) (ej: "Presencial", "Online", "Híbrida")
- descripcion: text (nullable)
- codigo: string(10) (único por tenant)
- requiere_sede: boolean (default true)
- activo: boolean (default true)
- created_at: timestamp
- updated_at: timestamp
```

#### Reglas de Negocio
- El `codigo` debe ser único dentro del tenant
- El `nombre` es obligatorio
- `requiere_sede` indica si la modalidad necesita una sede física

### 11. Resource: Provincias (Provinces)

#### Campos
```php
- id: bigint (PK)
- tenant_id: bigint (FK)
- nombre: string(100)
- codigo: string(10) (único por tenant)
- codigo_ine: string(5) (nullable) // Código INE oficial
- comunidad_autonoma: string(100) (nullable)
- activo: boolean (default true)
- created_at: timestamp
- updated_at: timestamp
```

#### Reglas de Negocio
- El `codigo` debe ser único dentro del tenant
- El `nombre` es obligatorio
- `codigo_ine` opcional para integración con sistemas oficiales

### 12. Resource: Fases de Venta (SalesPhases)

#### Campos
```php
- id: bigint (PK)
- tenant_id: bigint (FK)
- nombre: string(100)
- descripcion: text (nullable)
- orden: integer
- color: string(7) (hex color)
- activo: boolean (default true)
- created_at: timestamp
- updated_at: timestamp
```

### 13. Resource: Motivos Nulos (NullReasons)

#### Campos
```php
- id: bigint (PK)
- tenant_id: bigint (FK)
- nombre: string(100)
- descripcion: text (nullable)
- activo: boolean (default true)
- created_at: timestamp
- updated_at: timestamp
```

### 14. Resource: Orígenes (Origins)

#### Campos
```php
- id: bigint (PK)
- tenant_id: bigint (FK)
- nombre: string(100)
- descripcion: text (nullable)
- tipo: enum('web', 'telefono', 'email', 'redes_sociales', 'referido', 'evento', 'publicidad', 'otro')
- activo: boolean (default true)
- created_at: timestamp
- updated_at: timestamp
```

### 15. Resource: Configuración General (TenantSettings)

#### Campos
```php
- id: bigint (PK)
- tenant_id: bigint (FK, único)
- configuracion: json
- created_at: timestamp
- updated_at: timestamp
```

#### Estructura JSON de Configuración
```json
{
  "empresa": {
    "nombre": "string",
    "logo": "string",
    "colores_corporativos": ["#color1", "#color2"]
  },
  "notificaciones": {
    "email_nuevos_leads": true,
    "sms_seguimiento": false,
    "webhook_integraciones": "url"
  },
  "campos_personalizados": {
    "leads": [
      {
        "nombre": "campo_custom",
        "tipo": "string|number|date|select",
        "opciones": ["valor1", "valor2"],
        "requerido": false
      }
    ]
  }
}
```

## Filament Resources - Estructura del Panel

### Organización del Panel de Administración

#### **Sección Principal - CRM**
- **Dashboard** - Métricas y widgets principales
- **Leads** - Gestión de leads educativos
- **Contactos** - Información de contacto de leads
- **Cursos** - Catálogo de cursos disponibles
- **Notas de Lead** - Historial de interacciones
- **Eventos/Acciones** - Programación y seguimiento de tareas

#### **Sección Configuración/Settings** (Sub-sidebar)
Todos los recursos de catálogo agrupados en una sección dedicada:

**📋 Catálogos Académicos**
- **Áreas** - Áreas de estudio
- **Unidades de Negocio** - Departamentos/divisiones
- **Duraciones** - Tipos de duración de cursos

**🏢 Catálogos Operativos**
- **Sedes** - Campus y ubicaciones
- **Modalidades** - Tipos de modalidad educativa
- **Provincias** - Ubicaciones geográficas

**📊 Catálogos de Ventas**
- **Fases de Venta** - Estados del proceso de venta
- **Motivos Nulos** - Razones de pérdida de leads
- **Orígenes** - Fuentes de captación de leads

**⚙️ Configuración General**
- **Configuración del Tenant** - Settings globales JSON

### Implementación Técnica Filament

#### Estructura de Navegación
```php
// En el Panel Provider
NavigationGroup::make('CRM')
    ->items([
        NavigationItem::make('Dashboard'),
        NavigationItem::make('Leads'),
        NavigationItem::make('Contactos'),
        NavigationItem::make('Cursos'),
        NavigationItem::make('Notas de Lead'),
        NavigationItem::make('Eventos/Acciones'),
    ]),

NavigationGroup::make('Configuración')
    ->collapsed() // Colapsado por defecto
    ->items([
        // Catálogos Académicos
        NavigationGroup::make('Catálogos Académicos')
            ->items([
                NavigationItem::make('Áreas'),
                NavigationItem::make('Unidades de Negocio'),
                NavigationItem::make('Duraciones'),
            ]),
        
        // Catálogos Operativos
        NavigationGroup::make('Catálogos Operativos')
            ->items([
                NavigationItem::make('Sedes'),
                NavigationItem::make('Modalidades'),
                NavigationItem::make('Provincias'),
            ]),
        
        // Catálogos de Ventas
        NavigationGroup::make('Catálogos de Ventas')
            ->items([
                NavigationItem::make('Fases de Venta'),
                NavigationItem::make('Motivos Nulos'),
                NavigationItem::make('Orígenes'),
            ]),
        
        // Configuración General
        NavigationItem::make('Configuración General'),
    ])
```

### Características de cada Resource

#### **Resources Principales (CRM)**
- **Listado**: Tabla con filtros por tenant automático
- **Formulario**: Validación de campos y reglas de negocio
- **Acciones**: Crear, editar, eliminar (soft delete)
- **Permisos**: Basado en roles por tenant
- **Exportación**: Excel/CSV de datos filtrados por tenant
- **Búsqueda global**: Integrada en el panel principal

#### **Resources de Configuración (Settings)**
- **Listado simplificado**: Tabla básica con nombre, código, activo
- **Formulario compacto**: Campos esenciales únicamente
- **Acciones básicas**: Crear, editar, activar/desactivar
- **Permisos restrictivos**: Solo admins del tenant
- **Sin exportación**: No necesaria para catálogos
- **Validación estricta**: Códigos únicos por tenant

### Widgets Dashboard
- **Métricas de leads** por estado y fase de venta
- **Conversión por curso** y área académica
- **Rendimiento por asesor** y sede
- **Gráficos de tendencias** temporales y geográficas
- **Alertas de seguimiento** basadas en notas programadas

## Integraciones Futuras

### APIs Externas
- Sistemas de gestión académica
- Plataformas de marketing (HubSpot, Mailchimp)
- Servicios de comunicación (WhatsApp Business)
- Analytics (Google Analytics, Facebook Pixel)

### Webhooks
- Notificaciones de nuevos leads
- Cambios de estado
- Actualizaciones de contacto

## Consideraciones Técnicas

### Performance
- Índices en `tenant_id` para todas las tablas
- Cache de configuraciones por tenant
- Paginación en listados grandes

### Seguridad
- Middleware de tenant en todas las rutas
- Validación de pertenencia en queries
- Logs de auditoría por tenant

### Backup & Recovery
- Backup por tenant individual
- Restauración selectiva de datos
