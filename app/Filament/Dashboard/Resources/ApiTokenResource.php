<?php

namespace App\Filament\Dashboard\Resources;

use App\Models\ApiToken;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ApiTokenResource extends Resource
{
    protected static ?string $model = ApiToken::class;

    protected static ?string $navigationLabel = 'API Tokens';

    protected static ?int $navigationSort = 90;

    public static function getNavigationGroup(): ?string
    {
        return 'Gestión';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-key';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre del Token')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->maxLength(500),

                Forms\Components\CheckboxList::make('abilities')
                    ->label('Permisos')
                    ->required()
                    ->options([
                        'leads:read' => 'Ver leads',
                        'leads:write' => 'Crear y modificar leads',
                        'leads:delete' => 'Eliminar leads',
                        'leads:admin' => 'Acceso completo',
                    ])
                    ->columns(2),

                Forms\Components\DateTimePicker::make('expires_at')
                    ->label('Fecha de Expiración')
                    ->helperText('Opcional'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tokenable.name')
                    ->label('Usuario'),

                Tables\Columns\TextColumn::make('abilities')
                    ->label('Permisos')
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Estado')
                    ->boolean()
                    ->getStateUsing(fn (ApiToken $record): bool => $record->isActive()),

                Tables\Columns\TextColumn::make('last_used_at')
                    ->label('Último uso')
                    ->dateTime()
                    ->placeholder('Nunca'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime(),
            ])
            ->actions([
                // Acciones simplificadas para compatibilidad
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('tenant_id', filament()->getTenant()->id);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Dashboard\Resources\ApiTokenResource\Pages\ListApiTokens::route('/'),
            'create' => \App\Filament\Dashboard\Resources\ApiTokenResource\Pages\CreateApiToken::route('/create'),
            'view' => \App\Filament\Dashboard\Resources\ApiTokenResource\Pages\ViewApiToken::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->canManageConfiguration(filament()->getTenant()) ?? false;
    }
}
