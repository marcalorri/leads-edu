<?php

namespace App\Filament\Dashboard\Resources\ApiTokenResource\Pages;

use App\Filament\Dashboard\Resources\ApiTokenResource;
use App\Models\ApiToken;
use Filament\Actions;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;

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
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('description')
                    ->label(__('Description'))
                    ->limit(50)
                    ->placeholder(__('No description'))
                    ->wrap(),
                
                Tables\Columns\TextColumn::make('abilities')
                    ->label(__('Permissions'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => is_array($state) ? $state : [$state])
                    ->getStateUsing(fn ($record) => $record->abilities ?? []),
                
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
                DeleteAction::make()
                    ->label('')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
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
                    ['method' => 'GET', 'path' => '/catalogs/areas', 'description' => __('List areas'), 'scope' => 'leads:read'],
                    ['method' => 'GET', 'path' => '/catalogs/business-units', 'description' => __('List business units'), 'scope' => 'leads:read'],
                    ['method' => 'GET', 'path' => '/catalogs/durations', 'description' => __('List durations'), 'scope' => 'leads:read'],
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
                // LEADS EXAMPLES
                'list_leads' => [
                    'title' => __('Example: List Leads (Basic)'),
                    'method' => 'GET',
                    'url' => url('/api/v1/leads'),
                ],
                'list_leads_filtered' => [
                    'title' => __('Example: List Leads with Filters'),
                    'method' => 'GET',
                    'url' => url('/api/v1/leads?estado=abierto&curso_id=1&asesor_id=2&page=1&per_page=20'),
                ],
                'list_leads_search' => [
                    'title' => __('Example: Search Leads by Name/Email'),
                    'method' => 'GET',
                    'url' => url('/api/v1/leads?search=john&estado=abierto'),
                ],
                'list_leads_date_range' => [
                    'title' => __('Example: Filter Leads by Date Range'),
                    'method' => 'GET',
                    'url' => url('/api/v1/leads?fecha_desde=2024-01-01&fecha_hasta=2024-12-31'),
                ],
                'create_lead' => [
                    'title' => __('Example: Create Lead (Minimal)'),
                    'method' => 'POST',
                    'url' => url('/api/v1/leads'),
                    'body' => [
                        'nombre' => 'John',
                        'apellidos' => 'Doe',
                        'email' => 'john.doe@example.com',
                        'telefono' => '+1234567890',
                        'provincia_id' => 1,
                    ],
                ],
                'create_lead_complete' => [
                    'title' => __('Example: Create Lead (Complete)'),
                    'method' => 'POST',
                    'url' => url('/api/v1/leads'),
                    'body' => [
                        'nombre' => 'María',
                        'apellidos' => 'García López',
                        'email' => 'maria.garcia@example.com',
                        'telefono' => '+34612345678',
                        'provincia_id' => 'Madrid',
                        'curso_id' => 'PROG001',
                        'sede_id' => 'Main Campus',
                        'modalidad_id' => 'Online',
                        'pais' => 'España',
                        'convocatoria' => '2024-01',
                        'horario' => 'Mañana',
                        'estado' => 'abierto',
                        'asesor_id' => 'advisor@example.com',
                        'fase_venta_id' => 'Initial Contact',
                        'origen_id' => 'Web',
                        'utm_source' => 'google',
                        'utm_medium' => 'cpc',
                        'utm_campaign' => 'spring_2024',
                    ],
                ],
                'create_lead_with_names' => [
                    'title' => __('Example: Create Lead (Using Names/Codes)'),
                    'method' => 'POST',
                    'url' => url('/api/v1/leads'),
                    'body' => [
                        'nombre' => 'Carlos',
                        'apellidos' => 'Rodríguez',
                        'email' => 'carlos.rodriguez@example.com',
                        'telefono' => '+34655443322',
                        'provincia_id' => 'Barcelona',
                        'curso_id' => 'Marketing Digital',
                        'sede_id' => 'Barcelona Campus',
                        'modalidad_id' => 'Presencial',
                        'origen_id' => 'Referido',
                        'asesor_id' => 'John Doe',
                    ],
                ],
                'update_lead' => [
                    'title' => __('Example: Update Lead'),
                    'method' => 'PUT',
                    'url' => url('/api/v1/leads/123'),
                    'body' => [
                        'estado' => 'ganado',
                        'fase_venta_id' => 5,
                        'asesor_id' => 4,
                    ],
                ],
                'get_lead' => [
                    'title' => __('Example: Get Single Lead'),
                    'method' => 'GET',
                    'url' => url('/api/v1/leads/123'),
                ],
                // CATALOGS EXAMPLES
                'list_courses' => [
                    'title' => __('Example: List Courses'),
                    'method' => 'GET',
                    'url' => url('/api/v1/catalogs/courses'),
                ],
                'create_course' => [
                    'title' => __('Example: Create Course (with IDs)'),
                    'method' => 'POST',
                    'url' => url('/api/v1/catalogs/courses'),
                    'body' => [
                        'codigo_curso' => 'PROG001',
                        'titulacion' => 'Web Development Bootcamp',
                        'area_id' => 1,
                        'unidad_negocio_id' => 2,
                        'duracion_id' => 3,
                    ],
                ],
                'create_course_smart' => [
                    'title' => __('Example: Create Course (Smart Field Resolution)'),
                    'method' => 'POST',
                    'url' => url('/api/v1/catalogs/courses'),
                    'body' => [
                        'codigo_curso' => 'WEB-2024-01',
                        'titulacion' => 'Master en Desarrollo Web',
                        'area_id' => 'Tecnología',  // Nombre o código
                        'unidad_negocio_id' => 'Formación Online',  // Nombre o código
                        'duracion_id' => '12 meses',  // Nombre
                    ],
                ],
                'list_campuses' => [
                    'title' => __('Example: List Campuses'),
                    'method' => 'GET',
                    'url' => url('/api/v1/catalogs/campuses?activo=1'),
                ],
                'create_campus' => [
                    'title' => __('Example: Create Campus'),
                    'method' => 'POST',
                    'url' => url('/api/v1/catalogs/campuses'),
                    'body' => [
                        'codigo' => 'NYC',
                        'nombre' => 'New York Campus',
                        'direccion' => '123 Main Street',
                        'ciudad' => 'New York',
                        'codigo_postal' => '10001',
                        'telefono' => '+1234567890',
                        'email' => 'nyc@example.com',
                        'activo' => true,
                    ],
                ],
                'list_advisors' => [
                    'title' => __('Example: List Advisors'),
                    'method' => 'GET',
                    'url' => url('/api/v1/catalogs/asesores'),
                ],
                'get_filters' => [
                    'title' => __('Example: Get Available Filters'),
                    'method' => 'GET',
                    'url' => url('/api/v1/leads/filters'),
                ],
            ],
            'filters' => [
                'leads' => [
                    'search' => __('Search by name, email or phone (partial match)'),
                    'estado' => __('Filter by status: abierto, ganado, perdido'),
                    'curso_id' => __('Filter by course ID'),
                    'asesor_id' => __('Filter by advisor/user ID'),
                    'sede_id' => __('Filter by campus ID'),
                    'modalidad_id' => __('Filter by modality ID'),
                    'provincia_id' => __('Filter by province ID'),
                    'fase_venta_id' => __('Filter by sales phase ID'),
                    'origen_id' => __('Filter by origin ID'),
                    'fecha_desde' => __('Filter from date (YYYY-MM-DD)'),
                    'fecha_hasta' => __('Filter to date (YYYY-MM-DD)'),
                    'page' => __('Page number (default: 1)'),
                    'per_page' => __('Results per page (default: 15, max: 100)'),
                ],
                'catalogs' => [
                    'activo' => __('Filter by active status (1 or 0)'),
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
