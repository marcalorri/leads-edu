<?php

namespace App\Filament\Dashboard\Resources\Provinces\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProvincesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tenant.name')
                    ->searchable()
                    ->label(__('Tenant')),
                TextColumn::make('nombre')
                    ->searchable()
                    ->label(__('Name')),
                TextColumn::make('codigo')
                    ->searchable()
                    ->label(__('Code')),
                TextColumn::make('codigo_ine')
                    ->searchable()
                    ->label(__('INE Code')),
                TextColumn::make('comunidad_autonoma')
                    ->searchable()
                    ->label(__('Autonomous Community')),
                IconColumn::make('activo')
                    ->boolean()
                    ->label(__('Active')),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('Created')),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('Updated')),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
