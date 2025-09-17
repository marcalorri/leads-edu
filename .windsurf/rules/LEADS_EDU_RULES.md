---
trigger: always_on
---

# Leads-Edu CRM - Reglas del Proyecto

## Sistema
CRM educativo multi-tenant construido sobre SaaSykit Tenancy + Laravel 12.x + Filament 4.x

## Recursos Principales (5)
1. **Cursos** - Catálogo de cursos con FK a Areas, BusinessUnits, Durations
2. **Leads** - Gestión de leads con asesor_id FK, estado, fase_venta_id, tracking UTM
3. **Contactos** - Info detallada 1:1 con leads, provincia_id FK
4. **LeadNotes** - Historial de interacciones con auditoría usuario_id
5. **LeadEvents** - Tareas/acciones programadas con recordatorios

## Catálogos Configurables (10)
**Académicos**: Areas, BusinessUnits, Durations
**Operativos**: Campuses, Modalities, Provinces  
**Ventas**: SalesPhases, NullReasons, Origins
**General**: TenantSettings (JSON)

## Panel Filament
- **Sección CRM**: Dashboard, Leads, Contactos, Cursos, LeadNotes, LeadEvents
- **Sección Configuración** (colapsada): 3 grupos de catálogos + settings

## Multi-Tenancy
- Aislamiento total por tenant_id
- Códigos únicos por tenant en catálogos
- Middleware tenant en todas las rutas
- Permisos: Admin, Manager, Comercial

## Validaciones Clave
- email único por tenant en leads
- codigo_curso único por tenant
- dni_nie único por tenant si existe
- motivo_nulo_id requerido solo si estado='perdido'

## Stack Técnico
Laravel 12.x, Filament 4.x, SaaSykit Tenancy, MySQL, Redis, Livewire 3.5+, Alpine.js, Tailwind CSS 4.x

## Documentación Detallada
- `docs/DATABASE_SCHEMA.md` - Estructura completa de BD
- `docs/FILAMENT_STRUCTURE.md` - Organización del panel
- `docs/BUSINESS_RULES.md` - Reglas de negocio detalladas
- `IMPLEMENTATION_PLAN.md` - Plan de desarrollo 16 semanas
