<?php

namespace App\Filament\Dashboard\Resources\Leads\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LeadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('asesor.avatar')
                    ->label('Asesor')
                    ->circular()
                    ->size(40)
                    ->getStateUsing(function ($record) {
                        return $record->asesor?->avatar_url ?? 'https://ui-avatars.com/api/?name=Sin+Asesor&color=9CA3AF&background=F3F4F6&size=40';
                    })
                    ->tooltip(fn ($record) => $record->asesor?->name ?? 'Sin asesor asignado'),
                TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'abierto' => 'warning',
                        'ganado' => 'success',
                        'perdido' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'abierto' => 'Abierto',
                        'ganado' => 'Ganado',
                        'perdido' => 'Perdido',
                        default => $state,
                    }),
                TextColumn::make('course.codigo_curso')
                    ->label('Curso')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                TextColumn::make('campus.nombre')
                    ->label('Sede')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('modality.nombre')
                    ->label('Modalidad')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('province.nombre')
                    ->label('Provincia')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    ->limit(20)
                    ->tooltip(fn ($record) => $record->email)
                    ->copyable()
                    ->copyMessage('Email copiado')
                    ->icon('heroicon-m-envelope'),
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
                ViewAction::make()
                    ->label(''),
                EditAction::make()
                    ->label(''),
                DeleteAction::make()
                    ->label(''),
            ])
            ->recordUrl(fn ($record) => route('filament.dashboard.resources.leads.edit', [
                'tenant' => filament()->getTenant(),
                'record' => $record
            ]))
            ->defaultSort('created_at', 'desc');
    }
}
