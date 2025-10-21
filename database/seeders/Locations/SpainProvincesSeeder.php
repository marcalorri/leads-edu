<?php

namespace Database\Seeders\Locations;

use App\Models\Province;
use App\Models\Country;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class SpainProvincesSeeder extends Seeder
{
    public function run(): void
    {
        $spain = Country::where('codigo', 'ES')->first();
        
        if (!$spain) {
            if ($this->command) {
                $this->command->error('Country ES not found. Run CountriesSeeder first.');
            }
            return;
        }
        
        $provinces = [
            // Andalucía
            ['codigo_ine' => '04', 'codigo' => 'AL', 'nombre' => 'Almería', 'comunidad_autonoma' => 'Andalucía'],
            ['codigo_ine' => '11', 'codigo' => 'CA', 'nombre' => 'Cádiz', 'comunidad_autonoma' => 'Andalucía'],
            ['codigo_ine' => '14', 'codigo' => 'CO', 'nombre' => 'Córdoba', 'comunidad_autonoma' => 'Andalucía'],
            ['codigo_ine' => '18', 'codigo' => 'GR', 'nombre' => 'Granada', 'comunidad_autonoma' => 'Andalucía'],
            ['codigo_ine' => '21', 'codigo' => 'H', 'nombre' => 'Huelva', 'comunidad_autonoma' => 'Andalucía'],
            ['codigo_ine' => '23', 'codigo' => 'J', 'nombre' => 'Jaén', 'comunidad_autonoma' => 'Andalucía'],
            ['codigo_ine' => '29', 'codigo' => 'MA', 'nombre' => 'Málaga', 'comunidad_autonoma' => 'Andalucía'],
            ['codigo_ine' => '41', 'codigo' => 'SE', 'nombre' => 'Sevilla', 'comunidad_autonoma' => 'Andalucía'],
            
            // Aragón
            ['codigo_ine' => '22', 'codigo' => 'HU', 'nombre' => 'Huesca', 'comunidad_autonoma' => 'Aragón'],
            ['codigo_ine' => '44', 'codigo' => 'TE', 'nombre' => 'Teruel', 'comunidad_autonoma' => 'Aragón'],
            ['codigo_ine' => '50', 'codigo' => 'Z', 'nombre' => 'Zaragoza', 'comunidad_autonoma' => 'Aragón'],
            
            // Asturias
            ['codigo_ine' => '33', 'codigo' => 'O', 'nombre' => 'Asturias', 'comunidad_autonoma' => 'Asturias'],
            
            // Islas Baleares
            ['codigo_ine' => '07', 'codigo' => 'PM', 'nombre' => 'Illes Balears', 'comunidad_autonoma' => 'Illes Balears'],
            
            // Canarias
            ['codigo_ine' => '35', 'codigo' => 'GC', 'nombre' => 'Las Palmas', 'comunidad_autonoma' => 'Canarias'],
            ['codigo_ine' => '38', 'codigo' => 'TF', 'nombre' => 'Santa Cruz de Tenerife', 'comunidad_autonoma' => 'Canarias'],
            
            // Cantabria
            ['codigo_ine' => '39', 'codigo' => 'S', 'nombre' => 'Cantabria', 'comunidad_autonoma' => 'Cantabria'],
            
            // Castilla y León
            ['codigo_ine' => '05', 'codigo' => 'AV', 'nombre' => 'Ávila', 'comunidad_autonoma' => 'Castilla y León'],
            ['codigo_ine' => '09', 'codigo' => 'BU', 'nombre' => 'Burgos', 'comunidad_autonoma' => 'Castilla y León'],
            ['codigo_ine' => '24', 'codigo' => 'LE', 'nombre' => 'León', 'comunidad_autonoma' => 'Castilla y León'],
            ['codigo_ine' => '34', 'codigo' => 'P', 'nombre' => 'Palencia', 'comunidad_autonoma' => 'Castilla y León'],
            ['codigo_ine' => '37', 'codigo' => 'SA', 'nombre' => 'Salamanca', 'comunidad_autonoma' => 'Castilla y León'],
            ['codigo_ine' => '40', 'codigo' => 'SG', 'nombre' => 'Segovia', 'comunidad_autonoma' => 'Castilla y León'],
            ['codigo_ine' => '42', 'codigo' => 'SO', 'nombre' => 'Soria', 'comunidad_autonoma' => 'Castilla y León'],
            ['codigo_ine' => '47', 'codigo' => 'VA', 'nombre' => 'Valladolid', 'comunidad_autonoma' => 'Castilla y León'],
            ['codigo_ine' => '49', 'codigo' => 'ZA', 'nombre' => 'Zamora', 'comunidad_autonoma' => 'Castilla y León'],
            
            // Castilla-La Mancha
            ['codigo_ine' => '02', 'codigo' => 'AB', 'nombre' => 'Albacete', 'comunidad_autonoma' => 'Castilla-La Mancha'],
            ['codigo_ine' => '13', 'codigo' => 'CR', 'nombre' => 'Ciudad Real', 'comunidad_autonoma' => 'Castilla-La Mancha'],
            ['codigo_ine' => '16', 'codigo' => 'CU', 'nombre' => 'Cuenca', 'comunidad_autonoma' => 'Castilla-La Mancha'],
            ['codigo_ine' => '19', 'codigo' => 'GU', 'nombre' => 'Guadalajara', 'comunidad_autonoma' => 'Castilla-La Mancha'],
            ['codigo_ine' => '45', 'codigo' => 'TO', 'nombre' => 'Toledo', 'comunidad_autonoma' => 'Castilla-La Mancha'],
            
            // Cataluña
            ['codigo_ine' => '08', 'codigo' => 'B', 'nombre' => 'Barcelona', 'comunidad_autonoma' => 'Catalunya'],
            ['codigo_ine' => '17', 'codigo' => 'GI', 'nombre' => 'Girona', 'comunidad_autonoma' => 'Catalunya'],
            ['codigo_ine' => '25', 'codigo' => 'L', 'nombre' => 'Lleida', 'comunidad_autonoma' => 'Catalunya'],
            ['codigo_ine' => '43', 'codigo' => 'T', 'nombre' => 'Tarragona', 'comunidad_autonoma' => 'Catalunya'],
            
            // Comunidad Valenciana
            ['codigo_ine' => '03', 'codigo' => 'A', 'nombre' => 'Alicante', 'comunidad_autonoma' => 'Comunitat Valenciana'],
            ['codigo_ine' => '12', 'codigo' => 'CS', 'nombre' => 'Castellón', 'comunidad_autonoma' => 'Comunitat Valenciana'],
            ['codigo_ine' => '46', 'codigo' => 'V', 'nombre' => 'Valencia', 'comunidad_autonoma' => 'Comunitat Valenciana'],
            
            // Extremadura
            ['codigo_ine' => '06', 'codigo' => 'BA', 'nombre' => 'Badajoz', 'comunidad_autonoma' => 'Extremadura'],
            ['codigo_ine' => '10', 'codigo' => 'CC', 'nombre' => 'Cáceres', 'comunidad_autonoma' => 'Extremadura'],
            
            // Galicia
            ['codigo_ine' => '15', 'codigo' => 'C', 'nombre' => 'A Coruña', 'comunidad_autonoma' => 'Galicia'],
            ['codigo_ine' => '27', 'codigo' => 'LU', 'nombre' => 'Lugo', 'comunidad_autonoma' => 'Galicia'],
            ['codigo_ine' => '32', 'codigo' => 'OR', 'nombre' => 'Ourense', 'comunidad_autonoma' => 'Galicia'],
            ['codigo_ine' => '36', 'codigo' => 'PO', 'nombre' => 'Pontevedra', 'comunidad_autonoma' => 'Galicia'],
            
            // Madrid
            ['codigo_ine' => '28', 'codigo' => 'M', 'nombre' => 'Madrid', 'comunidad_autonoma' => 'Comunidad de Madrid'],
            
            // Murcia
            ['codigo_ine' => '30', 'codigo' => 'MU', 'nombre' => 'Murcia', 'comunidad_autonoma' => 'Región de Murcia'],
            
            // Navarra
            ['codigo_ine' => '31', 'codigo' => 'NA', 'nombre' => 'Navarra', 'comunidad_autonoma' => 'Comunidad Foral de Navarra'],
            
            // País Vasco
            ['codigo_ine' => '01', 'codigo' => 'VI', 'nombre' => 'Álava', 'comunidad_autonoma' => 'País Vasco'],
            ['codigo_ine' => '20', 'codigo' => 'SS', 'nombre' => 'Gipuzkoa', 'comunidad_autonoma' => 'País Vasco'],
            ['codigo_ine' => '48', 'codigo' => 'BI', 'nombre' => 'Bizkaia', 'comunidad_autonoma' => 'País Vasco'],
            
            // La Rioja
            ['codigo_ine' => '26', 'codigo' => 'LO', 'nombre' => 'La Rioja', 'comunidad_autonoma' => 'La Rioja'],
            
            // Ceuta y Melilla
            ['codigo_ine' => '51', 'codigo' => 'CE', 'nombre' => 'Ceuta', 'comunidad_autonoma' => 'Ceuta'],
            ['codigo_ine' => '52', 'codigo' => 'ML', 'nombre' => 'Melilla', 'comunidad_autonoma' => 'Melilla'],
        ];
        
        foreach ($provinces as $provinceData) {
            Province::updateOrCreate(
                ['codigo' => $provinceData['codigo']],
                [
                    'country_id' => $spain->id,
                    'nombre' => $provinceData['nombre'],
                    'codigo_ine' => $provinceData['codigo_ine'],
                    'comunidad_autonoma' => $provinceData['comunidad_autonoma'],
                    'activo' => true,
                ]
            );
        }
    }
}
