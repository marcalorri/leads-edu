# Location Seeders

Seeders modulares para poblar provincias/estados de múltiples países.

## 📁 Estructura

```
Locations/
├── README.md                    # Este archivo
├── SpainProvincesSeeder.php     # 52 provincias españolas
├── LatinAmericaSeeder.php       # 13 países latinoamericanos
├── EuropeSeeder.php             # 5 países europeos
├── NorthAmericaSeeder.php       # USA y Canadá
```

## 🌍 Cobertura

### España (52 provincias)
- Todas las comunidades autónomas
- Códigos INE oficiales
- Códigos de provincia

### América Latina (13 países)
- 🇲🇽 México - 32 estados
- 🇨🇴 Colombia - 33 departamentos
- 🇦🇷 Argentina - 24 provincias
- 🇨🇱 Chile - 16 regiones
- 🇵🇪 Perú - 25 departamentos
- 🇪🇨 Ecuador - 24 provincias
- 🇻🇪 Venezuela - 24 estados
- 🇧🇴 Bolivia - 9 departamentos
- 🇵🇾 Paraguay - 18 departamentos
- 🇺🇾 Uruguay - 19 departamentos
- 🇨🇷 Costa Rica - 7 provincias
- 🇵🇦 Panamá - 10 provincias
- 🇧🇷 Brasil - 27 estados

### Europa (5 países)
- 🇵🇹 Portugal - 20 distritos
- 🇫🇷 Francia - 13 regiones
- 🇮🇹 Italia - 20 regiones
- 🇩🇪 Alemania - 16 estados
- 🇬🇧 Reino Unido - 4 regiones

### América del Norte (2 países)
- 🇺🇸 Estados Unidos - 51 estados
- 🇨🇦 Canadá - 13 provincias/territorios

## 🚀 Uso

### Ejecutar todos los seeders

```bash
php artisan db:seed --class=GlobalLocationsSeeder
```

### Ejecutar para un tenant específico

```bash
php artisan db:seed --class=GlobalLocationsSeeder
# Cuando pregunte, introduce el tenant_id
```

### Ejecutar un seeder individual

```bash
# Solo España
php artisan db:seed --class=Database\\Seeders\\Locations\\SpainProvincesSeeder

# Solo América Latina
php artisan db:seed --class=Database\\Seeders\\Locations\\LatinAmericaSeeder

# Solo Europa
php artisan db:seed --class=Database\\Seeders\\Locations\\EuropeSeeder

# Solo Norte América
php artisan db:seed --class=Database\\Seeders\\Locations\\NorthAmericaSeeder
```

## 📊 Estadísticas

| Región | Países | Ubicaciones |
|--------|--------|-------------|
| España | 1 | 52 |
| América Latina | 13 | ~280 |
| Europa | 5 | ~90 |
| América del Norte | 2 | ~64 |
| **TOTAL** | **22** | **~550** |

## 🔧 Agregar Más Países

1. Edita el seeder correspondiente (ej: `LatinAmericaSeeder.php`)
2. Agrega un método `protected function seedPais(Tenant $tenant): void`
3. Llama al método desde `run()`
4. Ejecuta el seeder

Ejemplo:

```php
protected function seedNuevoPais(Tenant $tenant): void
{
    $provinces = [
        ['codigo' => 'XX', 'nombre' => 'Provincia 1'],
        ['codigo' => 'YY', 'nombre' => 'Provincia 2'],
    ];
    
    foreach ($provinces as $prov) {
        Province::updateOrCreate(
            ['tenant_id' => $tenant->id, 'codigo' => $prov['codigo'], 'comunidad_autonoma' => 'Nuevo País'],
            ['nombre' => $prov['nombre'], 'activo' => true]
        );
    }
}
```

## ✨ Características

- ✅ **Modular** - Cada región en su propio archivo
- ✅ **Idempotente** - Usa `updateOrCreate()` para evitar duplicados
- ✅ **Multi-tenant** - Aislamiento completo por tenant
- ✅ **Extensible** - Fácil agregar nuevos países
- ✅ **Mantenible** - Código limpio y organizado

## 🔍 Verificación

Después de ejecutar los seeders, verifica:

```bash
php artisan test:province-normalization {tenant_id}
```

Este comando prueba el sistema de normalización con las provincias cargadas.
