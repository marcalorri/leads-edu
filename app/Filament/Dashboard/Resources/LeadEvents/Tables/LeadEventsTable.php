<?php

namespace App\Filament\Dashboard\Resources\LeadEvents\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class LeadEventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lead.id')
                    ->searchable()
                    ->label(__('Lead')),
                TextColumn::make('usuario.name')
                    ->searchable()
                    ->label(__('User')),
                TextColumn::make('titulo')
                    ->searchable()
                    ->label(__('Title')),
                TextColumn::make('tipo')
                    ->badge()
                    ->label(__('Type')),
                TextColumn::make('estado')
                    ->badge()
                    ->label(__('Status')),
                TextColumn::make('prioridad')
                    ->badge()
                    ->label(__('Priority')),
                TextColumn::make('fecha_programada')
                    ->dateTime()
                    ->sortable()
                    ->label(__('Scheduled Date')),
                TextColumn::make('fecha_completada')
                    ->dateTime()
                    ->sortable()
                    ->label(__('Completed Date')),
                TextColumn::make('duracion_estimada')
                    ->numeric()
                    ->sortable()
                    ->label(__('Duration')),
                IconColumn::make('requiere_recordatorio')
                    ->boolean()
                    ->label(__('Reminder')),
                TextColumn::make('minutos_recordatorio')
                    ->numeric()
                    ->sortable()
                    ->label(__('Minutes')),
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
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(__('Deleted')),
            ])
            ->filters([
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
