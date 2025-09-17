---
trigger: always_on
---

# Leads-Edu CRM - Estructura del Panel Filament

## Organización del Panel

### Sección Principal - CRM
- **Dashboard** - Métricas y widgets principales
- **Leads** - Gestión de leads educativos
- **Contactos** - Información de contacto de leads
- **Cursos** - Catálogo de cursos disponibles
- **Notas de Lead** - Historial de interacciones
- **Eventos/Acciones** - Programación y seguimiento de tareas

### Sección Configuración (Sub-sidebar colapsado)

#### 📋 Catálogos Académicos
- **Áreas** - Áreas de estudio
- **Unidades de Negocio** - Departamentos/divisiones
- **Duraciones** - Tipos de duración de cursos

#### 🏢 Catálogos Operativos
- **Sedes** - Campus y ubicaciones
- **Modalidades** - Tipos de modalidad educativa
- **Provincias** - Ubicaciones geográficas

#### 📊 Catálogos de Ventas
- **Fases de Venta** - Estados del proceso de venta
- **Motivos Nulos** - Razones de pérdida de leads
- **Orígenes** - Fuentes de captación de leads

#### ⚙️ Configuración General
- **Configuración del Tenant** - Settings globales JSON

## Código de Navegación

```php
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
    ->collapsed()
    ->items([
        NavigationGroup::make('Catálogos Académicos')
            ->items([
                NavigationItem::make('Áreas'),
                NavigationItem::make('Unidades de Negocio'),
                NavigationItem::make('Duraciones'),
            ]),
        NavigationGroup::make('Catálogos Operativos')
            ->items([
                NavigationItem::make('Sedes'),
                NavigationItem::make('Modalidades'),
                NavigationItem::make('Provincias'),
            ]),
        NavigationGroup::make('Catálogos de Ventas')
            ->items([
                NavigationItem::make('Fases de Venta'),
                NavigationItem::make('Motivos Nulos'),
                NavigationItem::make('Orígenes'),
            ]),
        NavigationItem::make('Configuración General'),
    ])
```

## Características por Tipo de Resource

### Resources Principales (CRM)
- Listado completo con filtros avanzados
- Formularios con validaciones complejas
- Exportación Excel/CSV
- Búsqueda global integrada
- Permisos basados en roles

### Resources de Configuración (Settings)
- Listados simplificados (nombre, código, activo)
- Formularios compactos
- Permisos restrictivos (solo admins)
- Validación estricta de códigos únicos por tenant

## Widgets Dashboard
- Métricas de leads por estado y fase de venta
- Conversión por curso y área académica
- Rendimiento por asesor y sede
- Gráficos de tendencias temporales y geográficas
- Alertas de seguimiento basadas en notas programadas
