<?php

namespace App\Filament\Dashboard\Resources\Courses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('codigo_curso')
                    ->label('Código')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                TextColumn::make('titulacion')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->label('Titulación'),
                TextColumn::make('area.nombre')
                    ->label('Área')
                    ->sortable()
                    ->badge()
                    ->color('success'),
                TextColumn::make('businessUnit.nombre')
                    ->label('Unidad de Negocio')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('duration.nombre')
                    ->label('Duración')
                    ->sortable()
                    ->badge()
                    ->color('info'),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('area_id')
                    ->label('Área')
                    ->relationship('area', 'nombre')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('unidad_negocio_id')
                    ->label('Unidad de Negocio')
                    ->relationship('businessUnit', 'nombre')
                    ->searchable()
                    ->preload(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
