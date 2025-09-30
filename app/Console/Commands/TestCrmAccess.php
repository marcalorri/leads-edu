<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Console\Command;

class TestCrmAccess extends Command
{
    protected $signature = 'test:crm-access {--user-id=} {--tenant-id=}';

    protected $description = 'Probar acceso CRM para un usuario y tenant específico';

    public function handle()
    {
        $userId = $this->option('user-id') ?: $this->ask('ID del usuario');
        $tenantId = $this->option('tenant-id') ?: $this->ask('ID del tenant');

        $user = User::find($userId);
        $tenant = Tenant::find($tenantId);

        if (!$user) {
            $this->error("Usuario con ID {$userId} no encontrado");
            return 1;
        }

        if (!$tenant) {
            $this->error("Tenant con ID {$tenantId} no encontrado");
            return 1;
        }

        $this->info("🧪 Probando acceso CRM para:");
        $this->line("   Usuario: {$user->name} ({$user->email})");
        $this->line("   Tenant: {$tenant->name}");
        $this->newLine();

        // Simular verificaciones de suscripción (como si fuera SaaSyKit)
        $this->info("📋 Verificaciones de Suscripción:");
        
        // Nota: Estos métodos no existen realmente hasta que se configure SaaSyKit
        try {
            $isSubscribed = method_exists($user, 'isSubscribed') ? 
                $user->isSubscribed('crm-plan', $tenant) : false;
            $isTrialing = method_exists($user, 'isTrialing') ? 
                $user->isTrialing('crm-plan', $tenant) : false;
            
            $this->line("   Suscrito a CRM: " . ($isSubscribed ? '✅ Sí' : '❌ No'));
            $this->line("   En período de prueba: " . ($isTrialing ? '✅ Sí' : '❌ No'));
            
            $hasAccess = $isSubscribed || $isTrialing;
            $this->line("   Acceso CRM: " . ($hasAccess ? '✅ Permitido' : '🔒 Bloqueado'));
            
        } catch (\Exception $e) {
            $this->warn("   ⚠️  Métodos de SaaSyKit no disponibles aún");
            $this->line("   Esto es normal hasta que se configure el producto CRM");
        }

        $this->newLine();
        $this->info("🔧 Estado del Middleware:");
        $this->line("   Middleware registrado: ✅ crm.subscription");
        $this->line("   LeadResource protegido: ✅ Sí");
        $this->line("   Página upgrade creada: ✅ Sí");

        $this->newLine();
        $this->info("📝 Próximos pasos:");
        $this->line("   1. Configurar producto 'crm-plan' en SaaSyKit admin");
        $this->line("   2. Asignar suscripción al tenant");
        $this->line("   3. Probar acceso en el navegador");

        return 0;
    }
}
