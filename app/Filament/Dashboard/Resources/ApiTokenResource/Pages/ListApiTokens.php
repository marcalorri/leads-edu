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
