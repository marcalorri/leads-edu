<?php

namespace Database\Seeders\Locations;

use App\Models\Province;
use App\Models\Country;
use Illuminate\Database\Seeder;

class EuropeSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPortugal();
        $this->seedFrance();
        $this->seedItaly();
        $this->seedGermany();
        $this->seedUnitedKingdom();
    }
    
    protected function seedPortugal(): void
    {
        $portugal = Country::where('codigo', 'PT')->first();
        if (!$portugal) return;
        
        $districts = [
            ['codigo' => 'AV', 'nombre' => 'Aveiro'], ['codigo' => 'BE', 'nombre' => 'Beja'],
            ['codigo' => 'BR', 'nombre' => 'Braga'], ['codigo' => 'BA', 'nombre' => 'Bragança'],
            ['codigo' => 'CB', 'nombre' => 'Castelo Branco'], ['codigo' => 'CO', 'nombre' => 'Coimbra'],
            ['codigo' => 'EV', 'nombre' => 'Évora'], ['codigo' => 'FA', 'nombre' => 'Faro'],
            ['codigo' => 'GU', 'nombre' => 'Guarda'], ['codigo' => 'LE', 'nombre' => 'Leiria'],
            ['codigo' => 'LI', 'nombre' => 'Lisboa'], ['codigo' => 'PO', 'nombre' => 'Portalegre'],
            ['codigo' => 'PR', 'nombre' => 'Porto'], ['codigo' => 'SA', 'nombre' => 'Santarém'],
            ['codigo' => 'SE', 'nombre' => 'Setúbal'], ['codigo' => 'VC', 'nombre' => 'Viana do Castelo'],
            ['codigo' => 'VR', 'nombre' => 'Vila Real'], ['codigo' => 'VI', 'nombre' => 'Viseu'],
            ['codigo' => 'AZ', 'nombre' => 'Azores'], ['codigo' => 'MA', 'nombre' => 'Madeira'],
        ];
        
        foreach ($districts as $dist) {
            Province::updateOrCreate(
                ['codigo' => $dist['codigo']],
                ['country_id' => $portugal->id, 'nombre' => $dist['nombre'], 'comunidad_autonoma' => 'Portugal', 'activo' => true]
            );
        }
    }
    
    protected function seedFrance(): void
    {
        $france = Country::where('codigo', 'FR')->first();
        if (!$france) return;
        
        $regions = [
            ['codigo' => 'ARA', 'nombre' => 'Auvergne-Rhône-Alpes'],
            ['codigo' => 'BFC', 'nombre' => 'Bourgogne-Franche-Comté'],
            ['codigo' => 'BRE', 'nombre' => 'Bretagne'],
            ['codigo' => 'CVL', 'nombre' => 'Centre-Val de Loire'],
            ['codigo' => 'COR', 'nombre' => 'Corse'],
            ['codigo' => 'GES', 'nombre' => 'Grand Est'],
            ['codigo' => 'HDF', 'nombre' => 'Hauts-de-France'],
            ['codigo' => 'IDF', 'nombre' => 'Île-de-France'],
            ['codigo' => 'NOR', 'nombre' => 'Normandie'],
            ['codigo' => 'NAQ', 'nombre' => 'Nouvelle-Aquitaine'],
            ['codigo' => 'OCC', 'nombre' => 'Occitanie'],
            ['codigo' => 'PDL', 'nombre' => 'Pays de la Loire'],
            ['codigo' => 'PAC', 'nombre' => 'Provence-Alpes-Côte d\'Azur'],
        ];
        
        foreach ($regions as $region) {
            Province::updateOrCreate(
                ['codigo' => $region['codigo']],
                ['country_id' => $france->id, 'nombre' => $region['nombre'], 'comunidad_autonoma' => 'France', 'activo' => true]
            );
        }
    }
    
    protected function seedItaly(): void
    {
        $italy = Country::where('codigo', 'IT')->first();
        if (!$italy) return;
        
        $regions = [
            ['codigo' => 'ABR', 'nombre' => 'Abruzzo'], ['codigo' => 'BAS', 'nombre' => 'Basilicata'],
            ['codigo' => 'CAL', 'nombre' => 'Calabria'], ['codigo' => 'CAM', 'nombre' => 'Campania'],
            ['codigo' => 'EMR', 'nombre' => 'Emilia-Romagna'], ['codigo' => 'FVG', 'nombre' => 'Friuli-Venezia Giulia'],
            ['codigo' => 'LAZ', 'nombre' => 'Lazio'], ['codigo' => 'LIG', 'nombre' => 'Liguria'],
            ['codigo' => 'LOM', 'nombre' => 'Lombardia'], ['codigo' => 'MAR', 'nombre' => 'Marche'],
            ['codigo' => 'MOL', 'nombre' => 'Molise'], ['codigo' => 'PIE', 'nombre' => 'Piemonte'],
            ['codigo' => 'PUG', 'nombre' => 'Puglia'], ['codigo' => 'SAR', 'nombre' => 'Sardegna'],
            ['codigo' => 'SIC', 'nombre' => 'Sicilia'], ['codigo' => 'TOS', 'nombre' => 'Toscana'],
            ['codigo' => 'TAA', 'nombre' => 'Trentino-Alto Adige'], ['codigo' => 'UMB', 'nombre' => 'Umbria'],
            ['codigo' => 'VDA', 'nombre' => 'Valle d\'Aosta'], ['codigo' => 'VEN', 'nombre' => 'Veneto'],
        ];
        
        foreach ($regions as $region) {
            Province::updateOrCreate(
                ['codigo' => $region['codigo']],
                ['country_id' => $italy->id, 'nombre' => $region['nombre'], 'comunidad_autonoma' => 'Italy', 'activo' => true]
            );
        }
    }
    
    protected function seedGermany(): void
    {
        $germany = Country::where('codigo', 'DE')->first();
        if (!$germany) return;
        
        $states = [
            ['codigo' => 'BW', 'nombre' => 'Baden-Württemberg'], ['codigo' => 'BY', 'nombre' => 'Bayern'],
            ['codigo' => 'BE', 'nombre' => 'Berlin'], ['codigo' => 'BB', 'nombre' => 'Brandenburg'],
            ['codigo' => 'HB', 'nombre' => 'Bremen'], ['codigo' => 'HH', 'nombre' => 'Hamburg'],
            ['codigo' => 'HE', 'nombre' => 'Hessen'], ['codigo' => 'MV', 'nombre' => 'Mecklenburg-Vorpommern'],
            ['codigo' => 'NI', 'nombre' => 'Niedersachsen'], ['codigo' => 'NW', 'nombre' => 'Nordrhein-Westfalen'],
            ['codigo' => 'RP', 'nombre' => 'Rheinland-Pfalz'], ['codigo' => 'SL', 'nombre' => 'Saarland'],
            ['codigo' => 'SN', 'nombre' => 'Sachsen'], ['codigo' => 'ST', 'nombre' => 'Sachsen-Anhalt'],
            ['codigo' => 'SH', 'nombre' => 'Schleswig-Holstein'], ['codigo' => 'TH', 'nombre' => 'Thüringen'],
        ];
        
        foreach ($states as $state) {
            Province::updateOrCreate(
                ['codigo' => $state['codigo']],
                ['country_id' => $germany->id, 'nombre' => $state['nombre'], 'comunidad_autonoma' => 'Germany', 'activo' => true]
            );
        }
    }
    
    protected function seedUnitedKingdom(): void
    {
        $uk = Country::where('codigo', 'GB')->first();
        if (!$uk) return;
        
        $regions = [
            ['codigo' => 'ENG', 'nombre' => 'England'],
            ['codigo' => 'SCT', 'nombre' => 'Scotland'],
            ['codigo' => 'WLS', 'nombre' => 'Wales'],
            ['codigo' => 'NIR', 'nombre' => 'Northern Ireland'],
        ];
        
        foreach ($regions as $region) {
            Province::updateOrCreate(
                ['codigo' => $region['codigo']],
                ['country_id' => $uk->id, 'nombre' => $region['nombre'], 'comunidad_autonoma' => 'United Kingdom', 'activo' => true]
            );
        }
    }
}
