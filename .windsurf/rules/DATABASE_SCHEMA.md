---
trigger: always_on
description: Any action related with data base
---

# Leads-Edu CRM - Esquema de Base de Datos

## Recursos Principales

### 1. Cursos (courses)
```sql
- id: bigint PK
- tenant_id: bigint FK
- codigo_curso: string(50) UNIQUE per tenant
- titulacion: string(255)
- area_id: bigint FK
- unidad_negocio_id: bigint FK
- duracion_id: bigint FK
- timestamps, soft_deletes
```

### 2. Leads (leads)
```sql
- id: bigint PK
- tenant_id: bigint FK
- asesor_id: bigint FK (users)
- estado: enum('nuevo', 'contactado', 'interesado', 'matriculado', 'perdido')
- fase_venta_id: bigint FK
- curso_id: bigint FK
- sede_id: bigint FK
- modalidad_id: bigint FK
- provincia_id: bigint FK
- nombre: string(100)
- apellidos: string(150)
- telefono: string(20)
- email: string(255) UNIQUE per tenant
- motivo_nulo_id: bigint FK nullable
- origen_id: bigint FK
- convocatoria: string(100)
- horario: string(100)
- utm_source: string(255) nullable
- utm_medium: string(255) nullable
- utm_campaign: string(255) nullable
- timestamps, soft_deletes
```

### 3. Contactos (contacts)
```sql
- id: bigint PK
- tenant_id: bigint FK
- lead_id: bigint FK UNIQUE
- nombre_completo: string(255)
- telefono_principal: string(20)
- telefono_secundario: string(20) nullable
- email_principal: string(255)
- email_secundario: string(255) nullable
- direccion: text nullable
- ciudad: string(100) nullable
- codigo_postal: string(10) nullable
- provincia_id: bigint FK nullable
- fecha_nacimiento: date nullable
- dni_nie: string(20) nullable UNIQUE per tenant
- profesion: string(100) nullable
- empresa: string(150) nullable
- notas_contacto: text nullable
- preferencia_comunicacion: enum('email', 'telefono', 'whatsapp', 'sms')
- timestamps, soft_deletes
```

### 4. Notas de Lead (lead_notes)
```sql
- id: bigint PK
- tenant_id: bigint FK
- lead_id: bigint FK
- usuario_id: bigint FK
- titulo: string(255) nullable
- contenido: text
- tipo: enum('llamada', 'email', 'reunion', 'seguimiento', 'observacion', 'otro')
- es_importante: boolean default false
- fecha_seguimiento: datetime nullable
- timestamps, soft_deletes
```

### 5. Eventos/Acciones (lead_events)
```sql
- id: bigint PK
- tenant_id: bigint FK
- lead_id: bigint FK
- usuario_id: bigint FK
- titulo: string(255)
- descripcion: text nullable
- tipo: enum('llamada', 'email', 'reunion', 'whatsapp', 'visita', 'seguimiento', 'otro')
- estado: enum('pendiente', 'en_progreso', 'completada', 'cancelada')
- prioridad: enum('baja', 'media', 'alta', 'urgente')
- fecha_programada: datetime
- fecha_completada: datetime nullable
- duracion_estimada: integer nullable
- resultado: text nullable
- requiere_recordatorio: boolean default true
- minutos_recordatorio: integer default 15
- timestamps, soft_deletes
```

## Catálogos de Configuración

### Académicos
- **areas**: id, tenant_id, nombre, descripcion, codigo UNIQUE per tenant, activo
- **business_units**: id, tenant_id, nombre, descripcion, codigo UNIQUE per tenant, responsable, activo
- **durations**: id, tenant_id, nombre, descripcion, horas_totales, tipo, valor_numerico, activo

### Operativos
- **campuses**: id, tenant_id, nombre, codigo UNIQUE per tenant, direccion, ciudad, codigo_postal, telefono, email, responsable, activo
- **modalities**: id, tenant_id, nombre, descripcion, codigo UNIQUE per tenant, requiere_sede, activo
- **provinces**: id, tenant_id, nombre, codigo UNIQUE per tenant, codigo_ine, comunidad_autonoma, activo

### Ventas
- **sales_phases**: id, tenant_id, nombre, descripcion, orden, color, activo
- **null_reasons**: id, tenant_id, nombre, descripcion, activo
- **origins**: id, tenant_id, nombre, descripcion, tipo, activo

### General
- **tenant_settings**: id, tenant_id UNIQUE, configuracion JSON

## Índices Principales
- Todos los campos `tenant_id` tienen índice
- Campos únicos por tenant: codigo_curso, email (leads), dni_nie
- Foreign keys con índices automáticos