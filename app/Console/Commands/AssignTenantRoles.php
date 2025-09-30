<?php

namespace App\Console\Commands;

use App\Constants\TenancyPermissionConstants;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Console\Command;

class AssignTenantRoles extends Command
{
    protected $signature = 'tenant:assign-roles {--tenant-id= : ID del tenant} {--user-id= : ID del usuario} {--role=user : Rol a asignar (admin|user)}';

    protected $description = 'Asignar roles de tenant a usuarios existentes';

    public function handle()
    {
        $tenantId = $this->option('tenant-id');
        $userId = $this->option('user-id');
        $roleName = $this->option('role');

        if (!$tenantId) {
            $tenantId = $this->ask('ID del tenant');
        }

        $tenant = Tenant::find($tenantId);
        if (!$tenant) {
            $this->error("Tenant con ID {$tenantId} no encontrado");
            return 1;
        }

        $this->info("Trabajando con tenant: {$tenant->name} (ID: {$tenant->id})");

        // Si no se especifica usuario, mostrar todos los usuarios del tenant
        if (!$userId) {
            $users = $tenant->users;
            
            if ($users->isEmpty()) {
                $this->warn('No hay usuarios en este tenant');
                return 0;
            }

            $this->table(
                ['ID', 'Nombre', 'Email', 'Rol Actual'],
                $users->map(function ($user) use ($tenant) {
                    $currentRole = $user->roles()
                        ->where('tenant_id', $tenant->id)
                        ->where('is_tenant_role', true)
                        ->first();
                    
                    return [
                        $user->id,
                        $user->name,
                        $user->email,
                        $currentRole ? $currentRole->name : 'Sin rol'
                    ];
                })
            );

            $userId = $this->ask('ID del usuario al que asignar rol');
        }

        $user = User::find($userId);
        if (!$user) {
            $this->error("Usuario con ID {$userId} no encontrado");
            return 1;
        }

        // Verificar que el usuario pertenece al tenant
        if (!$user->tenants()->where('tenant_id', $tenant->id)->exists()) {
            $this->error("El usuario {$user->name} no pertenece al tenant {$tenant->name}");
            return 1;
        }

        // Obtener el rol
        $roleConstant = $roleName === 'admin' 
            ? TenancyPermissionConstants::ROLE_ADMIN 
            : TenancyPermissionConstants::ROLE_USER;

        $role = Role::where('name', $roleConstant)
            ->where('is_tenant_role', true)
            ->first();

        if (!$role) {
            $this->error("Rol {$roleConstant} no encontrado");
            return 1;
        }

        // Remover roles anteriores del tenant
        $user->roles()
            ->where('tenant_id', $tenant->id)
            ->where('is_tenant_role', true)
            ->detach();

        // Asignar nuevo rol con tenant_id
        $user->assignRole($role->name);
        
        // Actualizar el pivot para incluir tenant_id
        $pivotRecord = $user->roles()->where('role_id', $role->id)->first();
        if ($pivotRecord) {
            $user->roles()->updateExistingPivot($role->id, ['tenant_id' => $tenant->id]);
        }

        $this->info("✅ Rol '{$role->name}' asignado a {$user->name} en tenant {$tenant->name}");

        return 0;
    }
}
