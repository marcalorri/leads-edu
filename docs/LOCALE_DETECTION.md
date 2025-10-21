# Detección Automática de Idioma del Navegador

## Descripción
El sistema detecta automáticamente el idioma preferido del navegador del usuario y configura el locale de Laravel en consecuencia.

## Componentes Implementados

### 1. Middleware: `SetLocaleFromBrowser`
**Ubicación**: `app/Http/Middleware/SetLocaleFromBrowser.php`

**Funcionalidad**:
- Lee el header `Accept-Language` del navegador
- Verifica si el idioma está en la lista de idiomas disponibles
- Prioriza el idioma guardado en sesión sobre el del navegador
- Establece el locale de Laravel automáticamente

**Orden de Prioridad**:
1. Idioma guardado en sesión (si el usuario lo cambió manualmente)
2. Idioma del navegador (detectado automáticamente)
3. Idioma por defecto (`en`)

### 2. Controlador: `LocaleController`
**Ubicación**: `app/Http/Controllers/LocaleController.php`

**Funcionalidad**:
- Permite cambiar manualmente el idioma
- Guarda la preferencia en sesión
- Valida que el idioma sea válido

### 3. Configuración
**Ubicación**: `config/app.php`

```php
'available_locales' => ['en', 'es'],
```

Esta configuración centraliza los idiomas disponibles en la aplicación.

### 4. Ruta de Cambio de Idioma
**Ubicación**: `routes/web.php`

```php
Route::get('/locale/{locale}', [LocaleController::class, 'change'])->name('locale.change');
```

### 5. Componente Blade: Selector de Idioma
**Ubicación**: `resources/views/components/language-switcher.blade.php`

Selector de idioma integrado en el navbar principal (`resources/views/components/layouts/app/header.blade.php`).
También puede agregarse en cualquier otra vista si es necesario.

## Uso

### Detección Automática
El middleware está registrado en el grupo `web`, por lo que funciona automáticamente en todas las rutas web sin necesidad de configuración adicional.

### Cambio Manual de Idioma
El selector de idioma ya está integrado en el navbar principal de la aplicación.

Los usuarios pueden:
1. **Usar el selector en el navbar** (esquina superior derecha)
2. **Visitar directamente las URLs**:
   - `/locale/en` - Cambiar a inglés
   - `/locale/es` - Cambiar a español

## UI/UX
- Selector de idioma integrado en el navbar (esquina superior derecha)
- Estilo: DaisyUI select-sm con fondo semi-transparente para integración visual
- Muestra solo códigos ISO (EN, ES)

### Agregar el Selector en Otras Vistas
Si necesitas agregar el selector en otras ubicaciones (footer, sidebar, etc.):

```blade
<x-language-switcher />
```

## Agregar Nuevos Idiomas

### Paso 1: Agregar el idioma a la configuración
Edita `config/app.php`:

```php
'available_locales' => ['en', 'es', 'fr', 'de'],
```

### Paso 2: Crear archivo de traducciones
Crea el archivo de traducciones correspondiente:
- Para francés: `lang/fr.json`
- Para alemán: `lang/de.json`

### Paso 3: Actualizar el componente language-switcher
Agrega la opción en `resources/views/components/language-switcher.blade.php`:

```blade
<option value="{{ route('locale.change', 'fr') }}" {{ app()->getLocale() === 'fr' ? 'selected' : '' }}>
    FR
</option>
```

## Cómo Funciona

### Flujo de Detección
1. Usuario visita la aplicación
2. Middleware `SetLocaleFromBrowser` se ejecuta
3. Verifica si hay un idioma guardado en sesión
4. Si no hay idioma en sesión, lee el header `Accept-Language` del navegador
5. Compara con los idiomas disponibles en `config/app.php`
6. Establece el locale de Laravel con `App::setLocale()`
7. Todas las traducciones usan `__()` respetan este locale

### Persistencia
- El idioma detectado del navegador NO se guarda en sesión (se detecta en cada request)
- Solo cuando el usuario cambia manualmente el idioma se guarda en sesión
- La preferencia manual tiene prioridad sobre la detección automática

## Testing

### Probar Detección Automática
1. Cambia el idioma preferido en tu navegador:
   - Chrome: Settings > Languages
   - Firefox: Preferences > Language
2. Visita la aplicación
3. Verifica que el idioma se haya detectado correctamente

### Probar Cambio Manual
1. Visita `/locale/es`
2. Verifica que el idioma cambió a español
3. Cierra y abre el navegador
4. El idioma debería seguir siendo español (guardado en sesión)

## Consideraciones

### Multi-Tenant
Este sistema funciona correctamente con multi-tenancy. Cada tenant puede tener sus propias traducciones en `lang/es.json`.

### API
El middleware solo se aplica al grupo `web`. Las rutas API no detectan el idioma del navegador automáticamente. Para APIs, considera:
- Usar el header `Accept-Language` en el request
- Pasar el idioma como parámetro en la URL o body
- Usar el idioma del usuario autenticado

### Performance
- La detección es muy rápida (< 1ms)
- No hay queries a base de datos
- Solo lee headers HTTP y sesión

## Troubleshooting

### El idioma no cambia
1. Verifica que el middleware esté registrado en `bootstrap/app.php`
2. Limpia la cache de configuración: `php artisan config:clear`
3. Verifica que el archivo de traducciones existe: `lang/es.json`

### El idioma del navegador no se detecta
1. Verifica que el navegador envía el header `Accept-Language`
2. Verifica que el idioma está en `config/app.php` > `available_locales`
3. Revisa los logs de Laravel para errores

### El cambio manual no persiste
1. Verifica que las sesiones funcionan correctamente
2. Verifica que el driver de sesión está configurado (`.env` > `SESSION_DRIVER`)
3. Limpia las sesiones: `php artisan session:clear`
