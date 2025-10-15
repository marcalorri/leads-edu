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
                    ->label(__('Advisor'))
                    ->circular()
                    ->size(40)
                    ->getStateUsing(function ($record) {
                        return $record->asesor?->avatar_url ?? 'https://ui-avatars.com/api/?name=No+Advisor&color=9CA3AF&background=F3F4F6&size=40';
                    })
                    ->tooltip(fn ($record) => $record->asesor?->name ?? __('No advisor assigned')),
                TextColumn::make('estado')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'abierto' => 'warning',
                        'ganado' => 'success',
                        'perdido' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'abierto' => __('Open'),
                        'ganado' => __('Won'),
                        'perdido' => __('Lost'),
                        default => $state,
                    }),
                TextColumn::make('salesPhase.nombre')
                    ->label(__('Sales Phase'))
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
                    ->label(__('Course'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                TextColumn::make('campus.nombre')
                    ->label(__('Campus'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('modality.nombre')
                    ->label(__('Modality'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('province.nombre')
                    ->label(__('Province'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nombre')
                    ->label(__('First Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('apellidos')
                    ->label(__('Last Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('telefono')
                    ->label(__('Phone'))
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__('Email'))
                    ->searchable()
                    ->limit(20)
                    ->tooltip(fn ($record) => $record->email)
                    ->copyable()
                    ->copyMessage(__('Email copied'))
                    ->icon('heroicon-m-envelope'),
                TextColumn::make('origin.nombre')
                    ->label(__('Origin'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('convocatoria')
                    ->label(__('Call'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->label(__('Created'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('asesor')
                    ->relationship('asesor', 'name')
                    ->searchable()
                    ->preload()
                    ->label(__('Advisor')),
                SelectFilter::make('course')
                    ->relationship('course', 'titulacion')
                    ->searchable()
                    ->preload()
                    ->label(__('Course')),
                SelectFilter::make('salesPhase')
                    ->relationship('salesPhase', 'nombre')
                    ->searchable()
                    ->preload()
                    ->label(__('Sales Phase')),
                SelectFilter::make('nullReason')
                    ->relationship('nullReason', 'nombre')
                    ->searchable()
                    ->preload()
                    ->label(__('Null Reason')),
                SelectFilter::make('campus')
                    ->relationship('campus', 'nombre')
                    ->searchable()
                    ->preload()
                    ->label(__('Campus')),
                SelectFilter::make('modality')
                    ->relationship('modality', 'nombre')
                    ->searchable()
                    ->preload()
                    ->label(__('Modality')),
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
