<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateTestCSV extends Command
{
    protected $signature = 'generate:test-csv {--path=public/test_leads.csv}';
    protected $description = 'Generate a test CSV file for lead import';

    public function handle()
    {
        $path = $this->option('path');
        $fullPath = base_path($path);
        
        $csvContent = $this->generateCSVContent();
        
        // Crear directorio si no existe
        $directory = dirname($fullPath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        file_put_contents($fullPath, $csvContent);
        
        $this->info("✅ CSV file generated at: $fullPath");
        $this->info("📁 You can now download this file and use it in the Filament import panel");
        $this->line("");
        $this->line("Content preview:");
        $this->line(substr($csvContent, 0, 300) . '...');
        $this->line("");
        $this->info("🔗 Access the file at: http://localhost/test_leads.csv");
    }
    
    private function generateCSVContent(): string
    {
        $header = 'nombre,apellidos,telefono,email,estado';
        
        $rows = [
            'Ana,Martínez González,666111222,ana.martinez@test.com,abierto',
            'Carlos,López Ruiz,677333444,carlos.lopez@test.com,abierto',
            'Elena,Fernández Castro,688555666,elena.fernandez@test.com,abierto',
            'David,Sánchez Moreno,699777888,david.sanchez@test.com,abierto',
            'Laura,García Jiménez,655999000,laura.garcia@test.com,abierto',
            'Miguel,Rodríguez Pérez,644888111,miguel.rodriguez@test.com,abierto',
            'Carmen,Ruiz Martín,633777222,carmen.ruiz@test.com,abierto',
            'Javier,González López,622666333,javier.gonzalez@test.com,abierto',
            'Isabel,Hernández García,611555444,isabel.hernandez@test.com,abierto',
            'Antonio,Díaz Fernández,600444555,antonio.diaz@test.com,abierto'
        ];
        
        return $header . "\n" . implode("\n", $rows);
    }
}
