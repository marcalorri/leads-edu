<?php

namespace App\Filament\Dashboard\Resources\Durations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DurationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->searchable()
                    ->sortable()
                    ->label('Nombre'),
                TextColumn::make('horas_totales')
                    ->numeric()
                    ->sortable()
                    ->suffix(' h')
                    ->label('Horas Totales'),
                TextColumn::make('tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'horas' => 'gray',
                        'dias' => 'warning',
                        'semanas' => 'success',
                        'meses' => 'info',
                        'años' => 'danger',
                        default => 'gray',
                    })
                    ->label('Tipo'),
                TextColumn::make('valor_numerico')
                    ->numeric()
                    ->label('Valor'),
                IconColumn::make('activo')
                    ->boolean()
                    ->sortable()
                    ->label('Activo'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Creado'),
            ])
            ->filters([
                TernaryFilter::make('activo')
                    ->label('Estado')
                    ->placeholder('Todos')
                    ->trueLabel('Activos')
                    ->falseLabel('Inactivos'),
                SelectFilter::make('tipo')
                    ->options([
                        'horas' => 'Horas',
                        'dias' => 'Días',
                        'semanas' => 'Semanas',
                        'meses' => 'Meses',
                        'años' => 'Años',
                    ])
                    ->label('Tipo'),
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
