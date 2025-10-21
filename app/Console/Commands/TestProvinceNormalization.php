<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Services\LocationNormalizerService;
use Illuminate\Console\Command;

class TestProvinceNormalization extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:province-normalization {tenant_id}';

    /**
     * The console command description.
     */
    protected $description = 'Test province normalization with various inputs';

    /**
     * Execute the console command.
     */
    public function handle(LocationNormalizerService $normalizer): int
    {
        $tenantId = $this->argument('tenant_id');
        $tenant = Tenant::findOrFail($tenantId);
        
        $this->info("Testing province normalization for tenant: {$tenant->name}");
        $this->newLine();
        
        // Test cases comunes
        $testCases = [
            // Exactos
            'Madrid',
            'Barcelona',
            'Valencia',
            
            // Con acentos
            'Málaga',
            'Cádiz',
            'Córdoba',
            
            // Sin acentos
            'Malaga',
            'Cadiz',
            'Cordoba',
            
            // Variantes
            'Alacant',
            'Alicante',
            'València',
            'Castello',
            'Castellón',
            
            // Nombres completos
            'A Coruña',
            'La Coruña',
            'Coruña',
            
            // Errores tipográficos
            'Madrd',
            'Barcelon',
            'Sevila',
            
            // Códigos
            'M',
            'B',
            'V',
            
            // No existentes
            'Atlantis',
            'Wakanda',
        ];
        
        $this->table(
            ['Input', 'Found', 'Province Name', 'Method'],
            collect($testCases)->map(function ($input) use ($normalizer, $tenant) {
                $province = $normalizer->resolveProvince($input, $tenant, false);
                
                if ($province) {
                    $method = $this->detectMatchMethod($input, $province);
                    return [
                        $input,
                        '✓',
                        $province->nombre,
                        $method,
                    ];
                }
                
                return [
                    $input,
                    '✗',
                    '-',
                    'Not found',
                ];
            })->toArray()
        );
        
        // Estadísticas
        $this->newLine();
        $stats = $normalizer->getMatchStatistics($testCases, $tenant);
        
        $this->info('Statistics:');
        $this->line("Total inputs: {$stats['total']}");
        $this->line("Exact matches: {$stats['exact_matches']}");
        $this->line("Fuzzy matches: {$stats['fuzzy_matches']}");
        $this->line("Not found: {$stats['not_found']}");
        
        return Command::SUCCESS;
    }
    
    protected function detectMatchMethod(string $input, $province): string
    {
        $inputLower = mb_strtolower($input);
        $provinceLower = mb_strtolower($province->nombre);
        
        if ($inputLower === $provinceLower) {
            return 'Exact';
        }
        
        if ($input === $province->codigo) {
            return 'Code';
        }
        
        if ($input === $province->codigo_ine) {
            return 'INE Code';
        }
        
        return 'Fuzzy';
    }
}
