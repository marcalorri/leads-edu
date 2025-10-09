<?php

namespace App\Filament\Dashboard\Resources\Leads\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
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
                TextColumn::make('salesPhase.nombre')
                    ->label('Fase de Venta')
                    ->formatStateUsing(function ($state, $record) {
                        if (!$record->salesPhase) return '-';
                        $color = $record->salesPhase->color;
                        return new \Illuminate\Support\HtmlString(
                            '<span style="background-color: ' . $color . '; color: white; padding: 0.125rem 0.5rem; border-radius: 0.25rem; font-size: 0.6875rem; font-weight: 500; display: inline-block; white-space: nowrap; line-height: 1.25rem;">' 
                            . e($state) . 
                            '</span>'
                        );
                    })
                    ->html()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('course.codigo_curso')
                    ->label('Curso')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                TextColumn::make('campus.nombre')
                    ->label('Sede')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                SelectFilter::make('asesor')
                    ->relationship('asesor', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Asesor'),
                SelectFilter::make('course')
                    ->relationship('course', 'titulacion')
                    ->searchable()
                    ->preload()
                    ->label('Curso'),
                SelectFilter::make('salesPhase')
                    ->relationship('salesPhase', 'nombre')
                    ->searchable()
                    ->preload()
                    ->label('Fase de Venta'),
                SelectFilter::make('nullReason')
                    ->relationship('nullReason', 'nombre')
                    ->searchable()
                    ->preload()
                    ->label('Motivo Nulo'),
                SelectFilter::make('campus')
                    ->relationship('campus', 'nombre')
                    ->searchable()
                    ->preload()
                    ->label('Sede'),
                SelectFilter::make('modality')
                    ->relationship('modality', 'nombre')
                    ->searchable()
                    ->preload()
                    ->label('Modalidad'),
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
