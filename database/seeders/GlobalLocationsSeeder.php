<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Database\Seeders\CountriesSeeder;
use Database\Seeders\Locations\SpainProvincesSeeder;
use Database\Seeders\Locations\LatinAmericaSeeder;
use Database\Seeders\Locations\EuropeSeeder;
use Database\Seeders\Locations\NorthAmericaSeeder;
use Illuminate\Database\Seeder;

class GlobalLocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Este seeder orquesta todos los seeders de ubicaciones por región.
     * Cubre 22 países con ~550 ubicaciones totales.
     */
    public function run(): void
    {
        $this->command->info("\n" . str_repeat('=', 60));
        $this->command->info('🌍 GLOBAL LOCATIONS SEEDER');
        $this->command->info('   (Shared catalog - no tenant required)');
        $this->command->info(str_repeat('=', 60));
        
        // Países primero (necesarios para foreign keys)
        $this->command->info("\n  🌐 Countries...");
        (new CountriesSeeder())->run();
        
        // España
        $this->command->info("\n  🇪🇸 Spain...");
        (new SpainProvincesSeeder())->run();
        $this->command->line("     ✓ 52 provinces");
        
        // América Latina
        $this->command->info("  🌎 Latin America...");
        (new LatinAmericaSeeder())->run();
        $this->command->line("     ✓ 13 countries");
        
        // Europa
        $this->command->info("  🇪🇺 Europe...");
        (new EuropeSeeder())->run();
        $this->command->line("     ✓ 5 countries");
        
        // América del Norte
        $this->command->info("  🌎 North America...");
        (new NorthAmericaSeeder())->run();
        $this->command->line("     ✓ 2 countries");
        
        $this->command->info("\n" . str_repeat('=', 60));
        $this->command->info('✨ SEEDING COMPLETED - ~550 locations loaded');
        $this->command->info(str_repeat('=', 60) . "\n");
    }
}
