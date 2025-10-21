<?php

namespace App\Services;

use App\Models\Province;
use App\Models\Country;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class LocationNormalizerService
{
    /**
     * Normalizar y resolver provincia por nombre, código o variantes
     * Ahora trabaja con catálogo global (sin tenant_id)
     */
    public function resolveProvince(string $input, ?int $countryId = null): ?Province
    {
        // 1. Normalizar el input
        $normalized = $this->normalizeString($input);
        
        // Query base
        $query = Province::query()->where('activo', true);
        
        // Filtrar por país si se proporciona
        if ($countryId) {
            $query->where('country_id', $countryId);
        }
        
        // 2. Buscar coincidencia exacta por nombre normalizado
        $province = (clone $query)
            ->whereRaw('LOWER(nombre) = ?', [$normalized])
            ->first();
        
        if ($province) {
            return $province;
        }
        
        // 3. Buscar por código exacto
        $province = (clone $query)
            ->whereRaw('LOWER(codigo) = ?', [$normalized])
            ->first();
        
        if ($province) {
            return $province;
        }
        
        // 4. Buscar por código INE
        $province = (clone $query)
            ->where('codigo_ine', $input)
            ->first();
        
        if ($province) {
            return $province;
        }
        
        // 5. Fuzzy matching - buscar similitudes
        $province = $this->fuzzyMatchProvince($input, $countryId);
        
        if ($province) {
            return $province;
        }
        
        // 6. No se encuentra - registrar para revisión
        $this->logNotFound($input, $countryId);
        
        return null;
    }
    
    /**
     * Fuzzy matching para encontrar provincias similares
     */
    protected function fuzzyMatchProvince(string $input, ?int $countryId = null): ?Province
    {
        $normalized = $this->normalizeString($input);
        
        // Query base
        $query = Province::query()->where('activo', true);
        if ($countryId) {
            $query->where('country_id', $countryId);
        }
        
        // Mapeo de variantes comunes
        $variants = $this->getProvinceVariants($normalized);
        
        foreach ($variants as $variant) {
            $province = (clone $query)
                ->whereRaw('LOWER(nombre) LIKE ?', ["%{$variant}%"])
                ->first();
            
            if ($province) {
                return $province;
            }
        }
        
        // Búsqueda por similitud de Levenshtein (distancia de edición)
        $provinces = $query->get();
        
        $bestMatch = null;
        $minDistance = PHP_INT_MAX;
        
        foreach ($provinces as $province) {
            $distance = levenshtein(
                $normalized,
                $this->normalizeString($province->nombre)
            );
            
            // Si la distancia es menor a 3 caracteres, considerarlo coincidencia
            if ($distance < 3 && $distance < $minDistance) {
                $minDistance = $distance;
                $bestMatch = $province;
            }
        }
        
        return $bestMatch;
    }
    
    /**
     * Obtener variantes comunes de nombres de provincias
     */
    protected function getProvinceVariants(string $normalized): array
    {
        $variants = [$normalized];
        
        // Mapeo de variantes comunes españolas
        $commonVariants = [
            'alicante' => ['alacant'],
            'castellon' => ['castello', 'castelló'],
            'valencia' => ['valència'],
            'vizcaya' => ['bizkaia'],
            'guipuzcoa' => ['gipuzkoa'],
            'alava' => ['araba'],
            'navarra' => ['nafarroa'],
            'la coruña' => ['a coruña', 'coruña'],
            'orense' => ['ourense'],
            'pontevedra' => ['pontevedra'],
            'baleares' => ['illes balears', 'islas baleares'],
        ];
        
        foreach ($commonVariants as $standard => $alternatives) {
            if (Str::contains($normalized, $standard)) {
                $variants = array_merge($variants, $alternatives);
            }
            
            foreach ($alternatives as $alt) {
                if (Str::contains($normalized, $alt)) {
                    $variants[] = $standard;
                    $variants = array_merge($variants, $alternatives);
                }
            }
        }
        
        return array_unique($variants);
    }
    
    /**
     * Registrar provincia no encontrada para revisión
     */
    protected function logNotFound(string $input, ?int $countryId = null): void
    {
        if (!config('app.suggest_corrections', true)) {
            return;
        }
        
        // Buscar la provincia más cercana para sugerir
        $suggestion = $this->findClosestMatch($input, $countryId);
        
        Log::warning('Province not found', [
            'input' => $input,
            'country_id' => $countryId,
            'suggestion' => $suggestion ? $suggestion->nombre : null,
            'suggestion_id' => $suggestion ? $suggestion->id : null,
            'suggestion_country' => $suggestion && $suggestion->country ? $suggestion->country->nombre : null,
        ]);
    }
    
    /**
     * Encontrar la coincidencia más cercana para sugerencias
     */
    protected function findClosestMatch(string $input, ?int $countryId = null): ?Province
    {
        $normalized = $this->normalizeString($input);
        
        $query = Province::query()->where('activo', true);
        if ($countryId) {
            $query->where('country_id', $countryId);
        }
        
        $provinces = $query->get();
        
        $bestMatch = null;
        $minDistance = PHP_INT_MAX;
        
        foreach ($provinces as $province) {
            $distance = levenshtein(
                $normalized,
                $this->normalizeString($province->nombre)
            );
            
            if ($distance < $minDistance) {
                $minDistance = $distance;
                $bestMatch = $province;
            }
        }
        
        // Solo sugerir si la distancia es razonable (< 5)
        return $minDistance < 5 ? $bestMatch : null;
    }
    
    /**
     * Normalizar string para comparación
     */
    protected function normalizeString(string $input): string
    {
        // Convertir a minúsculas
        $normalized = mb_strtolower($input, 'UTF-8');
        
        // Remover acentos
        $normalized = $this->removeAccents($normalized);
        
        // Remover espacios extras
        $normalized = preg_replace('/\s+/', ' ', $normalized);
        $normalized = trim($normalized);
        
        return $normalized;
    }
    
    /**
     * Remover acentos de un string
     */
    protected function removeAccents(string $string): string
    {
        $unwanted = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'a', 'É' => 'e', 'Í' => 'i', 'Ó' => 'o', 'Ú' => 'u',
            'à' => 'a', 'è' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u',
            'À' => 'a', 'È' => 'e', 'Ì' => 'i', 'Ò' => 'o', 'Ù' => 'u',
            'ä' => 'a', 'ë' => 'e', 'ï' => 'i', 'ö' => 'o', 'ü' => 'u',
            'Ä' => 'a', 'Ë' => 'e', 'Ï' => 'i', 'Ö' => 'o', 'Ü' => 'u',
            'ñ' => 'n', 'Ñ' => 'n', 'ç' => 'c', 'Ç' => 'c',
        ];
        
        return strtr($string, $unwanted);
    }
    
    /**
     * Resolver país por nombre o código
     */
    public function resolveCountry(string $input): ?Country
    {
        $normalized = $this->normalizeString($input);
        
        // Buscar por código ISO2
        $country = Country::where('activo', true)
            ->whereRaw('LOWER(codigo) = ?', [$normalized])
            ->first();
        
        if ($country) {
            return $country;
        }
        
        // Buscar por código ISO3
        $country = Country::where('activo', true)
            ->whereRaw('LOWER(codigo_iso3) = ?', [$normalized])
            ->first();
        
        if ($country) {
            return $country;
        }
        
        // Buscar por nombre
        $country = Country::where('activo', true)
            ->whereRaw('LOWER(nombre) = ?', [$normalized])
            ->first();
        
        if ($country) {
            return $country;
        }
        
        // Fuzzy matching para países
        $countries = Country::where('activo', true)->get();
        $bestMatch = null;
        $minDistance = PHP_INT_MAX;
        
        foreach ($countries as $country) {
            $distance = levenshtein(
                $normalized,
                $this->normalizeString($country->nombre)
            );
            
            if ($distance < 3 && $distance < $minDistance) {
                $minDistance = $distance;
                $bestMatch = $country;
            }
        }
        
        return $bestMatch;
    }
    
    /**
     * Obtener estadísticas de normalización
     */
    public function getMatchStatistics(array $inputs, ?int $countryId = null): array
    {
        $stats = [
            'total' => count($inputs),
            'exact_matches' => 0,
            'fuzzy_matches' => 0,
            'not_found' => 0,
        ];
        
        foreach ($inputs as $input) {
            $province = $this->resolveProvince($input, $countryId);
            
            if ($province) {
                $normalized = $this->normalizeString($input);
                $provinceNormalized = $this->normalizeString($province->nombre);
                
                if ($normalized === $provinceNormalized) {
                    $stats['exact_matches']++;
                } else {
                    $stats['fuzzy_matches']++;
                }
            } else {
                $stats['not_found']++;
            }
        }
        
        return $stats;
    }
}
