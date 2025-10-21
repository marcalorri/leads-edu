<?php

namespace Database\Seeders\Locations;

use App\Models\Province;
use App\Models\Country;
use Illuminate\Database\Seeder;

class NorthAmericaSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedUSA();
        $this->seedCanada();
    }
    
    protected function seedUSA(): void
    {
        $usa = Country::where('codigo', 'US')->first();
        
        if (!$usa) {
            if ($this->command) {
                $this->command->error('Country US not found. Run CountriesSeeder first.');
            }
            return;
        }
        
        $states = [
            ['codigo' => 'AL', 'nombre' => 'Alabama'], ['codigo' => 'AK', 'nombre' => 'Alaska'],
            ['codigo' => 'AZ', 'nombre' => 'Arizona'], ['codigo' => 'AR', 'nombre' => 'Arkansas'],
            ['codigo' => 'CA', 'nombre' => 'California'], ['codigo' => 'CO', 'nombre' => 'Colorado'],
            ['codigo' => 'CT', 'nombre' => 'Connecticut'], ['codigo' => 'DE', 'nombre' => 'Delaware'],
            ['codigo' => 'FL', 'nombre' => 'Florida'], ['codigo' => 'GA', 'nombre' => 'Georgia'],
            ['codigo' => 'HI', 'nombre' => 'Hawaii'], ['codigo' => 'ID', 'nombre' => 'Idaho'],
            ['codigo' => 'IL', 'nombre' => 'Illinois'], ['codigo' => 'IN', 'nombre' => 'Indiana'],
            ['codigo' => 'IA', 'nombre' => 'Iowa'], ['codigo' => 'KS', 'nombre' => 'Kansas'],
            ['codigo' => 'KY', 'nombre' => 'Kentucky'], ['codigo' => 'LA', 'nombre' => 'Louisiana'],
            ['codigo' => 'ME', 'nombre' => 'Maine'], ['codigo' => 'MD', 'nombre' => 'Maryland'],
            ['codigo' => 'MA', 'nombre' => 'Massachusetts'], ['codigo' => 'MI', 'nombre' => 'Michigan'],
            ['codigo' => 'MN', 'nombre' => 'Minnesota'], ['codigo' => 'MS', 'nombre' => 'Mississippi'],
            ['codigo' => 'MO', 'nombre' => 'Missouri'], ['codigo' => 'MT', 'nombre' => 'Montana'],
            ['codigo' => 'NE', 'nombre' => 'Nebraska'], ['codigo' => 'NV', 'nombre' => 'Nevada'],
            ['codigo' => 'NH', 'nombre' => 'New Hampshire'], ['codigo' => 'NJ', 'nombre' => 'New Jersey'],
            ['codigo' => 'NM', 'nombre' => 'New Mexico'], ['codigo' => 'NY', 'nombre' => 'New York'],
            ['codigo' => 'NC', 'nombre' => 'North Carolina'], ['codigo' => 'ND', 'nombre' => 'North Dakota'],
            ['codigo' => 'OH', 'nombre' => 'Ohio'], ['codigo' => 'OK', 'nombre' => 'Oklahoma'],
            ['codigo' => 'OR', 'nombre' => 'Oregon'], ['codigo' => 'PA', 'nombre' => 'Pennsylvania'],
            ['codigo' => 'RI', 'nombre' => 'Rhode Island'], ['codigo' => 'SC', 'nombre' => 'South Carolina'],
            ['codigo' => 'SD', 'nombre' => 'South Dakota'], ['codigo' => 'TN', 'nombre' => 'Tennessee'],
            ['codigo' => 'TX', 'nombre' => 'Texas'], ['codigo' => 'UT', 'nombre' => 'Utah'],
            ['codigo' => 'VT', 'nombre' => 'Vermont'], ['codigo' => 'VA', 'nombre' => 'Virginia'],
            ['codigo' => 'WA', 'nombre' => 'Washington'], ['codigo' => 'WV', 'nombre' => 'West Virginia'],
            ['codigo' => 'WI', 'nombre' => 'Wisconsin'], ['codigo' => 'WY', 'nombre' => 'Wyoming'],
            ['codigo' => 'DC', 'nombre' => 'District of Columbia'],
        ];
        
        foreach ($states as $state) {
            Province::updateOrCreate(
                ['codigo' => $state['codigo']],
                [
                    'country_id' => $usa->id,
                    'nombre' => $state['nombre'],
                    'comunidad_autonoma' => 'United States',
                    'activo' => true
                ]
            );
        }
    }
    
    protected function seedCanada(): void
    {
        $canada = Country::where('codigo', 'CA')->first();
        
        if (!$canada) {
            if ($this->command) {
                $this->command->error('Country CA not found. Run CountriesSeeder first.');
            }
            return;
        }
        
        $provinces = [
            ['codigo' => 'AB', 'nombre' => 'Alberta'],
            ['codigo' => 'BC', 'nombre' => 'British Columbia'],
            ['codigo' => 'MB', 'nombre' => 'Manitoba'],
            ['codigo' => 'NB', 'nombre' => 'New Brunswick'],
            ['codigo' => 'NL', 'nombre' => 'Newfoundland and Labrador'],
            ['codigo' => 'NT', 'nombre' => 'Northwest Territories'],
            ['codigo' => 'NS', 'nombre' => 'Nova Scotia'],
            ['codigo' => 'NU', 'nombre' => 'Nunavut'],
            ['codigo' => 'ON', 'nombre' => 'Ontario'],
            ['codigo' => 'PE', 'nombre' => 'Prince Edward Island'],
            ['codigo' => 'QC', 'nombre' => 'Quebec'],
            ['codigo' => 'SK', 'nombre' => 'Saskatchewan'],
            ['codigo' => 'YT', 'nombre' => 'Yukon'],
        ];
        
        foreach ($provinces as $prov) {
            Province::updateOrCreate(
                ['codigo' => $prov['codigo']],
                [
                    'country_id' => $canada->id,
                    'nombre' => $prov['nombre'],
                    'comunidad_autonoma' => 'Canada',
                    'activo' => true
                ]
            );
        }
    }
}
