<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SetGlobalAdmin extends Command
{
    protected $signature = 'user:set-global-admin {user-id} {is-admin : true o false}';

    protected $description = 'Cambiar el estado de admin global de un usuario';

    public function handle()
    {
        $userId = $this->argument('user-id');
        $isAdmin = $this->argument('is-admin') === 'true';

        $user = User::find($userId);
        if (!$user) {
            $this->error("Usuario con ID {$userId} no encontrado");
            return 1;
        }

        $oldStatus = $user->is_admin ? 'Admin Global' : 'Usuario Regular';
        $user->is_admin = $isAdmin;
        $user->save();
        $newStatus = $user->is_admin ? 'Admin Global' : 'Usuario Regular';

        $this->info("✅ Usuario {$user->name}:");
        $this->line("   Antes: {$oldStatus}");
        $this->line("   Ahora: {$newStatus}");

        return 0;
    }
}
