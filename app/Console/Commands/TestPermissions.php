<?php

namespace App\Console\Commands;

use App\Constants\TenancyPermissionConstants;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class TestPermissions extends Command
{
    protected $signature = 'test:permissions {--user-id= : ID del usuario a probar}';

    protected $description = 'Probar el sistema de permisos de tenant';

    public function handle()
    {
        $userId = $this->option('user-id');
        
        if (!$userId) {
            $userId = $this->ask('ID del usuario a probar');
        }

        $user = User::find($userId);
        if (!$user) {
            $this->error("Usuario con ID {$userId} no encontrado");
            return 1;
        }

        $this->info("🧪 Probando permisos para: {$user->name} ({$user->email})");
        $this->newLine();

        // Simular login del usuario
        Auth::login($user);

        // Obtener tenant
        $tenant = Tenant::find(1);
        if (!$tenant) {
            $this->error('Tenant con ID 1 no encontrado');
            return 1;
        }

        // Simular contexto de tenant
        app()->instance('filament.tenant', $tenant);

        $this->info("📋 Información del Usuario:");
        $this->line("- Es Admin Global: " . ($user->isAdmin() ? '✅ Sí' : '❌ No'));
        $this->line("- Es Admin del Tenant: " . ($user->isTenantAdmin($tenant) ? '✅ Sí' : '❌ No'));
        $this->newLine();

        $this->info("🔐 Permisos CRM:");
        $permissions = [
            'Ver todos los leads' => $user->canViewAllLeads($tenant),
            'Ver todos los contactos' => $user->canViewAllContacts($tenant),
            'Gestionar configuración' => $user->canManageConfiguration($tenant),
            'Ver estadísticas dashboard' => $user->canViewDashboardStats($tenant),
        ];

        foreach ($permissions as $permission => $hasPermission) {
            $this->line("- {$permission}: " . ($hasPermission ? '✅ Sí' : '❌ No'));
        }

        $this->newLine();

        $this->info("📊 Permisos Específicos de Spatie:");
        $spatiePermissions = [
            TenancyPermissionConstants::PERMISSION_VIEW_ALL_LEADS,
            TenancyPermissionConstants::PERMISSION_CREATE_LEADS,
            TenancyPermissionConstants::PERMISSION_MANAGE_CONFIGURATION,
            TenancyPermissionConstants::PERMISSION_VIEW_DASHBOARD_STATS,
        ];

        foreach ($spatiePermissions as $permission) {
            try {
                $hasPermission = $user->hasPermissionTo($permission);
                $this->line("- {$permission}: " . ($hasPermission ? '✅ Sí' : '❌ No'));
            } catch (\Exception $e) {
                $this->line("- {$permission}: ❌ Error - " . $e->getMessage());
            }
        }

        $this->newLine();

        $this->info("👥 Roles del Usuario en este Tenant:");
        $roles = $user->roles()
            ->where('is_tenant_role', true)
            ->get();
            
        $this->info("👥 Todos los Roles del Usuario:");
        $allRoles = $user->roles()->get();

        if ($roles->isEmpty()) {
            $this->warn('- Sin roles de tenant asignados');
        } else {
            foreach ($roles as $role) {
                $this->line("- {$role->name} (tenant_id: {$role->tenant_id})");
            }
        }

        if ($allRoles->isEmpty()) {
            $this->warn('- Sin roles globales asignados');
        } else {
            foreach ($allRoles as $role) {
                $this->line("- {$role->name} (tenant_id: {$role->tenant_id}, is_tenant_role: " . ($role->is_tenant_role ? 'Sí' : 'No') . ")");
            }
        }

        return 0;
    }
}
