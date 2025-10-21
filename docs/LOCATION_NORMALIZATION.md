# Sistema de Normalización de Ubicaciones

## 📋 Descripción

Sistema inteligente para manejar datos de ubicación (provincias, países) provenientes de fuentes externas (API, importadores CSV, formularios) con normalización automática y fuzzy matching.

## 🎯 Problema que Resuelve

Cuando recibes datos de ubicación de fuentes externas, pueden venir en diferentes formatos:
- Con/sin acentos: `Málaga` vs `Malaga`
- Variantes regionales: `Alicante` vs `Alacant`
- Errores tipográficos: `Madrd` vs `Madrid`
- Códigos: `M` vs `Madrid`
- Nombres completos: `A Coruña` vs `La Coruña` vs `Coruña`

Este sistema resuelve automáticamente todas estas variantes al ID correcto.

## 🚀 Componentes

### 1. LocationNormalizerService

Servicio principal que maneja la normalización:

```php
use App\Services\LocationNormalizerService;

$normalizer = app(LocationNormalizerService::class);
$province = $normalizer->resolveProvince('Malaga', $tenant, $createIfNotFound);
```

### 2. Seeder de Provincias Españolas

Pre-pobla la base de datos con todas las provincias españolas:

```bash
php artisan db:seed --class=SpanishProvincesSeeder
```

### 3. Comando de Testing

Prueba la normalización con diferentes inputs:

```bash
php artisan test:province-normalization {tenant_id}
```

## 📊 Estrategia de Resolución

El sistema intenta resolver en este orden:

1. **Coincidencia exacta** por nombre (normalizado, sin acentos)
2. **Código exacto** (ej: `M` → Madrid)
3. **Código INE** (ej: `28` → Madrid)
4. **Fuzzy matching** con variantes comunes
5. **Levenshtein distance** (distancia de edición < 3)
6. **Crear nuevo** (si está habilitado)

## ⚙️ Configuración

### Variables de Entorno (.env)

```env
# Crear automáticamente provincias no encontradas
AUTO_CREATE_PROVINCES=false

# Crear automáticamente países no encontrados
AUTO_CREATE_COUNTRIES=false

# Umbral de distancia de Levenshtein (0-10)
FUZZY_MATCH_THRESHOLD=3
```

### Configuración en config/app.php

```php
'auto_create_provinces' => env('AUTO_CREATE_PROVINCES', false),
'auto_create_countries' => env('AUTO_CREATE_COUNTRIES', false),
'fuzzy_match_threshold' => env('FUZZY_MATCH_THRESHOLD', 3),
```

## 🔧 Uso en la API

El sistema ya está integrado en `LeadStoreRequest`:

```json
{
  "nombre": "Juan",
  "provincia_id": "Malaga"  // Se resuelve automáticamente a ID
}
```

Acepta:
- IDs numéricos: `28`
- Nombres: `Madrid`, `madrid`, `MADRID`
- Con/sin acentos: `Málaga`, `Malaga`
- Variantes: `Alicante`, `Alacant`
- Códigos: `M`, `B`, `V`

## 📝 Ejemplos de Uso

### Ejemplo 1: Resolver Provincia

```php
use App\Services\LocationNormalizerService;

$normalizer = app(LocationNormalizerService::class);

// Todas estas variantes resuelven a la misma provincia
$province = $normalizer->resolveProvince('Málaga', $tenant);
$province = $normalizer->resolveProvince('Malaga', $tenant);
$province = $normalizer->resolveProvince('malaga', $tenant);
$province = $normalizer->resolveProvince('MA', $tenant);
```

### Ejemplo 2: Con Creación Automática

```php
// Si la provincia no existe, la crea
$province = $normalizer->resolveProvince(
    'Nueva Provincia',
    $tenant,
    true  // createIfNotFound
);
```

### Ejemplo 3: Obtener Estadísticas

```php
$inputs = ['Madrid', 'Madrd', 'Malaga', 'Atlantis'];
$stats = $normalizer->getMatchStatistics($inputs, $tenant);

// Resultado:
// [
//     'total' => 4,
//     'exact_matches' => 2,
//     'fuzzy_matches' => 1,
//     'not_found' => 1,
//     'would_create' => 1
// ]
```

## 🧪 Testing

### Comando de Test

```bash
php artisan test:province-normalization 1
```

Salida ejemplo:
```
+----------------+-------+------------------+----------+
| Input          | Found | Province Name    | Method   |
+----------------+-------+------------------+----------+
| Madrid         | ✓     | Madrid           | Exact    |
| Málaga         | ✓     | Málaga           | Exact    |
| Malaga         | ✓     | Málaga           | Fuzzy    |
| Alacant        | ✓     | Alicante         | Fuzzy    |
| Madrd          | ✓     | Madrid           | Fuzzy    |
| M              | ✓     | Madrid           | Code     |
| Atlantis       | ✗     | -                | Not found|
+----------------+-------+------------------+----------+

Statistics:
Total inputs: 7
Exact matches: 2
Fuzzy matches: 4
Not found: 1
```

## 📚 Variantes Comunes Soportadas

El sistema reconoce automáticamente estas variantes:

| Estándar | Variantes |
|----------|-----------|
| Alicante | Alacant |
| Castellón | Castelló, Castello |
| Valencia | València |
| Vizcaya | Bizkaia |
| Guipúzcoa | Gipuzkoa |
| Álava | Araba |
| Navarra | Nafarroa |
| A Coruña | La Coruña, Coruña |
| Orense | Ourense |
| Baleares | Illes Balears, Islas Baleares |

## 🔐 Seguridad Multi-Tenant

- ✅ Todas las búsquedas filtran por `tenant_id`
- ✅ No se pueden resolver provincias de otros tenants
- ✅ La creación automática respeta el tenant actual

## 🎨 Normalización de Strings

El servicio normaliza automáticamente:
- ✅ Convierte a minúsculas
- ✅ Remueve acentos (á → a, é → e, etc.)
- ✅ Elimina espacios extras
- ✅ Trim de espacios

## 📦 Seeder de Provincias

### Ejecutar para Todos los Tenants

```bash
php artisan db:seed --class=SpanishProvincesSeeder
```

### Ejecutar para un Tenant Específico

```bash
php artisan db:seed --class=SpanishProvincesSeeder
# Cuando pregunte, introduce el tenant_id
```

### Provincias Incluidas

52 provincias españolas con:
- Código INE oficial
- Código de provincia
- Nombre oficial
- Comunidad autónoma

## 🚨 Recomendaciones

### Para Producción

1. **Pre-poblar provincias**: Ejecuta el seeder antes de lanzar
2. **Deshabilitar auto-create**: `AUTO_CREATE_PROVINCES=false`
3. **Monitorear no encontrados**: Revisa logs de provincias no resueltas
4. **Validar importaciones**: Usa el comando de test antes de importar

### Para Desarrollo

1. **Habilitar auto-create**: `AUTO_CREATE_PROVINCES=true`
2. **Probar variantes**: Usa el comando de test
3. **Revisar estadísticas**: Analiza qué tan bien funciona el matching

## 🔄 Flujo Recomendado

```
1. Seeder → Pre-poblar provincias estándar
2. API/Import → Recibe datos con variantes
3. Normalizer → Resuelve automáticamente
4. Fallback → Crea nueva si está habilitado
5. Log → Registra provincias no encontradas
6. Review → Admin revisa y corrige manualmente
```

## 📈 Métricas de Éxito

Con este sistema deberías lograr:
- ✅ **>95% de coincidencias** automáticas
- ✅ **0 duplicados** por variantes
- ✅ **Datos limpios** y normalizados
- ✅ **UX mejorada** para usuarios

## 🛠️ Extensión Futura

Puedes extender el servicio para:
- Países (similar a provincias)
- Ciudades
- Códigos postales
- Otros catálogos personalizados

## 📞 Soporte

Para dudas o problemas, revisa:
1. Los logs de Laravel
2. El comando de test
3. Las estadísticas de matching
