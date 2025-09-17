<?php

namespace App\Filament\Dashboard\Resources\Leads\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class LeadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('asesor.name')
                    ->label('Asesor')
                    ->searchable()
                    ->sortable(),
                SelectColumn::make('estado')
                    ->options([
                        'abierto' => 'Abierto',
                        'ganado' => 'Ganado',
                        'perdido' => 'Perdido',
                    ])
                    ->label('Estado'),
                TextColumn::make('course.codigo_curso')
                    ->label('Curso')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('campus.nombre')
                    ->label('Sede')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('modality.nombre')
                    ->label('Modalidad')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('province.nombre')
                    ->label('Provincia')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('apellidos')
                    ->label('Apellidos')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->limit(25),
                TextColumn::make('origin.nombre')
                    ->label('Origen')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('convocatoria')
                    ->label('Convocatoria')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->label('Creado')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('estado')
                    ->options([
                        'abierto' => 'Abierto',
                        'ganado' => 'Ganado',
                        'perdido' => 'Perdido',
                    ])
                    ->label('Estado'),
                SelectFilter::make('asesor')
                    ->relationship('asesor', 'name')
                    ->label('Asesor'),
                SelectFilter::make('course')
                    ->relationship('course', 'titulacion')
                    ->label('Curso'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
