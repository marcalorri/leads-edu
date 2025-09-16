---
trigger: always_on
---

# Leads-Edu - Stack Tecnológico

## Descripción del Proyecto
**Leads-Edu** es una aplicación SaaS educativa construida sobre SaaSykit, un starter kit completo para proyectos SaaS que acelera el desarrollo y lanzamiento de productos.

## Stack Tecnológico Principal

### Backend Framework
- **Laravel 12.x** - Framework PHP moderno y robusto
- **PHP 8.2+** - Lenguaje de programación backend

### Frontend & UI
- **Filament PHP 4.x** - Panel de administración moderno y potente
- **Livewire 3.5+** - Componentes dinámicos full-stack para Laravel
- **Alpine.js 3.13+** - Framework JavaScript ligero para interactividad
- **Tailwind CSS 4.x** - Framework CSS utility-first
- **DaisyUI 5.x** - Componentes UI para Tailwind CSS

### SaaS & Multi-tenancy
- **SaaSykit Tenancy** - Sistema de multi-tenancy y gestión SaaS
- **SaaSykit Components**:
  - `saasykit/filament-country-field` - Campo de países para Filament
  - `saasykit/laravel-invoices` - Sistema de facturación
  - `saasykit/laravel-money` - Manejo de monedas y dinero
  - `saasykit/laravel-open-graphy` - Integración con Open Graph
  - `saasykit/laravel-recaptcha` - Protección reCAPTCHA
  - `saasykit/filament-breezy` - Autenticación mejorada para Filament

### Base de Datos
- **MySQL** - Base de datos principal
- **Redis** - Cache y sesiones

### Autenticación & Seguridad
- **Laravel Sanctum 4.x** - Autenticación API
- **Laravel Socialite 5.6+** - Login social (OAuth)
- **Laragear Two-Factor 3.x** - Autenticación de dos factores
- **Spatie Laravel Permission 6.x** - Sistema de roles y permisos

### Pagos & Facturación
- **Stripe PHP 17.x** - Procesamiento de pagos
- **Laravel Invoices** - Generación de facturas

### Comunicaciones
- **Resend PHP** - Servicio de email transaccional
- **Twilio SDK 8.3+** - SMS y comunicaciones
- **Symfony Mailers** - Integración con Mailgun y Postmark

### Herramientas de Desarrollo
- **Vite 7.x** - Build tool y bundler
- **Laravel Sail** - Entorno de desarrollo con Docker
- **Laravel Telescope** - Debugging y profiling
- **Laravel Horizon** - Gestión de colas
- **Deployer 7.3** - Herramienta de despliegue

### Testing & Calidad de Código
- **PHPUnit 11.x** - Testing framework
- **Larastan 3.x** - Análisis estático de código
- **Laravel Pint** - Code styling
- **Laravel Debugbar** - Debugging en desarrollo

### Utilidades Adicionales
- **Spatie Packages**:
  - `laravel-cookie-consent` - Gestión de cookies
  - `laravel-flash` - Mensajes flash
  - `laravel-sitemap` - Generación de sitemaps
- **Intervention Image 3.4+** - Manipulación de imágenes
- **AWS SDK PHP** - Integración con servicios AWS
- **Guzzle HTTP** - Cliente HTTP

### Build & Assets
- **Node.js** - Runtime para herramientas frontend
- **npm/Vite** - Gestión de dependencias y build
- **Autoprefixer** - Post-procesamiento CSS
- **Highlight.js** - Resaltado de sintaxis

## Arquitectura del Proyecto

### Estructura Multi-tenant
El proyecto utiliza SaaSykit Tenancy para manejar múltiples inquilinos (tenants), permitiendo que cada organización educativa tenga su propio espacio aislado.

### Panel de Administración
Filament PHP proporciona un panel de administración completo con:
- Gestión de usuarios y roles
- Dashboard con métricas
- CRUD automático para modelos
- Sistema de notificaciones

### API & Frontend
- API RESTful con Laravel Sanctum
- Componentes Livewire para interactividad
- Alpine.js para funcionalidades JavaScript ligeras

## Funcionalidades (Próximas a documentar)
- Sistema de gestión de leads educativos
- Dashboard de métricas y analytics
- Gestión de campañas de marketing
- Sistema de facturación y pagos
- Notificaciones multi-canal (email, SMS)
- Integración con CRM
- Reportes y exportación de datos
