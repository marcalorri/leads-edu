<?php

namespace App\Filament\Dashboard\Resources\ApiTokenResource\Pages;

use App\Filament\Dashboard\Resources\ApiTokenResource;
use App\Models\ApiToken;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;

class ListApiTokens extends ListRecords
{
    protected static string $resource = ApiTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Crear Token')
                ->url(route('filament.dashboard.resources.api-tokens.create', ['tenant' => filament()->getTenant()])),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ApiToken::query()->where('tenant_id', filament()->getTenant()->id))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('token')
                    ->label('Token')
                    ->formatStateUsing(fn ($state) => substr($state, 0, 20) . '...')
                    ->copyable()
                    ->copyMessage('Token copiado al portapapeles')
                    ->tooltip('Haz clic para copiar el token completo'),
                
                Tables\Columns\TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50)
                    ->placeholder('Sin descripción'),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Estado')
                    ->boolean()
                    ->getStateUsing(fn (ApiToken $record): bool => $record->isActive())
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\TextColumn::make('last_used_at')
                    ->label('Último uso')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Nunca usado')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->actions([
                // Acciones simplificadas para compatibilidad
            ]);
    }

    protected function getFooterWidgets(): array
    {
        return [];
    }

    public function getFooter(): \Illuminate\Contracts\View\View
    {
        return view('filament.dashboard.resources.api-token-resource.pages.api-documentation', [
            'baseUrl' => url('/api/v1'),
            'endpointGroups' => [
                'Gestión de Leads' => [
                    ['method' => 'GET', 'path' => '/leads', 'description' => 'Listar leads con filtros y paginación', 'scope' => 'leads:read'],
                    ['method' => 'POST', 'path' => '/leads', 'description' => 'Crear un nuevo lead', 'scope' => 'leads:write'],
                    ['method' => 'GET', 'path' => '/leads/{id}', 'description' => 'Obtener un lead específico', 'scope' => 'leads:read'],
                    ['method' => 'PUT', 'path' => '/leads/{id}', 'description' => 'Actualizar un lead existente', 'scope' => 'leads:write'],
                    ['method' => 'DELETE', 'path' => '/leads/{id}', 'description' => 'Eliminar un lead', 'scope' => 'leads:delete'],
                    ['method' => 'GET', 'path' => '/leads/filters', 'description' => 'Obtener filtros disponibles', 'scope' => 'leads:read'],
                ],
                'Catálogos - Lectura' => [
                    ['method' => 'GET', 'path' => '/catalogs/courses', 'description' => 'Listar cursos del tenant', 'scope' => 'leads:read'],
                    ['method' => 'GET', 'path' => '/catalogs/asesores', 'description' => 'Listar asesores del tenant', 'scope' => 'leads:read'],
                    ['method' => 'GET', 'path' => '/catalogs/campuses', 'description' => 'Listar sedes activas', 'scope' => 'leads:read'],
                    ['method' => 'GET', 'path' => '/catalogs/modalities', 'description' => 'Listar modalidades activas', 'scope' => 'leads:read'],
                    ['method' => 'GET', 'path' => '/catalogs/provinces', 'description' => 'Listar provincias activas', 'scope' => 'leads:read'],
                    ['method' => 'GET', 'path' => '/catalogs/sales-phases', 'description' => 'Listar fases de venta', 'scope' => 'leads:read'],
                    ['method' => 'GET', 'path' => '/catalogs/origins', 'description' => 'Listar orígenes de leads', 'scope' => 'leads:read'],
                    ['method' => 'GET', 'path' => '/catalogs/estados', 'description' => 'Listar estados disponibles', 'scope' => 'leads:read'],
                ],
                'Catálogos - Gestión (Admin)' => [
                    ['method' => 'POST', 'path' => '/catalogs/courses', 'description' => 'Crear un nuevo curso', 'scope' => 'leads:admin'],
                    ['method' => 'PUT', 'path' => '/catalogs/courses/{id}', 'description' => 'Actualizar un curso', 'scope' => 'leads:admin'],
                    ['method' => 'POST', 'path' => '/catalogs/campuses', 'description' => 'Crear una nueva sede', 'scope' => 'leads:admin'],
                    ['method' => 'PUT', 'path' => '/catalogs/campuses/{id}', 'description' => 'Actualizar una sede', 'scope' => 'leads:admin'],
                    ['method' => 'POST', 'path' => '/catalogs/modalities', 'description' => 'Crear una nueva modalidad', 'scope' => 'leads:admin'],
                    ['method' => 'PUT', 'path' => '/catalogs/modalities/{id}', 'description' => 'Actualizar una modalidad', 'scope' => 'leads:admin'],
                    ['method' => 'POST', 'path' => '/catalogs/provinces', 'description' => 'Crear una nueva provincia', 'scope' => 'leads:admin'],
                    ['method' => 'PUT', 'path' => '/catalogs/provinces/{id}', 'description' => 'Actualizar una provincia', 'scope' => 'leads:admin'],
                    ['method' => 'POST', 'path' => '/catalogs/sales-phases', 'description' => 'Crear una fase de venta', 'scope' => 'leads:admin'],
                    ['method' => 'PUT', 'path' => '/catalogs/sales-phases/{id}', 'description' => 'Actualizar una fase de venta', 'scope' => 'leads:admin'],
                    ['method' => 'POST', 'path' => '/catalogs/origins', 'description' => 'Crear un nuevo origen', 'scope' => 'leads:admin'],
                    ['method' => 'PUT', 'path' => '/catalogs/origins/{id}', 'description' => 'Actualizar un origen', 'scope' => 'leads:admin'],
                ],
            ],
            'scopes' => [
                'leads:read' => 'Ver leads y catálogos del tenant',
                'leads:write' => 'Crear y modificar leads',
                'leads:delete' => 'Eliminar leads',
                'leads:admin' => 'Acceso completo (incluye gestión de catálogos)',
            ],
            'examples' => [
                'authentication' => [
                    'title' => 'Autenticación',
                    'description' => 'Todas las peticiones requieren un Bearer Token en el header Authorization',
                    'code' => 'Authorization: Bearer {tu_token_api}',
                ],
                'create_lead' => [
                    'title' => 'Ejemplo: Crear Lead',
                    'method' => 'POST',
                    'url' => url('/api/v1/leads'),
                    'body' => [
                        'nombre' => 'Juan',
                        'apellidos' => 'Pérez',
                        'email' => 'juan@example.com',
                        'telefono' => '612345678',
                        'estado' => 'nuevo',
                        'curso_id' => 1,
                        'asesor_id' => 2,
                        'sede_id' => 1,
                    ],
                ],
                'list_leads' => [
                    'title' => 'Ejemplo: Listar Leads con Filtros',
                    'method' => 'GET',
                    'url' => url('/api/v1/leads?estado=nuevo&curso_id=1&page=1&per_page=15'),
                ],
                'create_campus' => [
                    'title' => 'Ejemplo: Crear Sede',
                    'method' => 'POST',
                    'url' => url('/api/v1/catalogs/campuses'),
                    'body' => [
                        'codigo' => 'MAD',
                        'nombre' => 'Madrid Centro',
                        'direccion' => 'Calle Gran Vía 123',
                        'ciudad' => 'Madrid',
                        'activo' => true,
                    ],
                ],
            ],
            'rateLimit' => [
                'perToken' => '1000 requests/hora',
                'perTenant' => '10000 requests/hora',
            ],
        ]);
    }


    public function getViewData(): array
    {
        return array_merge(parent::getViewData(), [
            'showApiDocumentation' => true,
            'baseUrl' => url('/api/v1'),
            'endpoints' => [
                ['method' => 'GET', 'path' => '/api/v1/leads', 'description' => 'Listar leads con filtros y paginación', 'scope' => 'leads:read'],
                ['method' => 'POST', 'path' => '/api/v1/leads', 'description' => 'Crear un nuevo lead', 'scope' => 'leads:write'],
                ['method' => 'GET', 'path' => '/api/v1/leads/{id}', 'description' => 'Obtener un lead específico', 'scope' => 'leads:read'],
                ['method' => 'PUT', 'path' => '/api/v1/leads/{id}', 'description' => 'Actualizar un lead', 'scope' => 'leads:write'],
                ['method' => 'DELETE', 'path' => '/api/v1/leads/{id}', 'description' => 'Eliminar un lead', 'scope' => 'leads:delete'],
                ['method' => 'GET', 'path' => '/api/v1/leads/filters', 'description' => 'Obtener filtros disponibles', 'scope' => 'leads:read'],
            ],
            'scopes' => [
                'leads:read' => 'Ver leads del tenant',
                'leads:write' => 'Crear y modificar leads',
                'leads:delete' => 'Eliminar leads',
                'leads:admin' => 'Acceso completo a leads',
            ]
        ]);
    }
}
