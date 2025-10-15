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
                    ->label(__('Name')),
                TextColumn::make('horas_totales')
                    ->numeric()
                    ->sortable()
                    ->suffix(' h')
                    ->label(__('Total Hours')),
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
                    ->label(__('Type')),
                TextColumn::make('valor_numerico')
                    ->numeric()
                    ->label(__('Value')),
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
                SelectFilter::make('tipo')
                    ->options([
                        'horas' => __('Hours'),
                        'dias' => __('Days'),
                        'semanas' => __('Weeks'),
                        'meses' => __('Months'),
                        'años' => __('Years'),
                    ])
                    ->label(__('Type')),
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
