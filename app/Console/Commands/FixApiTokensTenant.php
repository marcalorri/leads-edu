<?php

namespace App\Console\Commands;

use App\Models\ApiToken;
use App\Models\Tenant;
use Illuminate\Console\Command;

class FixApiTokensTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:fix-tokens-tenant';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Asigna tenant_id a tokens API que no lo tienen';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Buscando tokens sin tenant_id...');

        $tokensWithoutTenant = ApiToken::whereNull('tenant_id')->get();

        if ($tokensWithoutTenant->isEmpty()) {
            $this->info('No hay tokens sin tenant_id.');
            return 0;
        }

        $this->info("Encontrados {$tokensWithoutTenant->count()} tokens sin tenant_id.");

        foreach ($tokensWithoutTenant as $token) {
            $user = $token->tokenable;
            
            if (!$user) {
                $this->warn("Token ID {$token->id}: Usuario no encontrado. Saltando...");
                continue;
            }

            // Obtener el primer tenant del usuario
            $tenant = $user->tenants()->first();

            if (!$tenant) {
                $this->warn("Token ID {$token->id}: Usuario '{$user->name}' no tiene tenants. Saltando...");
                continue;
            }

            // Actualizar el token
            $token->tenant_id = $tenant->id;
            $token->save();

            $this->info("✓ Token ID {$token->id} ('{$token->name}') asignado al tenant '{$tenant->name}'");
        }

        $this->info('Proceso completado.');
        return 0;
    }
}
