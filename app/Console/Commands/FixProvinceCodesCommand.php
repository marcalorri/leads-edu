<?php

namespace App\Console\Commands;

use App\Models\Province;
use Illuminate\Console\Command;

class FixProvinceCodesCommand extends Command
{
    protected $signature = 'fix:province-codes {tenant_id}';
    protected $description = 'Add country prefixes to province codes to avoid duplicates';

    public function handle()
    {
        $tenantId = $this->argument('tenant_id');
        
        $this->info("Fixing province codes for tenant {$tenantId}...");
        
        // Mapeo de países a prefijos
        $countryPrefixes = [
            'México' => 'MX-',
            'Colombia' => 'CO-',
            'Argentina' => 'AR-',
            'Chile' => 'CL-',
            'Perú' => 'PE-',
            'Ecuador' => 'EC-',
            'Venezuela' => 'VE-',
            'Bolivia' => 'BO-',
            'Paraguay' => 'PY-',
            'Uruguay' => 'UY-',
            'Costa Rica' => 'CR-',
            'Panamá' => 'PA-',
            'Brazil' => 'BR-',
        ];
        
        foreach ($countryPrefixes as $country => $prefix) {
            $provinces = Province::where('tenant_id', $tenantId)
                ->where('comunidad_autonoma', $country)
                ->whereNotLike('codigo', $prefix . '%')
                ->get();
            
            foreach ($provinces as $province) {
                $oldCode = $province->codigo;
                $newCode = $prefix . $oldCode;
                
                $province->codigo = $newCode;
                $province->save();
                
                $this->line("  {$country}: {$oldCode} → {$newCode}");
            }
        }
        
        $this->info("\n✅ Province codes fixed successfully!");
    }
}
