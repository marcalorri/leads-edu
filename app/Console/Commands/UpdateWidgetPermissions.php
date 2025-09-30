<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class UpdateWidgetPermissions extends Command
{
    protected $signature = 'widgets:update-permissions';

    protected $description = 'Actualizar widgets para usar global scopes en lugar de filtros manuales';

    public function handle()
    {
        $widgetPath = app_path('Filament/Dashboard/Widgets');
        $files = File::glob($widgetPath . '/*Widget.php');

        $oldPattern = '        // Filtrar por usuario si no es admin
        $query = Lead::query();
        if (!$user->isAdmin()) {
            $query->where(\'asesor_id\', $user->id);
        }';

        $newPattern = '        // Los global scopes ya manejan el filtrado automáticamente
        $query = Lead::query();';

        $updatedFiles = [];

        foreach ($files as $file) {
            $content = File::get($file);
            
            if (strpos($content, 'if (!$user->isAdmin())') !== false) {
                $newContent = str_replace($oldPattern, $newPattern, $content);
                
                // También remover la línea $user = auth()->user(); si no se usa para otra cosa
                if (strpos($newContent, '$user->') === false && strpos($newContent, '$user = auth()->user();') !== false) {
                    $newContent = str_replace('        $user = auth()->user();', '', $newContent);
                    $newContent = str_replace('    $user = auth()->user();', '', $newContent);
                }
                
                File::put($file, $newContent);
                $updatedFiles[] = basename($file);
            }
        }

        if (empty($updatedFiles)) {
            $this->info('No se encontraron widgets para actualizar.');
        } else {
            $this->info('Widgets actualizados:');
            foreach ($updatedFiles as $file) {
                $this->line("  - {$file}");
            }
        }

        return 0;
    }
}
