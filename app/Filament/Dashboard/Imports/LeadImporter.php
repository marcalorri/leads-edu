<?php

namespace App\Filament\Dashboard\Imports;

use App\Models\Lead;
use App\Models\Course;
use App\Models\Campus;
use App\Models\Modality;
use App\Models\Province;
use App\Models\SalesPhase;
use App\Models\Origin;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Log;
use App\Models\NullReason;

class LeadImporter extends Importer
{
    protected static ?string $model = Lead::class;
    
    public static function getColumns(): array
    {
        return [
            // CAMPOS OBLIGATORIOS
            ImportColumn::make('nombre')
                ->label(__('First Name'))
                ->requiredMapping()
                ->rules(['required', 'max:100'])
                ->example('John'),
            
            ImportColumn::make('apellidos')
                ->label(__('Last Name'))
                ->requiredMapping()
                ->rules(['required', 'max:150'])
                ->example('Smith'),
            
            ImportColumn::make('telefono')
                ->label(__('Phone'))
                ->requiredMapping()
                ->rules(['required', 'max:20'])
                ->example('+1234567890'),
            
            ImportColumn::make('email')
                ->label(__('Email'))
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255'])
                ->example('user@example.com'),
            
            // RELACIONES IMPORTANTES (OPCIONALES POR AHORA)
            ImportColumn::make('provincia_id')
                ->label(__('Province'))
                ->rules(['nullable'])
                ->relationship(resolveUsing: function (?string $state): ?int {
                    if (empty($state)) return null;
                    $tenantId = filament()->getTenant()?->id ?? session('tenant_id', 1);
                    $province = Province::where('tenant_id', $tenantId)
                        ->where('nombre', 'like', "%{$state}%")
                        ->first();
                    return $province?->id;
                })
                ->example('New York'),
            
            ImportColumn::make('curso_id')
                ->label(__('Course (Code or Title)'))
                ->rules(['nullable'])
                ->relationship(resolveUsing: function (?string $state): ?int {
                    if (empty($state)) return null;
                    $tenantId = filament()->getTenant()?->id ?? session('tenant_id', 1);
                    
                    // Primero buscar por código exacto
                    $course = Course::where('tenant_id', $tenantId)
                        ->where('codigo_curso', $state)
                        ->first();
                    
                    if ($course) {
                        return $course->id;
                    }
                    
                    // Luego buscar por código parcial
                    $course = Course::where('tenant_id', $tenantId)
                        ->where('codigo_curso', 'like', "%{$state}%")
                        ->first();
                    
                    if ($course) {
                        return $course->id;
                    }
                    
                    // Finalmente buscar por título
                    $course = Course::where('tenant_id', $tenantId)
                        ->where('titulacion', 'like', "%{$state}%")
                        ->first();
                    return $course?->id;
                })
                ->example('PROG001'),
            
            ImportColumn::make('sede_id')
                ->label(__('Campus'))
                ->rules(['nullable'])
                ->relationship(resolveUsing: function (?string $state): ?int {
                    if (empty($state)) return null;
                    $tenantId = filament()->getTenant()?->id ?? session('tenant_id', 1);
                    $campus = Campus::where('tenant_id', $tenantId)
                        ->where('nombre', 'like', "%{$state}%")
                        ->first();
                    return $campus?->id;
                })
                ->example('Main Campus'),
            
            // CAMPOS OPCIONALES PERO IMPORTANTES
            ImportColumn::make('pais')
                ->label(__('Country'))
                ->rules(['nullable', 'max:100'])
                ->example('United States'),
            
            ImportColumn::make('modalidad_id')
                ->label(__('Modality'))
                ->relationship(resolveUsing: function (?string $state): ?int {
                    if (empty($state)) return null;
                    $tenantId = filament()->getTenant()?->id ?? session('tenant_id', 1);
                    $modality = Modality::where('tenant_id', $tenantId)
                        ->where('nombre', 'like', "%{$state}%")
                        ->first();
                    return $modality?->id;
                })
                ->example('Online'),
            
            ImportColumn::make('convocatoria')
                ->label(__('Call'))
                ->rules(['nullable', 'max:100'])
                ->example('2024-01'),
            
            ImportColumn::make('horario')
                ->label(__('Schedule'))
                ->rules(['nullable', 'max:100'])
                ->example('Morning'),
            
            // ESTADO Y SEGUIMIENTO
            ImportColumn::make('estado')
                ->label(__('Status'))
                ->rules(['nullable', 'in:abierto,ganado,perdido'])
                ->example('abierto'),
            
            ImportColumn::make('asesor_id')
                ->label(__('Advisor (Email or Name)'))
                ->relationship(resolveUsing: function (?string $state): ?int {
                    if (empty($state)) return null;
                    $tenantId = filament()->getTenant()?->id ?? session('tenant_id', 1);
                    
                    // Buscar por email exacto
                    $user = \App\Models\User::whereHas('tenants', function($query) use ($tenantId) {
                        $query->where('tenant_id', $tenantId);
                    })->where('email', $state)->first();
                    
                    if ($user) {
                        return $user->id;
                    }
                    
                    // Buscar por nombre
                    $user = \App\Models\User::whereHas('tenants', function($query) use ($tenantId) {
                        $query->where('tenant_id', $tenantId);
                    })->where('name', 'like', "%{$state}%")->first();
                    return $user?->id;
                })
                ->example('advisor@company.com'),
            
            ImportColumn::make('fase_venta_id')
                ->label(__('Sales Phase'))
                ->relationship(resolveUsing: function (?string $state): ?int {
                    if (empty($state)) return null;
                    $tenantId = filament()->getTenant()?->id ?? session('tenant_id', 1);
                    $salesPhase = SalesPhase::where('tenant_id', $tenantId)
                        ->where('nombre', 'like', "%{$state}%")
                        ->first();
                    return $salesPhase?->id;
                })
                ->example('Initial Contact'),
            
            ImportColumn::make('origen_id')
                ->label(__('Origin'))
                ->relationship(resolveUsing: function (?string $state): ?int {
                    if (empty($state)) return null;
                    $tenantId = filament()->getTenant()?->id ?? session('tenant_id', 1);
                    $origin = Origin::where('tenant_id', $tenantId)
                        ->where('nombre', 'like', "%{$state}%")
                        ->first();
                    return $origin?->id;
                })
                ->example('Web'),
            
            ImportColumn::make('motivo_nulo_id')
                ->label(__('Null Reason (only if status=lost)'))
                ->relationship(resolveUsing: function (?string $state): ?int {
                    if (empty($state)) return null;
                    $tenantId = filament()->getTenant()?->id ?? session('tenant_id', 1);
                    $nullReason = \App\Models\NullReason::where('tenant_id', $tenantId)
                        ->where('nombre', 'like', "%{$state}%")
                        ->first();
                    return $nullReason?->id;
                })
                ->example('High price'),
            
            // CAMPOS UTM PARA TRACKING
            ImportColumn::make('utm_source')
                ->label('UTM Source')
                ->rules(['nullable', 'max:255'])
                ->example('google'),
            
            ImportColumn::make('utm_medium')
                ->label('UTM Medium')
                ->rules(['nullable', 'max:255'])
                ->example('cpc'),
            
            ImportColumn::make('utm_campaign')
                ->label('UTM Campaign')
                ->rules(['nullable', 'max:255'])
                ->example('spring_2024'),
        ];
    }

    public function resolveRecord(): ?Lead
    {
        error_log('=== LEADIMPORTER RESOLVERECORD EJECUTADO ===');
        Log::info('LeadImporter resolveRecord - Iniciando resolución de registro');
        
        try {
            // Obtener tenant ID de manera segura - primero de los datos, luego fallback
            $tenantId = $this->data['tenant_id'] ?? null;
            
            if (!$tenantId) {
                Log::warning('LeadImporter resolveRecord - No hay tenant_id en los datos, usando fallback');
                
                // Usar el mismo método que funciona en beforeFill
                $tenant = null;
                try {
                    $tenant = filament()->getTenant();
                } catch (\Exception $e) {
                    // Fallback a session o primer tenant
                    $tenantId = session('tenant_id') ?? 1;
                    $tenant = \App\Models\Tenant::find($tenantId);
                }
                
                if (!$tenant && !$tenantId) {
                    $tenant = \App\Models\Tenant::first();
                }
                
                $tenantId = $tenant ? $tenant->id : $tenantId;
                
                if (!$tenantId) {
                    Log::error('LeadImporter resolveRecord - No se pudo obtener tenant por ningún método');
                    throw new \Exception('No se pudo determinar el tenant para el lead');
                }
                
                Log::info('LeadImporter resolveRecord - Tenant obtenido por fallback:', ['tenant_id' => $tenantId]);
            } else {
                Log::info('LeadImporter resolveRecord - Usando tenant ID de datos:', ['tenant_id' => $tenantId]);
            }
            
            // SIEMPRE crear nuevo lead - Un email puede tener múltiples leads
            // para diferentes cursos o períodos de tiempo
            Log::info('LeadImporter resolveRecord - Creando nuevo lead (emails duplicados permitidos)');
            
            if (!empty($this->data['email'])) {
                $existingLeadsCount = Lead::where('tenant_id', $tenantId)
                    ->where('email', $this->data['email'])
                    ->count();
                    
                if ($existingLeadsCount > 0) {
                    Log::info('LeadImporter resolveRecord - Email ya existe pero creando nuevo lead:', [
                        'email' => $this->data['email'],
                        'existing_leads_count' => $existingLeadsCount
                    ]);
                }
            }
            
            return new Lead();
            
        } catch (\Exception $e) {
            Log::error('LeadImporter resolveRecord - Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __('Lead import completed.') . ' ' . number_format($import->successful_rows) . ' ' . str('lead')->plural($import->successful_rows) . ' ' . __('imported') . '.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' ' . __('failed to import') . '.';
        }

        return $body;
    }

    protected function beforeFill(): void
    {
        error_log('=== LEADIMPORTER BEFOREFILL EJECUTADO ===');
        Log::info('LeadImporter beforeFill - Data recibida:', $this->data);
        
        // Verificar contexto del tenant con múltiples métodos
        $tenant = null;
        
        // Método 1: Usar filament()->getTenant()
        try {
            $tenant = filament()->getTenant();
        } catch (\Exception $e) {
            Log::warning('LeadImporter - Error obteniendo tenant con filament(): ' . $e->getMessage());
        }
        
        // Método 2: Si no funciona, buscar en la URL o sesión
        if (!$tenant) {
            try {
                // Obtener tenant_id de la URL actual
                $currentUrl = request()->url();
                Log::info('LeadImporter - URL actual:', ['url' => $currentUrl]);
                
                // Extraer tenant_id de la URL (formato: /dashboard/{tenant_id}/...)
                if (preg_match('/\/dashboard\/(\d+)\//', $currentUrl, $matches)) {
                    $tenantId = $matches[1];
                    $tenant = \App\Models\Tenant::find($tenantId);
                    Log::info('LeadImporter - Tenant obtenido de URL:', ['tenant_id' => $tenantId]);
                }
                
                // Fallback a session o primer tenant
                if (!$tenant) {
                    $tenantId = session('tenant_id') ?? 1;
                    $tenant = \App\Models\Tenant::find($tenantId);
                    Log::info('LeadImporter - Tenant obtenido por session fallback:', ['tenant_id' => $tenantId]);
                }
            } catch (\Exception $e) {
                Log::warning('LeadImporter - Error en fallback de tenant: ' . $e->getMessage());
            }
        }
        
        // Método 3: Último recurso - usar primer tenant disponible
        if (!$tenant) {
            $tenant = \App\Models\Tenant::first();
            if ($tenant) {
                Log::warning('LeadImporter - Usando primer tenant como último recurso:', ['tenant_id' => $tenant->id]);
            }
        }
        
        if (!$tenant) {
            Log::error('LeadImporter - No se pudo obtener ningún tenant');
            throw new \Exception('No se pudo obtener el tenant. Contacta al administrador.');
        }
        
        // Asegurar que el tenant_id se establezca
        $this->data['tenant_id'] = $tenant->id;
        Log::info('LeadImporter - Tenant ID asignado:', ['tenant_id' => $this->data['tenant_id']]);
        
        // Verificar usuario autenticado
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user) {
            Log::error('LeadImporter - No hay usuario autenticado');
            throw new \Exception('No hay usuario autenticado para realizar la importación.');
        }
        
        // REGLA DE NEGOCIO: Respetar asesor del CSV si existe y es válido
        // Si no hay asesor o no es válido, usar el usuario que hace la importación
        if (!isset($this->data['asesor_id']) || empty($this->data['asesor_id'])) {
            // No hay asesor en el CSV, usar el usuario que importa
            $this->data['asesor_id'] = $user->id;
            Log::info('LeadImporter - Asesor por defecto asignado (usuario que importa):', [
                'asesor_id' => $user->id,
                'asesor_name' => $user->name,
                'asesor_email' => $user->email,
                'razon' => 'No se especificó asesor en el CSV'
            ]);
        } else {
            // Hay asesor en el CSV, verificar que existe y pertenece al tenant
            $asesorId = $this->data['asesor_id'];
            $asesorValido = \App\Models\User::whereHas('tenants', function($query) use ($tenant) {
                $query->where('tenant_id', $tenant->id);
            })->find($asesorId);
            
            if ($asesorValido) {
                Log::info('LeadImporter - Asesor del CSV respetado:', [
                    'asesor_id' => $asesorId,
                    'asesor_name' => $asesorValido->name,
                    'asesor_email' => $asesorValido->email,
                    'importador_name' => $user->name
                ]);
            } else {
                // Asesor del CSV no válido, usar el usuario que importa
                $this->data['asesor_id'] = $user->id;
                Log::warning('LeadImporter - Asesor del CSV no válido, usando importador:', [
                    'asesor_csv_invalido' => $asesorId,
                    'asesor_asignado' => $user->id,
                    'asesor_name' => $user->name,
                    'razon' => 'Asesor del CSV no existe o no pertenece al tenant'
                ]);
            }
        }
        
        // Establecer estado por defecto si no se proporciona
        if (empty($this->data['estado'])) {
            $this->data['estado'] = 'abierto';
            Log::info('LeadImporter - Estado por defecto asignado: abierto');
        }
        
        // Registrar si hay emails duplicados (PERMITIDO - solo para información)
        if (!empty($this->data['email'])) {
            $existingLeadsCount = Lead::where('tenant_id', $this->data['tenant_id'])
                ->where('email', $this->data['email'])
                ->count();
                
            if ($existingLeadsCount > 0) {
                Log::info('LeadImporter - Email duplicado detectado (PERMITIDO):', [
                    'email' => $this->data['email'],
                    'existing_leads_count' => $existingLeadsCount,
                    'action' => 'Se creará un nuevo lead de todas formas'
                ]);
            }
        }
        
        // Validar que motivo_nulo_id solo se use cuando estado = 'perdido'
        if (!empty($this->data['motivo_nulo_id']) && $this->data['estado'] !== 'perdido') {
            Log::info('LeadImporter - Limpiando motivo_nulo_id porque estado no es perdido');
            $this->data['motivo_nulo_id'] = null;
        }
        
        // Limpiar campos vacíos para evitar problemas de validación
        foreach ($this->data as $key => $value) {
            if (is_string($value) && trim($value) === '') {
                $this->data[$key] = null;
            }
        }
        
        Log::info('LeadImporter beforeFill - Data final:', $this->data);
    }
}
