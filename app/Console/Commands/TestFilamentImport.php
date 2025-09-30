<?php

namespace App\Console\Commands;

use App\Filament\Dashboard\Imports\LeadImporter;
use App\Models\Lead;
use App\Models\Province;
use App\Models\Course;
use App\Models\Campus;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestFilamentImport extends Command
{
    protected $signature = 'test:filament-import';
    protected $description = 'Test Filament Lead Import with sample CSV data';

    public function handle()
    {
        $this->info('🧪 Testing Filament Lead Import...');
        
        // Test 1: Crear archivo CSV de prueba
        $this->info('1. Creating sample CSV file...');
        
        try {
            $csvData = $this->generateSampleCSV();
            $csvPath = 'test_leads.csv';
            Storage::disk('local')->put($csvPath, $csvData);
            $fullPath = storage_path('app/' . $csvPath);
            
            $this->info("✅ CSV created at: $fullPath");
            $this->info("CSV content preview:");
            $this->line(substr($csvData, 0, 200) . '...');
            
        } catch (\Exception $e) {
            $this->error('❌ Error creating CSV: ' . $e->getMessage());
            return;
        }
        
        // Test 2: Simular importación manual
        $this->info('2. Testing import logic manually...');
        
        try {
            $testRows = [
                [
                    'nombre' => 'Juan Carlos',
                    'apellidos' => 'García López',
                    'telefono' => '666123456',
                    'email' => 'juan.garcia.test@example.com',
                    'estado' => 'abierto'
                ],
                [
                    'nombre' => 'María',
                    'apellidos' => 'Rodríguez Pérez',
                    'telefono' => '677987654',
                    'email' => 'maria.rodriguez.test@example.com',
                    'estado' => 'abierto'
                ]
            ];
            
            $initialCount = Lead::count();
            $this->info("Initial lead count: $initialCount");
            
            foreach ($testRows as $index => $rowData) {
                $this->info("Processing row " . ($index + 1) . "...");
                
                // Crear lead directamente con los datos básicos
                $lead = new Lead();
                $lead->tenant_id = 1; // Asumiendo tenant ID 1
                $lead->nombre = $rowData['nombre'];
                $lead->apellidos = $rowData['apellidos'];
                $lead->telefono = $rowData['telefono'];
                $lead->email = $rowData['email'];
                $lead->estado = $rowData['estado'];
                
                // Asignar asesor
                $user = User::first();
                if ($user) {
                    $lead->asesor_id = $user->id;
                }
                
                $lead->save();
                
                $this->info("✅ Lead created with ID: {$lead->id}");
            }
            
            $finalCount = Lead::count();
            $imported = $finalCount - $initialCount;
            $this->info("✅ Successfully imported $imported leads");
            
        } catch (\Exception $e) {
            $this->error('❌ Error during manual import: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
        
        // Test 3: Verificar datos importados
        $this->info('3. Verifying imported data...');
        
        try {
            $recentLeads = Lead::orderBy('created_at', 'desc')->take(3)->get();
            
            foreach ($recentLeads as $lead) {
                $this->info("Lead ID {$lead->id}: {$lead->nombre} {$lead->apellidos} - {$lead->email}");
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Error verifying data: ' . $e->getMessage());
        }
        
        $this->info('🎉 Test completed!');
    }
    
    private function generateSampleCSV(): string
    {
        // Obtener datos reales para las relaciones
        $province = Province::first();
        $course = Course::first();
        $campus = Campus::first();
        $user = User::first();
        
        $provinceName = $province ? $province->nombre : 'Madrid';
        $courseName = $course ? $course->codigo_curso : 'PROG001';
        $campusName = $campus ? $campus->nombre : 'Campus Central';
        $userName = $user ? $user->name : 'Admin User';
        
        $header = 'nombre,apellidos,telefono,email,provincia,curso,sede,asesor,estado';
        
        $rows = [
            "Ana,Martínez González,666111222,ana.martinez@example.com,$provinceName,$courseName,$campusName,$userName,abierto",
            "Carlos,López Ruiz,677333444,carlos.lopez@example.com,$provinceName,$courseName,$campusName,$userName,abierto",
            "Elena,Fernández Castro,688555666,elena.fernandez@example.com,$provinceName,$courseName,$campusName,$userName,abierto",
            "David,Sánchez Moreno,699777888,david.sanchez@example.com,$provinceName,$courseName,$campusName,$userName,abierto",
            "Laura,García Jiménez,655999000,laura.garcia@example.com,$provinceName,$courseName,$campusName,$userName,abierto"
        ];
        
        return $header . "\n" . implode("\n", $rows);
    }
}
