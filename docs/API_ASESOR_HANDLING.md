# API - Manejo del Campo `asesor_id`

## Comportamiento del Sistema

El campo `asesor_id` en la API de creación de leads tiene un comportamiento inteligente y flexible para adaptarse a diferentes escenarios.

## Escenarios Posibles

### 1. ✅ No se envía `asesor_id`
**Payload**:
```json
{
  "nombre": "Juan",
  "apellidos": "Pérez",
  "email": "juan@example.com",
  // ... otros campos
  // asesor_id NO está presente
}
```

**Comportamiento**:
- El lead se asigna automáticamente al usuario del token API
- Se registra un log informativo:
  ```
  Lead assigned to API token user
  - reason: not_provided
  - assigned_to_user_id: 2
  - assigned_to_user_name: "Admin User"
  ```

**Resultado**: ✅ Lead creado y asignado al usuario del token

---

### 2. ✅ Se envía `asesor_id` como `null` o string vacío
**Payload**:
```json
{
  "nombre": "Juan",
  "apellidos": "Pérez",
  "email": "juan@example.com",
  "asesor_id": null  // o ""
}
```

**Comportamiento**:
- El campo se limpia automáticamente a `null` en `prepareForValidation()`
- El lead se asigna automáticamente al usuario del token API
- Se registra un log informativo:
  ```
  Lead assigned to API token user
  - reason: not_provided
  ```

**Resultado**: ✅ Lead creado y asignado al usuario del token

---

### 3. ✅ Se envía `asesor_id` numérico válido
**Payload**:
```json
{
  "nombre": "Juan",
  "apellidos": "Pérez",
  "email": "juan@example.com",
  "asesor_id": 5
}
```

**Comportamiento**:
- Se valida que el usuario con ID 5 exista
- Se valida que el usuario pertenezca al tenant
- Si es válido, se asigna ese asesor

**Resultado**: ✅ Lead creado y asignado al asesor especificado

---

### 4. ❌ Se envía `asesor_id` numérico inválido
**Payload**:
```json
{
  "nombre": "Juan",
  "apellidos": "Pérez",
  "email": "juan@example.com",
  "asesor_id": 99999  // No existe o no pertenece al tenant
}
```

**Comportamiento**:
- La validación `Rule::exists()` falla
- Se devuelve error de validación

**Respuesta**:
```json
{
  "error": {
    "code": "VALIDATION_FAILED",
    "message": "The provided data is not valid",
    "details": {
      "asesor_id": ["El asesor seleccionado no existe o no pertenece a tu organización."]
    }
  }
}
```

**Resultado**: ❌ Error 422 - Lead NO creado

---

### 5. ✅ Se envía `asesor_id` como email (texto) válido
**Payload**:
```json
{
  "nombre": "Juan",
  "apellidos": "Pérez",
  "email": "juan@example.com",
  "asesor_id": "maria@empresa.com"
}
```

**Comportamiento**:
- El sistema busca un usuario con ese email en el tenant
- Si lo encuentra, convierte el email al ID numérico
- Asigna el lead a ese asesor

**Resultado**: ✅ Lead creado y asignado al asesor encontrado por email

---

### 6. ✅ Se envía `asesor_id` como nombre (texto) válido
**Payload**:
```json
{
  "nombre": "Juan",
  "apellidos": "Pérez",
  "email": "juan@example.com",
  "asesor_id": "María García"
}
```

**Comportamiento**:
- El sistema busca un usuario con ese nombre (búsqueda parcial con LIKE) en el tenant
- Si lo encuentra, convierte el nombre al ID numérico
- Asigna el lead a ese asesor

**Resultado**: ✅ Lead creado y asignado al asesor encontrado por nombre

---

### 7. ✅ Se envía `asesor_id` como texto que NO existe
**Payload**:
```json
{
  "nombre": "Juan",
  "apellidos": "Pérez",
  "email": "juan@example.com",
  "asesor_id": "asesor.inexistente@empresa.com"
}
```

**Comportamiento**:
- El sistema busca el asesor por email → No lo encuentra
- El sistema busca el asesor por nombre → No lo encuentra
- Establece `asesor_id` como `null`
- El controlador asigna automáticamente al usuario del token API
- Se registra un log de advertencia:
  ```
  WARNING: Asesor no encontrado en API
  - identifier: "asesor.inexistente@empresa.com"
  - tenant_id: 4
  - will_assign_to_token_user: true
  ```
- Se registra un log informativo:
  ```
  Lead assigned to API token user
  - reason: not_found
  - assigned_to_user_id: 2
  ```

**Resultado**: ✅ Lead creado y asignado al usuario del token (con advertencia en logs)

---

## Resumen de Comportamiento

| Valor de `asesor_id` | Acción | Resultado |
|---------------------|--------|-----------|
| No enviado | Asignar a usuario del token | ✅ Éxito |
| `null` o `""` | Asignar a usuario del token | ✅ Éxito |
| ID numérico válido | Asignar al asesor especificado | ✅ Éxito |
| ID numérico inválido | Error de validación | ❌ Error 422 |
| Email válido (texto) | Buscar y asignar al asesor | ✅ Éxito |
| Nombre válido (texto) | Buscar y asignar al asesor | ✅ Éxito |
| Email/nombre inválido (texto) | Asignar a usuario del token + log warning | ✅ Éxito (con advertencia) |

## Ventajas de este Enfoque

### 1. **Flexibilidad**
- Acepta IDs numéricos, emails o nombres
- No requiere que el cliente conozca los IDs internos

### 2. **Robustez**
- Nunca falla por un asesor inexistente
- Siempre asigna el lead a alguien (usuario del token como fallback)

### 3. **Trazabilidad**
- Logs claros de cuándo y por qué se asigna al usuario del token
- Advertencias cuando no se encuentra un asesor especificado

### 4. **Compatibilidad con n8n**
- Si el campo viene vacío de tu fuente de datos, funciona
- Si viene con un nombre que no existe, funciona
- Si viene con un ID válido, funciona

## Recomendaciones para n8n

### Opción 1: Enviar ID numérico (más eficiente)
```json
{
  "asesor_id": 5
}
```

### Opción 2: Enviar email del asesor
```json
{
  "asesor_id": "maria@empresa.com"
}
```

### Opción 3: Enviar nombre del asesor
```json
{
  "asesor_id": "María García"
}
```

### Opción 4: No enviar nada (usar usuario del token)
```json
{
  // asesor_id no está presente
}
```

### Opción 5: Manejar valores vacíos en n8n
```javascript
{{ $('Definir campos').item.json.Asesor || null }}
```

## Monitoreo

Para revisar cuándo se están asignando leads al usuario del token por asesor no encontrado:

```bash
# Ver logs de advertencia
tail -f storage/logs/laravel.log | grep "Asesor no encontrado"

# Ver logs de asignación automática
tail -f storage/logs/laravel.log | grep "Lead assigned to API token user"
```

## Ejemplo de Log Completo

```
[2025-11-18 18:00:00] production.WARNING: Asesor no encontrado en API
{
  "identifier": "asesor.inexistente@empresa.com",
  "tenant_id": 4,
  "will_assign_to_token_user": true
}

[2025-11-18 18:00:00] production.INFO: Lead assigned to API token user
{
  "lead_email": "juan@example.com",
  "assigned_to_user_id": 2,
  "assigned_to_user_name": "Admin User",
  "tenant_id": 4,
  "reason": "not_found"
}
```
