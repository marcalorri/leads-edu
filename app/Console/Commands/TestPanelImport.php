<?php

namespace App\Console\Commands;

use App\Filament\Dashboard\Imports\LeadImporter;
use App\Models\Lead;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Filament\Actions\Imports\Models\Import;

class TestPanelImport extends Command
{
    protected $signature = 'test:panel-import {--tenant-id=1}';
    protected $description = 'Test Lead Import simulating panel context';

    public function handle()
    {
        $this->info('🧪 Testing Panel Import Context...');
        
        // Test 1: Verificar tenant
        $tenantId = $this->option('tenant-id');
        $tenant = Tenant::find($tenantId);
        
        if (!$tenant) {
            $this->error("❌ Tenant with ID $tenantId not found");
            return;
        }
        
        $this->info("✅ Using tenant: {$tenant->name} (ID: {$tenant->id})");
        
        // Test 2: Simular contexto de Filament
        app()->instance('filament.tenant', $tenant);
        
        // Test 3: Verificar usuario
        $user = User::first();
        if (!$user) {
            $this->error('❌ No users found');
            return;
        }
        
        \Illuminate\Support\Facades\Auth::login($user);
        $this->info("✅ Authenticated as: {$user->name}");
        
        // Test 4: Crear CSV de prueba
        $csvContent = $this->createTestCSV();
        $csvPath = 'panel_test_leads.csv';
        Storage::disk('local')->put($csvPath, $csvContent);
        $fullPath = storage_path('app/' . $csvPath);
        
        $this->info("✅ CSV created at: $fullPath");
        $this->line("Content preview:");
        $this->line(substr($csvContent, 0, 150) . '...');
        
        // Test 5: Probar columnas del importador
        try {
            $this->info('5. Testing LeadImporter columns...');
            $columns = LeadImporter::getColumns();
            $this->info("✅ Found " . count($columns) . " columns");
            
            // Mostrar primeras 5 columnas
            $columnNames = array_map(fn($col) => $col->getName(), array_slice($columns, 0, 5));
            $this->info('First 5 columns: ' . implode(', ', $columnNames));
            
        } catch (\Exception $e) {
            $this->error('❌ Error loading columns: ' . $e->getMessage());
            return;
        }
        
        // Test 6: Simular proceso de importación
        $this->info('6. Testing import process...');
        
        try {
            $testData = [
                'nombre' => 'Test Panel',
                'apellidos' => 'Import User',
                'telefono' => '666999888',
                'email' => 'panel.test@example.com',
            ];
            
            $this->info('Test data: ' . json_encode($testData));
            
            // Simular el proceso del importador
            // Crear un mock de Import para el constructor
            $import = new Import();
            $import->id = 1;
            $import->file_path = 'test.csv';
            
            $importer = new LeadImporter($import, [], []);
            
            // Usar reflection para acceder a la propiedad protegida
            $reflection = new \ReflectionClass($importer);
            $dataProperty = $reflection->getProperty('data');
            $dataProperty->setAccessible(true);
            $dataProperty->setValue($importer, $testData);
            
            // Ejecutar beforeFill
            $beforeFillMethod = $reflection->getMethod('beforeFill');
            $beforeFillMethod->setAccessible(true);
            $beforeFillMethod->invoke($importer);
            
            $processedData = $dataProperty->getValue($importer);
            $this->info('Processed data: ' . json_encode($processedData));
            
            // Crear lead con los datos procesados
            $lead = new Lead();
            $lead->fill($processedData);
            $lead->save();
            
            $this->info("✅ Lead created successfully with ID: {$lead->id}");
            
        } catch (\Exception $e) {
            $this->error('❌ Error during import simulation: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
        
        $this->info('🎉 Panel import test completed!');
    }
    
    private function createTestCSV(): string
    {
        $header = 'nombre,apellidos,telefono,email,estado';
        $rows = [
            'Juan Panel,García Test,666111222,juan.panel@example.com,abierto',
            'María Panel,López Test,677333444,maria.panel@example.com,abierto',
            'Carlos Panel,Ruiz Test,688555666,carlos.panel@example.com,abierto'
        ];
        
        return $header . "\n" . implode("\n", $rows);
    }
}
