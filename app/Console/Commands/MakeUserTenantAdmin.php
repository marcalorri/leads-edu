<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Console\Command;

class MakeUserTenantAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-tenant-admin {user_id} {tenant_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Asigna rol de admin a un usuario en un tenant específico';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $tenantId = $this->argument('tenant_id');

        $user = User::find($userId);
        if (!$user) {
            $this->error("Usuario con ID {$userId} no encontrado.");
            return 1;
        }

        $tenant = Tenant::find($tenantId);
        if (!$tenant) {
            $this->error("Tenant con ID {$tenantId} no encontrado.");
            return 1;
        }

        // Verificar si el usuario pertenece al tenant
        $tenantUser = $user->tenants()->where('tenant_id', $tenant->id)->first();
        
        if (!$tenantUser) {
            $this->error("El usuario '{$user->name}' no pertenece al tenant '{$tenant->name}'.");
            return 1;
        }

        // Asignar rol de admin
        $pivot = $tenantUser->pivot;
        
        if ($pivot->hasRole(\App\Constants\TenancyPermissionConstants::ROLE_ADMIN)) {
            $this->info("El usuario '{$user->name}' ya es admin del tenant '{$tenant->name}'.");
            return 0;
        }

        $pivot->assignRole(\App\Constants\TenancyPermissionConstants::ROLE_ADMIN);

        $this->info("✓ Usuario '{$user->name}' (ID: {$user->id}) es ahora admin del tenant '{$tenant->name}' (ID: {$tenant->id})");
        
        return 0;
    }
}
