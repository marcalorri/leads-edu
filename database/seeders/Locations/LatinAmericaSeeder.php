<?php

namespace Database\Seeders\Locations;

use App\Models\Province;
use App\Models\Country;
use Illuminate\Database\Seeder;

class LatinAmericaSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedMexico();
        $this->seedColombia();
        $this->seedArgentina();
        $this->seedChile();
        $this->seedPeru();
        $this->seedEcuador();
        $this->seedVenezuela();
        $this->seedBolivia();
        $this->seedParaguay();
        $this->seedUruguay();
        $this->seedCostaRica();
        $this->seedPanama();
        $this->seedBrazil();
    }
    
    protected function seedMexico(): void
    {
        $mexico = Country::where('codigo', 'MX')->first();
        if (!$mexico) return;
        
        $states = [
            ['codigo' => 'MX-AGU', 'nombre' => 'Aguascalientes'],
            ['codigo' => 'MX-BCN', 'nombre' => 'Baja California'],
            ['codigo' => 'MX-BCS', 'nombre' => 'Baja California Sur'],
            ['codigo' => 'MX-CAM', 'nombre' => 'Campeche'],
            ['codigo' => 'MX-CHP', 'nombre' => 'Chiapas'],
            ['codigo' => 'MX-CHH', 'nombre' => 'Chihuahua'],
            ['codigo' => 'MX-CMX', 'nombre' => 'Ciudad de México'],
            ['codigo' => 'MX-COA', 'nombre' => 'Coahuila'],
            ['codigo' => 'MX-COL', 'nombre' => 'Colima'],
            ['codigo' => 'MX-DUR', 'nombre' => 'Durango'],
            ['codigo' => 'MX-GUA', 'nombre' => 'Guanajuato'],
            ['codigo' => 'MX-GRO', 'nombre' => 'Guerrero'],
            ['codigo' => 'MX-HID', 'nombre' => 'Hidalgo'],
            ['codigo' => 'MX-JAL', 'nombre' => 'Jalisco'],
            ['codigo' => 'MX-MEX', 'nombre' => 'Estado de México'],
            ['codigo' => 'MX-MIC', 'nombre' => 'Michoacán'],
            ['codigo' => 'MX-MOR', 'nombre' => 'Morelos'],
            ['codigo' => 'MX-NAY', 'nombre' => 'Nayarit'],
            ['codigo' => 'MX-NLE', 'nombre' => 'Nuevo León'],
            ['codigo' => 'MX-OAX', 'nombre' => 'Oaxaca'],
            ['codigo' => 'MX-PUE', 'nombre' => 'Puebla'],
            ['codigo' => 'MX-QUE', 'nombre' => 'Querétaro'],
            ['codigo' => 'MX-ROO', 'nombre' => 'Quintana Roo'],
            ['codigo' => 'MX-SLP', 'nombre' => 'San Luis Potosí'],
            ['codigo' => 'MX-SIN', 'nombre' => 'Sinaloa'],
            ['codigo' => 'MX-SON', 'nombre' => 'Sonora'],
            ['codigo' => 'MX-TAB', 'nombre' => 'Tabasco'],
            ['codigo' => 'MX-TAM', 'nombre' => 'Tamaulipas'],
            ['codigo' => 'MX-TLA', 'nombre' => 'Tlaxcala'],
            ['codigo' => 'MX-VER', 'nombre' => 'Veracruz'],
            ['codigo' => 'MX-YUC', 'nombre' => 'Yucatán'],
            ['codigo' => 'MX-ZAC', 'nombre' => 'Zacatecas'],
        ];
        
        foreach ($states as $state) {
            Province::updateOrCreate(
                ['codigo' => $state['codigo']],
                ['country_id' => $mexico->id, 'nombre' => $state['nombre'], 'comunidad_autonoma' => 'México', 'activo' => true]
            );
        }
    }
    
    protected function seedColombia(): void
    {
        $colombia = Country::where('codigo', 'CO')->first();
        if (!$colombia) return;
        
        $departments = [
            ['codigo' => 'AMA', 'nombre' => 'Amazonas'], ['codigo' => 'ANT', 'nombre' => 'Antioquia'],
            ['codigo' => 'ARA', 'nombre' => 'Arauca'], ['codigo' => 'ATL', 'nombre' => 'Atlántico'],
            ['codigo' => 'BOL', 'nombre' => 'Bolívar'], ['codigo' => 'BOY', 'nombre' => 'Boyacá'],
            ['codigo' => 'CAL', 'nombre' => 'Caldas'], ['codigo' => 'CAQ', 'nombre' => 'Caquetá'],
            ['codigo' => 'CAS', 'nombre' => 'Casanare'], ['codigo' => 'CAU', 'nombre' => 'Cauca'],
            ['codigo' => 'CES', 'nombre' => 'Cesar'], ['codigo' => 'CHO', 'nombre' => 'Chocó'],
            ['codigo' => 'COR', 'nombre' => 'Córdoba'], ['codigo' => 'CUN', 'nombre' => 'Cundinamarca'],
            ['codigo' => 'DC', 'nombre' => 'Bogotá D.C.'], ['codigo' => 'GUA', 'nombre' => 'Guainía'],
            ['codigo' => 'GUV', 'nombre' => 'Guaviare'], ['codigo' => 'HUI', 'nombre' => 'Huila'],
            ['codigo' => 'LAG', 'nombre' => 'La Guajira'], ['codigo' => 'MAG', 'nombre' => 'Magdalena'],
            ['codigo' => 'MET', 'nombre' => 'Meta'], ['codigo' => 'NAR', 'nombre' => 'Nariño'],
            ['codigo' => 'NSA', 'nombre' => 'Norte de Santander'], ['codigo' => 'PUT', 'nombre' => 'Putumayo'],
            ['codigo' => 'QUI', 'nombre' => 'Quindío'], ['codigo' => 'RIS', 'nombre' => 'Risaralda'],
            ['codigo' => 'SAP', 'nombre' => 'San Andrés y Providencia'], ['codigo' => 'SAN', 'nombre' => 'Santander'],
            ['codigo' => 'SUC', 'nombre' => 'Sucre'], ['codigo' => 'TOL', 'nombre' => 'Tolima'],
            ['codigo' => 'VAC', 'nombre' => 'Valle del Cauca'], ['codigo' => 'VAU', 'nombre' => 'Vaupés'],
            ['codigo' => 'VID', 'nombre' => 'Vichada'],
        ];
        
        foreach ($departments as $dept) {
            Province::updateOrCreate(
                ['codigo' => $dept['codigo']],
                ['country_id' => $colombia->id, 'nombre' => $dept['nombre'], 'comunidad_autonoma' => 'Colombia', 'activo' => true]
            );
        }
    }
    
    protected function seedArgentina(): void
    {
        $argentina = Country::where('codigo', 'AR')->first();
        if (!$argentina) return;
        
        $provinces = [
            ['codigo' => 'BA', 'nombre' => 'Buenos Aires'], ['codigo' => 'CABA', 'nombre' => 'Ciudad Autónoma de Buenos Aires'],
            ['codigo' => 'CAT', 'nombre' => 'Catamarca'], ['codigo' => 'CHA', 'nombre' => 'Chaco'],
            ['codigo' => 'CHU', 'nombre' => 'Chubut'], ['codigo' => 'COR', 'nombre' => 'Córdoba'],
            ['codigo' => 'COR2', 'nombre' => 'Corrientes'], ['codigo' => 'ER', 'nombre' => 'Entre Ríos'],
            ['codigo' => 'FOR', 'nombre' => 'Formosa'], ['codigo' => 'JUJ', 'nombre' => 'Jujuy'],
            ['codigo' => 'LP', 'nombre' => 'La Pampa'], ['codigo' => 'LR', 'nombre' => 'La Rioja'],
            ['codigo' => 'MEN', 'nombre' => 'Mendoza'], ['codigo' => 'MIS', 'nombre' => 'Misiones'],
            ['codigo' => 'NEU', 'nombre' => 'Neuquén'], ['codigo' => 'RN', 'nombre' => 'Río Negro'],
            ['codigo' => 'SAL', 'nombre' => 'Salta'], ['codigo' => 'SJ', 'nombre' => 'San Juan'],
            ['codigo' => 'SL', 'nombre' => 'San Luis'], ['codigo' => 'SC', 'nombre' => 'Santa Cruz'],
            ['codigo' => 'SF', 'nombre' => 'Santa Fe'], ['codigo' => 'SE', 'nombre' => 'Santiago del Estero'],
            ['codigo' => 'TF', 'nombre' => 'Tierra del Fuego'], ['codigo' => 'TUC', 'nombre' => 'Tucumán'],
        ];
        
        foreach ($provinces as $prov) {
            Province::updateOrCreate(
                ['codigo' => $prov['codigo']],
                ['country_id' => $argentina->id, 'nombre' => $prov['nombre'], 'comunidad_autonoma' => 'Argentina', 'activo' => true]
            );
        }
    }
    
    protected function seedChile(): void
    {
        $chile = Country::where('codigo', 'CL')->first();
        if (!$chile) return;
        
        $regions = [
            ['codigo' => 'AP', 'nombre' => 'Arica y Parinacota'], ['codigo' => 'TA', 'nombre' => 'Tarapacá'],
            ['codigo' => 'AN', 'nombre' => 'Antofagasta'], ['codigo' => 'AT', 'nombre' => 'Atacama'],
            ['codigo' => 'CO', 'nombre' => 'Coquimbo'], ['codigo' => 'VA', 'nombre' => 'Valparaíso'],
            ['codigo' => 'RM', 'nombre' => 'Región Metropolitana'], ['codigo' => 'LI', 'nombre' => 'Libertador General Bernardo O\'Higgins'],
            ['codigo' => 'ML', 'nombre' => 'Maule'], ['codigo' => 'NB', 'nombre' => 'Ñuble'],
            ['codigo' => 'BI', 'nombre' => 'Biobío'], ['codigo' => 'AR', 'nombre' => 'La Araucanía'],
            ['codigo' => 'LR', 'nombre' => 'Los Ríos'], ['codigo' => 'LL', 'nombre' => 'Los Lagos'],
            ['codigo' => 'AI', 'nombre' => 'Aysén'], ['codigo' => 'MA', 'nombre' => 'Magallanes'],
        ];
        
        foreach ($regions as $region) {
            Province::updateOrCreate(
                ['codigo' => $region['codigo']],
                ['country_id' => $chile->id, 'nombre' => $region['nombre'], 'comunidad_autonoma' => 'Chile', 'activo' => true]
            );
        }
    }
    
    protected function seedPeru(): void
    {
        $peru = Country::where('codigo', 'PE')->first();
        if (!$peru) return;
        
        $departments = [
            ['codigo' => 'AMA', 'nombre' => 'Amazonas'], ['codigo' => 'ANC', 'nombre' => 'Áncash'],
            ['codigo' => 'APU', 'nombre' => 'Apurímac'], ['codigo' => 'ARE', 'nombre' => 'Arequipa'],
            ['codigo' => 'AYA', 'nombre' => 'Ayacucho'], ['codigo' => 'CAJ', 'nombre' => 'Cajamarca'],
            ['codigo' => 'CAL', 'nombre' => 'Callao'], ['codigo' => 'CUS', 'nombre' => 'Cusco'],
            ['codigo' => 'HUV', 'nombre' => 'Huancavelica'], ['codigo' => 'HUC', 'nombre' => 'Huánuco'],
            ['codigo' => 'ICA', 'nombre' => 'Ica'], ['codigo' => 'JUN', 'nombre' => 'Junín'],
            ['codigo' => 'LAL', 'nombre' => 'La Libertad'], ['codigo' => 'LAM', 'nombre' => 'Lambayeque'],
            ['codigo' => 'LIM', 'nombre' => 'Lima'], ['codigo' => 'LOR', 'nombre' => 'Loreto'],
            ['codigo' => 'MDD', 'nombre' => 'Madre de Dios'], ['codigo' => 'MOQ', 'nombre' => 'Moquegua'],
            ['codigo' => 'PAS', 'nombre' => 'Pasco'], ['codigo' => 'PIU', 'nombre' => 'Piura'],
            ['codigo' => 'PUN', 'nombre' => 'Puno'], ['codigo' => 'SAM', 'nombre' => 'San Martín'],
            ['codigo' => 'TAC', 'nombre' => 'Tacna'], ['codigo' => 'TUM', 'nombre' => 'Tumbes'],
            ['codigo' => 'UCA', 'nombre' => 'Ucayali'],
        ];
        
        foreach ($departments as $dept) {
            Province::updateOrCreate(
                ['codigo' => $dept['codigo']],
                ['country_id' => $peru->id, 'nombre' => $dept['nombre'], 'comunidad_autonoma' => 'Perú', 'activo' => true]
            );
        }
    }
    
    protected function seedEcuador(): void
    {
        $ecuador = Country::where('codigo', 'EC')->first();
        if (!$ecuador) return;
        
        $provinces = [
            ['codigo' => 'AZU', 'nombre' => 'Azuay'], ['codigo' => 'BOL', 'nombre' => 'Bolívar'],
            ['codigo' => 'CAN', 'nombre' => 'Cañar'], ['codigo' => 'CAR', 'nombre' => 'Carchi'],
            ['codigo' => 'CHI', 'nombre' => 'Chimborazo'], ['codigo' => 'COT', 'nombre' => 'Cotopaxi'],
            ['codigo' => 'EOR', 'nombre' => 'El Oro'], ['codigo' => 'ESM', 'nombre' => 'Esmeraldas'],
            ['codigo' => 'GAL', 'nombre' => 'Galápagos'], ['codigo' => 'GUA', 'nombre' => 'Guayas'],
            ['codigo' => 'IMB', 'nombre' => 'Imbabura'], ['codigo' => 'LOJ', 'nombre' => 'Loja'],
            ['codigo' => 'LOR', 'nombre' => 'Los Ríos'], ['codigo' => 'MAN', 'nombre' => 'Manabí'],
            ['codigo' => 'MSA', 'nombre' => 'Morona Santiago'], ['codigo' => 'NAP', 'nombre' => 'Napo'],
            ['codigo' => 'ORE', 'nombre' => 'Orellana'], ['codigo' => 'PAS', 'nombre' => 'Pastaza'],
            ['codigo' => 'PIC', 'nombre' => 'Pichincha'], ['codigo' => 'SDE', 'nombre' => 'Santo Domingo de los Tsáchilas'],
            ['codigo' => 'STE', 'nombre' => 'Santa Elena'], ['codigo' => 'SUC', 'nombre' => 'Sucumbíos'],
            ['codigo' => 'TUN', 'nombre' => 'Tungurahua'], ['codigo' => 'ZCH', 'nombre' => 'Zamora Chinchipe'],
        ];
        
        foreach ($provinces as $prov) {
            Province::updateOrCreate(
                ['codigo' => $prov['codigo']],
                ['country_id' => $ecuador->id, 'nombre' => $prov['nombre'], 'comunidad_autonoma' => 'Ecuador', 'activo' => true]
            );
        }
    }
    
    protected function seedVenezuela(): void
    {
        $venezuela = Country::where('codigo', 'VE')->first();
        if (!$venezuela) return;
        
        $states = [
            ['codigo' => 'AMA', 'nombre' => 'Amazonas'], ['codigo' => 'ANZ', 'nombre' => 'Anzoátegui'],
            ['codigo' => 'APU', 'nombre' => 'Apure'], ['codigo' => 'ARA', 'nombre' => 'Aragua'],
            ['codigo' => 'BAR', 'nombre' => 'Barinas'], ['codigo' => 'BOL', 'nombre' => 'Bolívar'],
            ['codigo' => 'CAR', 'nombre' => 'Carabobo'], ['codigo' => 'COJ', 'nombre' => 'Cojedes'],
            ['codigo' => 'DEL', 'nombre' => 'Delta Amacuro'], ['codigo' => 'DC', 'nombre' => 'Distrito Capital'],
            ['codigo' => 'FAL', 'nombre' => 'Falcón'], ['codigo' => 'GUA', 'nombre' => 'Guárico'],
            ['codigo' => 'LAR', 'nombre' => 'Lara'], ['codigo' => 'MER', 'nombre' => 'Mérida'],
            ['codigo' => 'MIR', 'nombre' => 'Miranda'], ['codigo' => 'MON', 'nombre' => 'Monagas'],
            ['codigo' => 'NEE', 'nombre' => 'Nueva Esparta'], ['codigo' => 'POR', 'nombre' => 'Portuguesa'],
            ['codigo' => 'SUC', 'nombre' => 'Sucre'], ['codigo' => 'TAC', 'nombre' => 'Táchira'],
            ['codigo' => 'TRU', 'nombre' => 'Trujillo'], ['codigo' => 'VAR', 'nombre' => 'Vargas'],
            ['codigo' => 'YAR', 'nombre' => 'Yaracuy'], ['codigo' => 'ZUL', 'nombre' => 'Zulia'],
        ];
        
        foreach ($states as $state) {
            Province::updateOrCreate(
                ['codigo' => $state['codigo']],
                ['country_id' => $venezuela->id, 'nombre' => $state['nombre'], 'comunidad_autonoma' => 'Venezuela', 'activo' => true]
            );
        }
    }
    
    protected function seedBolivia(): void
    {
        $bolivia = Country::where('codigo', 'BO')->first();
        if (!$bolivia) return;
        
        $departments = [
            ['codigo' => 'CHU', 'nombre' => 'Chuquisaca'], ['codigo' => 'LPZ', 'nombre' => 'La Paz'],
            ['codigo' => 'CBB', 'nombre' => 'Cochabamba'], ['codigo' => 'ORU', 'nombre' => 'Oruro'],
            ['codigo' => 'POT', 'nombre' => 'Potosí'], ['codigo' => 'TAR', 'nombre' => 'Tarija'],
            ['codigo' => 'SCZ', 'nombre' => 'Santa Cruz'], ['codigo' => 'BEN', 'nombre' => 'Beni'],
            ['codigo' => 'PAN', 'nombre' => 'Pando'],
        ];
        
        foreach ($departments as $dept) {
            Province::updateOrCreate(
                ['codigo' => $dept['codigo']],
                ['country_id' => $bolivia->id, 'nombre' => $dept['nombre'], 'comunidad_autonoma' => 'Bolivia', 'activo' => true]
            );
        }
    }
    
    protected function seedParaguay(): void
    {
        $paraguay = Country::where('codigo', 'PY')->first();
        if (!$paraguay) return;
        
        $departments = [
            ['codigo' => 'ASU', 'nombre' => 'Asunción'], ['codigo' => 'CON', 'nombre' => 'Concepción'],
            ['codigo' => 'SPE', 'nombre' => 'San Pedro'], ['codigo' => 'COR', 'nombre' => 'Cordillera'],
            ['codigo' => 'GUA', 'nombre' => 'Guairá'], ['codigo' => 'CAA', 'nombre' => 'Caaguazú'],
            ['codigo' => 'CAZ', 'nombre' => 'Caazapá'], ['codigo' => 'ITA', 'nombre' => 'Itapúa'],
            ['codigo' => 'MIS', 'nombre' => 'Misiones'], ['codigo' => 'PAR', 'nombre' => 'Paraguarí'],
            ['codigo' => 'APA', 'nombre' => 'Alto Paraná'], ['codigo' => 'CEN', 'nombre' => 'Central'],
            ['codigo' => 'ÑEE', 'nombre' => 'Ñeembucú'], ['codigo' => 'AMA', 'nombre' => 'Amambay'],
            ['codigo' => 'CAN', 'nombre' => 'Canindeyú'], ['codigo' => 'PPR', 'nombre' => 'Presidente Hayes'],
            ['codigo' => 'BOQ', 'nombre' => 'Boquerón'], ['codigo' => 'APG', 'nombre' => 'Alto Paraguay'],
        ];
        
        foreach ($departments as $dept) {
            Province::updateOrCreate(
                ['codigo' => $dept['codigo']],
                ['country_id' => $paraguay->id, 'nombre' => $dept['nombre'], 'comunidad_autonoma' => 'Paraguay', 'activo' => true]
            );
        }
    }
    
    protected function seedUruguay(): void
    {
        $uruguay = Country::where('codigo', 'UY')->first();
        if (!$uruguay) return;
        
        $departments = [
            ['codigo' => 'AR', 'nombre' => 'Artigas'], ['codigo' => 'CA', 'nombre' => 'Canelones'],
            ['codigo' => 'CL', 'nombre' => 'Cerro Largo'], ['codigo' => 'CO', 'nombre' => 'Colonia'],
            ['codigo' => 'DU', 'nombre' => 'Durazno'], ['codigo' => 'FS', 'nombre' => 'Flores'],
            ['codigo' => 'FD', 'nombre' => 'Florida'], ['codigo' => 'LA', 'nombre' => 'Lavalleja'],
            ['codigo' => 'MA', 'nombre' => 'Maldonado'], ['codigo' => 'MO', 'nombre' => 'Montevideo'],
            ['codigo' => 'PA', 'nombre' => 'Paysandú'], ['codigo' => 'RN', 'nombre' => 'Río Negro'],
            ['codigo' => 'RV', 'nombre' => 'Rivera'], ['codigo' => 'RO', 'nombre' => 'Rocha'],
            ['codigo' => 'SA', 'nombre' => 'Salto'], ['codigo' => 'SJ', 'nombre' => 'San José'],
            ['codigo' => 'SO', 'nombre' => 'Soriano'], ['codigo' => 'TA', 'nombre' => 'Tacuarembó'],
            ['codigo' => 'TT', 'nombre' => 'Treinta y Tres'],
        ];
        
        foreach ($departments as $dept) {
            Province::updateOrCreate(
                ['codigo' => $dept['codigo']],
                ['country_id' => $uruguay->id, 'nombre' => $dept['nombre'], 'comunidad_autonoma' => 'Uruguay', 'activo' => true]
            );
        }
    }
    
    protected function seedCostaRica(): void
    {
        $costaRica = Country::where('codigo', 'CR')->first();
        if (!$costaRica) return;
        
        $provinces = [
            ['codigo' => 'SJ', 'nombre' => 'San José'], ['codigo' => 'AL', 'nombre' => 'Alajuela'],
            ['codigo' => 'CA', 'nombre' => 'Cartago'], ['codigo' => 'HE', 'nombre' => 'Heredia'],
            ['codigo' => 'GU', 'nombre' => 'Guanacaste'], ['codigo' => 'PU', 'nombre' => 'Puntarenas'],
            ['codigo' => 'LI', 'nombre' => 'Limón'],
        ];
        
        foreach ($provinces as $prov) {
            Province::updateOrCreate(
                ['codigo' => $prov['codigo']],
                ['country_id' => $costaRica->id, 'nombre' => $prov['nombre'], 'comunidad_autonoma' => 'Costa Rica', 'activo' => true]
            );
        }
    }
    
    protected function seedPanama(): void
    {
        $panama = Country::where('codigo', 'PA')->first();
        if (!$panama) return;
        
        $provinces = [
            ['codigo' => 'BOC', 'nombre' => 'Bocas del Toro'], ['codigo' => 'CHI', 'nombre' => 'Chiriquí'],
            ['codigo' => 'COC', 'nombre' => 'Coclé'], ['codigo' => 'COL', 'nombre' => 'Colón'],
            ['codigo' => 'DAR', 'nombre' => 'Darién'], ['codigo' => 'HER', 'nombre' => 'Herrera'],
            ['codigo' => 'LSA', 'nombre' => 'Los Santos'], ['codigo' => 'PAN', 'nombre' => 'Panamá'],
            ['codigo' => 'VER', 'nombre' => 'Veraguas'], ['codigo' => 'PNO', 'nombre' => 'Panamá Oeste'],
        ];
        
        foreach ($provinces as $prov) {
            Province::updateOrCreate(
                ['codigo' => $prov['codigo']],
                ['country_id' => $panama->id, 'nombre' => $prov['nombre'], 'comunidad_autonoma' => 'Panamá', 'activo' => true]
            );
        }
    }
    
    protected function seedBrazil(): void
    {
        $brazil = Country::where('codigo', 'BR')->first();
        if (!$brazil) return;
        
        $states = [
            ['codigo' => 'AC', 'nombre' => 'Acre'], ['codigo' => 'AL', 'nombre' => 'Alagoas'],
            ['codigo' => 'AP', 'nombre' => 'Amapá'], ['codigo' => 'AM', 'nombre' => 'Amazonas'],
            ['codigo' => 'BA', 'nombre' => 'Bahia'], ['codigo' => 'CE', 'nombre' => 'Ceará'],
            ['codigo' => 'DF', 'nombre' => 'Distrito Federal'], ['codigo' => 'ES', 'nombre' => 'Espírito Santo'],
            ['codigo' => 'GO', 'nombre' => 'Goiás'], ['codigo' => 'MA', 'nombre' => 'Maranhão'],
            ['codigo' => 'MT', 'nombre' => 'Mato Grosso'], ['codigo' => 'MS', 'nombre' => 'Mato Grosso do Sul'],
            ['codigo' => 'MG', 'nombre' => 'Minas Gerais'], ['codigo' => 'PA', 'nombre' => 'Pará'],
            ['codigo' => 'PB', 'nombre' => 'Paraíba'], ['codigo' => 'PR', 'nombre' => 'Paraná'],
            ['codigo' => 'PE', 'nombre' => 'Pernambuco'], ['codigo' => 'PI', 'nombre' => 'Piauí'],
            ['codigo' => 'RJ', 'nombre' => 'Rio de Janeiro'], ['codigo' => 'RN', 'nombre' => 'Rio Grande do Norte'],
            ['codigo' => 'RS', 'nombre' => 'Rio Grande do Sul'], ['codigo' => 'RO', 'nombre' => 'Rondônia'],
            ['codigo' => 'RR', 'nombre' => 'Roraima'], ['codigo' => 'SC', 'nombre' => 'Santa Catarina'],
            ['codigo' => 'SP', 'nombre' => 'São Paulo'], ['codigo' => 'SE', 'nombre' => 'Sergipe'],
            ['codigo' => 'TO', 'nombre' => 'Tocantins'],
        ];
        
        foreach ($states as $state) {
            Province::updateOrCreate(
                ['codigo' => $state['codigo']],
                ['country_id' => $brazil->id, 'nombre' => $state['nombre'], 'comunidad_autonoma' => 'Brazil', 'activo' => true]
            );
        }
    }
}
