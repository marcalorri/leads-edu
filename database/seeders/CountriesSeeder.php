<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            // Europa
            ['codigo' => 'ES', 'codigo_iso3' => 'ESP', 'nombre' => 'España'],
            ['codigo' => 'PT', 'codigo_iso3' => 'PRT', 'nombre' => 'Portugal'],
            ['codigo' => 'FR', 'codigo_iso3' => 'FRA', 'nombre' => 'Francia'],
            ['codigo' => 'IT', 'codigo_iso3' => 'ITA', 'nombre' => 'Italia'],
            ['codigo' => 'DE', 'codigo_iso3' => 'DEU', 'nombre' => 'Alemania'],
            ['codigo' => 'GB', 'codigo_iso3' => 'GBR', 'nombre' => 'Reino Unido'],
            
            // América Latina
            ['codigo' => 'MX', 'codigo_iso3' => 'MEX', 'nombre' => 'México'],
            ['codigo' => 'CO', 'codigo_iso3' => 'COL', 'nombre' => 'Colombia'],
            ['codigo' => 'AR', 'codigo_iso3' => 'ARG', 'nombre' => 'Argentina'],
            ['codigo' => 'CL', 'codigo_iso3' => 'CHL', 'nombre' => 'Chile'],
            ['codigo' => 'PE', 'codigo_iso3' => 'PER', 'nombre' => 'Perú'],
            ['codigo' => 'EC', 'codigo_iso3' => 'ECU', 'nombre' => 'Ecuador'],
            ['codigo' => 'VE', 'codigo_iso3' => 'VEN', 'nombre' => 'Venezuela'],
            ['codigo' => 'BO', 'codigo_iso3' => 'BOL', 'nombre' => 'Bolivia'],
            ['codigo' => 'PY', 'codigo_iso3' => 'PRY', 'nombre' => 'Paraguay'],
            ['codigo' => 'UY', 'codigo_iso3' => 'URY', 'nombre' => 'Uruguay'],
            ['codigo' => 'CR', 'codigo_iso3' => 'CRI', 'nombre' => 'Costa Rica'],
            ['codigo' => 'PA', 'codigo_iso3' => 'PAN', 'nombre' => 'Panamá'],
            ['codigo' => 'BR', 'codigo_iso3' => 'BRA', 'nombre' => 'Brasil'],
            
            // América del Norte
            ['codigo' => 'US', 'codigo_iso3' => 'USA', 'nombre' => 'Estados Unidos'],
            ['codigo' => 'CA', 'codigo_iso3' => 'CAN', 'nombre' => 'Canadá'],
        ];
        
        foreach ($countries as $country) {
            Country::updateOrCreate(
                ['codigo' => $country['codigo']],
                [
                    'nombre' => $country['nombre'],
                    'codigo_iso3' => $country['codigo_iso3'],
                    'activo' => true,
                ]
            );
        }
        
        if ($this->command) {
            $this->command->info('✓ 22 countries seeded');
        }
    }
}
