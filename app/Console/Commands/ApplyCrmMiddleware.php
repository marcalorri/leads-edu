<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ApplyCrmMiddleware extends Command
{
    protected $signature = 'crm:apply-middleware';

    protected $description = 'Aplicar middleware de suscripción CRM a todos los recursos CRM';

    protected array $crmResources = [
        'ContactResource',
        'CourseResource', 
        'LeadNoteResource',
        'LeadEventResource',
    ];

    public function handle()
    {
        $this->info('Aplicando middleware CRM a recursos...');

        foreach ($this->crmResources as $resourceName) {
            $this->applyMiddlewareToResource($resourceName);
        }

        $this->info('✅ Middleware aplicado a todos los recursos CRM');
        return 0;
    }

    protected function applyMiddlewareToResource(string $resourceName): void
    {
        $resourcePath = $this->findResourceFile($resourceName);
        
        if (!$resourcePath) {
            $this->warn("⚠️  No se encontró el archivo para {$resourceName}");
            return;
        }

        $content = File::get($resourcePath);

        // Verificar si ya tiene el middleware
        if (strpos($content, 'getRouteMiddleware') !== false) {
            $this->line("   {$resourceName} ya tiene middleware configurado");
            return;
        }

        // Buscar donde insertar el método
        $insertAfter = 'public static function canCreate(): bool
    {
        return auth()->check();
    }';

        $middlewareMethod = '
    public static function getRouteMiddleware(\Filament\Panel $panel): string|array
    {
        return [
            \'crm.subscription\',
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        $tenant = filament()->getTenant();
        
        if (!$tenant || !$user) {
            return false;
        }
        
        // Admins globales siempre ven la navegación
        if ($user->is_admin) {
            return true;
        }
        
        // Solo mostrar en navegación si tiene suscripción CRM
        return $user->isSubscribed(\'crm-plan\', $tenant) || 
               $user->isTrialing(\'crm-plan\', $tenant);
    }';

        if (strpos($content, $insertAfter) !== false) {
            $newContent = str_replace($insertAfter, $insertAfter . $middlewareMethod, $content);
            File::put($resourcePath, $newContent);
            $this->line("   ✅ {$resourceName} actualizado");
        } else {
            $this->warn("   ⚠️  No se pudo actualizar {$resourceName} - estructura no encontrada");
        }
    }

    protected function findResourceFile(string $resourceName): ?string
    {
        $possiblePaths = [
            app_path("Filament/Dashboard/Resources/{$resourceName}.php"),
            app_path("Filament/Dashboard/Resources/*/{$resourceName}.php"),
        ];

        foreach ($possiblePaths as $pattern) {
            $files = glob($pattern);
            if (!empty($files)) {
                return $files[0];
            }
        }

        // Buscar recursivamente
        $files = File::allFiles(app_path('Filament/Dashboard/Resources'));
        foreach ($files as $file) {
            if ($file->getFilename() === $resourceName . '.php') {
                return $file->getPathname();
            }
        }

        return null;
    }
}
