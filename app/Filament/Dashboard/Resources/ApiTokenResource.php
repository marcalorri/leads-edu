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
        return __('Management');
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
                    ->label(__('Token Name'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label(__('Description'))
                    ->maxLength(500),

                Forms\Components\CheckboxList::make('abilities')
                    ->label(__('Permissions'))
                    ->required()
                    ->options([
                        'leads:read' => __('View leads'),
                        'leads:write' => __('Create and modify leads'),
                        'leads:delete' => __('Delete leads'),
                        'leads:admin' => __('Full access'),
                    ])
                    ->columns(2),

                Forms\Components\DateTimePicker::make('expires_at')
                    ->label(__('Expiration Date'))
                    ->helperText(__('Optional')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('tokenable.name')
                    ->label(__('User')),

                Tables\Columns\TextColumn::make('abilities')
                    ->label(__('Permissions'))
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('Status'))
                    ->boolean()
                    ->getStateUsing(fn (ApiToken $record): bool => $record->isActive()),

                Tables\Columns\TextColumn::make('last_used_at')
                    ->label(__('Last used'))
                    ->dateTime()
                    ->placeholder(__('Never')),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created'))
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
