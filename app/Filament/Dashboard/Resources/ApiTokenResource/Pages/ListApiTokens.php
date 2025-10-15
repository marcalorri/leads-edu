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
                ->label(__('Create Token'))
                ->url(route('filament.dashboard.resources.api-tokens.create', ['tenant' => filament()->getTenant()])),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ApiToken::query()->where('tenant_id', filament()->getTenant()->id))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('token')
                    ->label(__('Token'))
                    ->formatStateUsing(fn ($state) => substr($state, 0, 20) . '...')
                    ->copyable()
                    ->copyMessage(__('Token copied to clipboard'))
                    ->tooltip(__('Click to copy full token')),
                
                Tables\Columns\TextColumn::make('description')
                    ->label(__('Description'))
                    ->limit(50)
                    ->placeholder(__('No description')),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('Status'))
                    ->boolean()
                    ->getStateUsing(fn (ApiToken $record): bool => $record->isActive())
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\TextColumn::make('last_used_at')
                    ->label(__('Last used'))
                    ->dateTime('d/m/Y H:i')
                    ->placeholder(__('Never used'))
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created'))
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
                __('Lead Management') => [
                    ['method' => 'GET', 'path' => '/leads', 'description' => __('List leads with filters and pagination'), 'scope' => 'leads:read'],
                    ['method' => 'POST', 'path' => '/leads', 'description' => __('Create a new lead'), 'scope' => 'leads:write'],
                    ['method' => 'GET', 'path' => '/leads/{id}', 'description' => __('Get a specific lead'), 'scope' => 'leads:read'],
                    ['method' => 'PUT', 'path' => '/leads/{id}', 'description' => __('Update an existing lead'), 'scope' => 'leads:write'],
                    ['method' => 'DELETE', 'path' => '/leads/{id}', 'description' => __('Delete a lead'), 'scope' => 'leads:delete'],
                    ['method' => 'GET', 'path' => '/leads/filters', 'description' => __('Get available filters'), 'scope' => 'leads:read'],
                ],
                __('Catalogs - Read') => [
                    ['method' => 'GET', 'path' => '/catalogs/courses', 'description' => __('List tenant courses'), 'scope' => 'leads:read'],
                    ['method' => 'GET', 'path' => '/catalogs/asesores', 'description' => __('List tenant advisors'), 'scope' => 'leads:read'],
                    ['method' => 'GET', 'path' => '/catalogs/campuses', 'description' => __('List active campuses'), 'scope' => 'leads:read'],
                    ['method' => 'GET', 'path' => '/catalogs/modalities', 'description' => __('List active modalities'), 'scope' => 'leads:read'],
                    ['method' => 'GET', 'path' => '/catalogs/provinces', 'description' => __('List active provinces'), 'scope' => 'leads:read'],
                    ['method' => 'GET', 'path' => '/catalogs/sales-phases', 'description' => __('List sales phases'), 'scope' => 'leads:read'],
                    ['method' => 'GET', 'path' => '/catalogs/origins', 'description' => __('List lead origins'), 'scope' => 'leads:read'],
                    ['method' => 'GET', 'path' => '/catalogs/estados', 'description' => __('List available statuses'), 'scope' => 'leads:read'],
                ],
                __('Catalogs - Management (Admin)') => [
                    ['method' => 'POST', 'path' => '/catalogs/courses', 'description' => __('Create a new course'), 'scope' => 'leads:admin'],
                    ['method' => 'PUT', 'path' => '/catalogs/courses/{id}', 'description' => __('Update a course'), 'scope' => 'leads:admin'],
                    ['method' => 'POST', 'path' => '/catalogs/campuses', 'description' => __('Create a new campus'), 'scope' => 'leads:admin'],
                    ['method' => 'PUT', 'path' => '/catalogs/campuses/{id}', 'description' => __('Update a campus'), 'scope' => 'leads:admin'],
                    ['method' => 'POST', 'path' => '/catalogs/modalities', 'description' => __('Create a new modality'), 'scope' => 'leads:admin'],
                    ['method' => 'PUT', 'path' => '/catalogs/modalities/{id}', 'description' => __('Update a modality'), 'scope' => 'leads:admin'],
                    ['method' => 'POST', 'path' => '/catalogs/provinces', 'description' => __('Create a new province'), 'scope' => 'leads:admin'],
                    ['method' => 'PUT', 'path' => '/catalogs/provinces/{id}', 'description' => __('Update a province'), 'scope' => 'leads:admin'],
                    ['method' => 'POST', 'path' => '/catalogs/sales-phases', 'description' => __('Create a sales phase'), 'scope' => 'leads:admin'],
                    ['method' => 'PUT', 'path' => '/catalogs/sales-phases/{id}', 'description' => __('Update a sales phase'), 'scope' => 'leads:admin'],
                    ['method' => 'POST', 'path' => '/catalogs/origins', 'description' => __('Create a new origin'), 'scope' => 'leads:admin'],
                    ['method' => 'PUT', 'path' => '/catalogs/origins/{id}', 'description' => __('Update an origin'), 'scope' => 'leads:admin'],
                ],
            ],
            'scopes' => [
                'leads:read' => __('View leads and tenant catalogs'),
                'leads:write' => __('Create and modify leads'),
                'leads:delete' => __('Delete leads'),
                'leads:admin' => __('Full access (includes catalog management)'),
            ],
            'examples' => [
                'authentication' => [
                    'title' => __('Authentication'),
                    'description' => __('All requests require a Bearer Token in the Authorization header'),
                    'code' => 'Authorization: Bearer {tu_token_api}',
                ],
                'create_lead' => [
                    'title' => __('Example: Create Lead'),
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
                    'title' => __('Example: List Leads with Filters'),
                    'method' => 'GET',
                    'url' => url('/api/v1/leads?estado=nuevo&curso_id=1&page=1&per_page=15'),
                ],
                'create_campus' => [
                    'title' => __('Example: Create Campus'),
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
                ['method' => 'GET', 'path' => '/api/v1/leads', 'description' => __('List leads with filters and pagination'), 'scope' => 'leads:read'],
                ['method' => 'POST', 'path' => '/api/v1/leads', 'description' => __('Create a new lead'), 'scope' => 'leads:write'],
                ['method' => 'GET', 'path' => '/api/v1/leads/{id}', 'description' => __('Get a specific lead'), 'scope' => 'leads:read'],
                ['method' => 'PUT', 'path' => '/api/v1/leads/{id}', 'description' => __('Update a lead'), 'scope' => 'leads:write'],
                ['method' => 'DELETE', 'path' => '/api/v1/leads/{id}', 'description' => __('Delete a lead'), 'scope' => 'leads:delete'],
                ['method' => 'GET', 'path' => '/api/v1/leads/filters', 'description' => __('Get available filters'), 'scope' => 'leads:read'],
            ],
            'scopes' => [
                'leads:read' => __('View tenant leads'),
                'leads:write' => __('Create and modify leads'),
                'leads:delete' => __('Delete leads'),
                'leads:admin' => __('Full access to leads'),
            ]
        ]);
    }
}
