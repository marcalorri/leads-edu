<?php

namespace App\Filament\Dashboard\Resources\Campuses\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CampusesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('codigo')
                    ->searchable()
                    ->sortable()
                    ->label(__('Code')),
                TextColumn::make('nombre')
                    ->searchable()
                    ->sortable()
                    ->label(__('Name')),
                TextColumn::make('ciudad')
                    ->searchable()
                    ->label(__('City')),
                TextColumn::make('telefono')
                    ->label(__('Phone')),
                TextColumn::make('email')
                    ->label(__('Email')),
                TextColumn::make('responsable')
                    ->label(__('Manager')),
                IconColumn::make('activo')
                    ->boolean()
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
                    ->trueLabel(__('Active only'))
                    ->falseLabel(__('Inactive only'))
                    ->native(false),
            ])
            ->defaultSort('nombre')
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
