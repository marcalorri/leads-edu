<?php

namespace App\Console\Commands;

use App\Filament\Dashboard\Imports\LeadImporter;
use App\Models\Lead;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class TestImport extends Command
{
    protected $signature = 'test:import';
    protected $description = 'Test lead import manually';

    public function handle()
    {
        $this->info('Testing Lead Import...');
        
        // Test 1: Verificar que el importador se puede cargar
        try {
            $this->info('1. Testing LeadImporter class loading...');
            $columns = LeadImporter::getColumns();
            $this->info('✅ LeadImporter loaded successfully. Found ' . count($columns) . ' columns.');
            
            // Mostrar las primeras columnas
            $columnNames = array_map(fn($col) => $col->getName(), array_slice($columns, 0, 5));
            $this->info('First 5 columns: ' . implode(', ', $columnNames));
            
        } catch (\Exception $e) {
            $this->error('❌ Error loading LeadImporter: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return;
        }
        
        // Test 2: Verificar datos de prueba mínimos
        $this->info('2. Testing manual lead creation...');
        
        try {
            $testData = [
                'nombre' => 'Test Manual',
                'apellidos' => 'Apellido Test',
                'telefono' => '123456789',
                'email' => 'test.manual.unique' . time() . '@example.com', // Email único
                'estado' => 'abierto',
            ];
            
            $this->info('Datos de prueba: ' . json_encode($testData));
            
            // Crear lead manualmente con campos mínimos
            $lead = new Lead();
            $lead->tenant_id = 1; // Asumiendo tenant ID 1
            $lead->nombre = $testData['nombre'];
            $lead->apellidos = $testData['apellidos'];
            $lead->telefono = $testData['telefono'];
            $lead->email = $testData['email'];
            $lead->estado = $testData['estado'];
            
            // Verificar si el usuario existe
            $user = \App\Models\User::first();
            if ($user) {
                $lead->asesor_id = $user->id;
                $this->info('Asignando asesor ID: ' . $user->id);
            } else {
                $this->warn('No hay usuarios en la base de datos');
            }
            
            $lead->save();
            
            $this->info('✅ Lead creado exitosamente con ID: ' . $lead->id);
            
        } catch (\Exception $e) {
            $this->error('❌ Error al crear lead: ' . $e->getMessage());
            
            // Mostrar información más detallada del error
            if (str_contains($e->getMessage(), 'foreign key constraint')) {
                $this->error('🔍 Error de foreign key detectado. Verificando constraints...');
                
                // Verificar qué foreign keys pueden estar causando problemas
                try {
                    $userCount = \App\Models\User::count();
                    $tenantCount = \App\Models\Tenant::count();
                    $this->info("Users: $userCount, Tenants: $tenantCount");
                } catch (\Exception $e2) {
                    $this->error('Error verificando relaciones: ' . $e2->getMessage());
                }
            }
        }
        
        // Test 3: Verificar total de leads
        try {
            $totalLeads = Lead::count();
            $this->info('3. Total de leads en la base de datos: ' . $totalLeads);
        } catch (\Exception $e) {
            $this->error('❌ Error al contar leads: ' . $e->getMessage());
        }
        
        // Test 4: Verificar que las relaciones existen
        $this->info('4. Testing required relationships...');
        try {
            $provincesCount = \App\Models\Province::count();
            $coursesCount = \App\Models\Course::count();
            $campusesCount = \App\Models\Campus::count();
            
            $this->info("Provinces: $provincesCount, Courses: $coursesCount, Campuses: $campusesCount");
            
            if ($provincesCount == 0 || $coursesCount == 0 || $campusesCount == 0) {
                $this->warn('⚠️  Some required relationships are empty. Import may fail.');
            } else {
                $this->info('✅ Required relationships have data.');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Error checking relationships: ' . $e->getMessage());
        }
    }
}
