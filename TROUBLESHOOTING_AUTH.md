# Troubleshooting: Error de Autenticación en Invitaciones y Registro

## Error
```
Illuminate\Validation\ValidationException: These credentials do not match our records.
```

## Diagnóstico

### 1. Verificar Estado de la Base de Datos

```bash
# Conectarse al servidor de producción
ssh usuario@leads-edu.com

# Verificar conexión a BD
php artisan tinker
>>> DB::connection()->getPdo();
>>> DB::table('users')->count();
```

### 2. Verificar Migraciones

```bash
# Ver estado de migraciones
php artisan migrate:status

# Si faltan migraciones, ejecutar
php artisan migrate --force
```

### 3. Verificar Configuración de APP_KEY

```bash
# En el servidor, verificar que APP_KEY esté configurada
grep APP_KEY .env

# Si está vacía, generar una nueva
php artisan key:generate --force
```

### 4. Verificar Hash de Contraseñas

El modelo User tiene un cast `'password' => 'hashed'` que puede causar doble hashing.

**Solución Temporal**: Crear un usuario de prueba directamente en BD

```bash
php artisan tinker
>>> $user = new \App\Models\User();
>>> $user->name = 'Test User';
>>> $user->email = 'test@example.com';
>>> $user->password = 'password123'; // El cast 'hashed' lo hasheará automáticamente
>>> $user->email_verified_at = now();
>>> $user->save();
>>> exit
```

Luego intentar login con:
- Email: test@example.com
- Password: password123

### 5. Verificar Logs de Producción

```bash
# Ver últimos errores
tail -n 100 storage/logs/laravel.log

# Buscar errores específicos de autenticación
grep -i "credentials" storage/logs/laravel.log
grep -i "authentication" storage/logs/laravel.log
```

### 6. Problema Específico: Doble Hashing

**Causa**: El cast `'password' => 'hashed'` en el modelo User + `Hash::make()` en UserService

**Archivo afectado**: `app/Models/User.php` línea 66

**Solución 1 (Recomendada)**: Remover el cast y usar mutator manual

```php
// En app/Models/User.php
protected $casts = [
    'email_verified_at' => 'datetime',
    'phone_number_verified_at' => 'datetime',
    // 'password' => 'hashed', // REMOVER ESTA LÍNEA
    'last_seen_at' => 'datetime',
];

// Agregar mutator manual
public function setPasswordAttribute($value)
{
    // Solo hashear si no está ya hasheado
    if (!empty($value) && !str_starts_with($value, '$2y$')) {
        $this->attributes['password'] = Hash::make($value);
    } else {
        $this->attributes['password'] = $value;
    }
}
```

**Solución 2**: Remover `Hash::make()` de UserService y confiar en el cast

```php
// En app/Services/UserService.php
return User::create([
    'name' => $data['name'],
    'email' => strtolower($data['email']),
    'password' => $data['password'], // El cast 'hashed' lo hasheará
]);
```

### 7. Verificar Flujo de Invitaciones

El flujo correcto debe ser:

1. Admin crea invitación → Se guarda en tabla `invitations`
2. Usuario recibe email con token
3. Usuario hace clic en enlace → Redirige a registro/login
4. **Si usuario NO existe**: Debe registrarse primero
5. **Si usuario existe**: Debe hacer login
6. Después de autenticado → Acepta invitación

**Verificar que el enlace de invitación incluya el token**:

```bash
php artisan tinker
>>> $invitation = \App\Models\Invitation::latest()->first();
>>> echo $invitation->token;
>>> echo route('invitations'); // Verificar ruta
```

### 8. Solución Rápida para Producción

Si necesitas una solución inmediata:

```bash
# 1. Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 2. Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Reiniciar servicios
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
```

## Checklist de Verificación

- [ ] Base de datos accesible y con datos
- [ ] Migraciones ejecutadas correctamente
- [ ] APP_KEY configurada en .env
- [ ] Usuario de prueba puede hacer login
- [ ] Logs no muestran errores de BD
- [ ] Cast de password configurado correctamente
- [ ] Hash de contraseñas funciona (sin doble hashing)
- [ ] Flujo de invitaciones completo funciona

## Comandos Útiles

```bash
# Ver configuración actual
php artisan config:show database
php artisan config:show auth

# Crear usuario admin de emergencia
php artisan app:create-admin-user

# Verificar permisos de archivos
ls -la storage/logs/
chmod -R 775 storage/
chown -R www-data:www-data storage/
```

## Contacto de Soporte

Si el problema persiste después de estas verificaciones, proporciona:
1. Contenido completo del error desde `storage/logs/laravel.log`
2. Resultado de `php artisan migrate:status`
3. Resultado de `php artisan config:show database`
4. Resultado de `DB::table('users')->count()` desde tinker
