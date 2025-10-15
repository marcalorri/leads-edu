<?php

namespace App\Filament\Dashboard\Resources\BusinessUnits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BusinessUnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('codigo')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->label(__('Code')),
                TextColumn::make('nombre')
                    ->searchable()
                    ->sortable()
                    ->label(__('Name')),
                TextColumn::make('descripcion')
                    ->limit(50)
                    ->toggleable()
                    ->label(__('Description')),
                TextColumn::make('responsable')
                    ->searchable()
                    ->toggleable()
                    ->label(__('Manager')),
                IconColumn::make('activo')
                    ->boolean()
                    ->sortable()
                    ->label(__('Active')),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('Created')),
            ])
            ->filters([
                TernaryFilter::make('activo')
                    ->label(__('Status'))
                    ->placeholder(__('All'))
                    ->trueLabel(__('Active'))
                    ->falseLabel(__('Inactive')),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('nombre');
    }
}
