<?php

namespace App\Filament\Dashboard\Resources\Leads\Tables;

use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

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
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('Delete selected'))
                        ->requiresConfirmation()
                        ->modalHeading(__('Delete selected leads'))
                        ->modalDescription(__('Are you sure you want to delete the selected leads? This action cannot be undone.'))
                        ->modalSubmitActionLabel(__('Delete'))
                        ->successNotificationTitle(__('Leads deleted successfully')),
                    
                    BulkAction::make('updateStatus')
                        ->label(__('Update status'))
                        ->icon('heroicon-o-pencil-square')
                        ->form([
                            Select::make('estado')
                                ->label(__('Status'))
                                ->options([
                                    'abierto' => __('Open'),
                                    'ganado' => __('Won'),
                                    'perdido' => __('Lost'),
                                ])
                                ->required()
                                ->live(),
                            Select::make('motivo_nulo_id')
                                ->label(__('Null Reason'))
                                ->relationship('nullReason', 'nombre')
                                ->searchable()
                                ->preload()
                                ->visible(fn (callable $get) => $get('estado') === 'perdido')
                                ->required(fn (callable $get) => $get('estado') === 'perdido'),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $now = now();
                            
                            foreach ($records as $record) {
                                $updateData = ['estado' => $data['estado']];
                                
                                if ($data['estado'] === 'ganado') {
                                    $updateData['fecha_ganado'] = $now;
                                    $updateData['fecha_perdido'] = null;
                                    $updateData['motivo_nulo_id'] = null;
                                } elseif ($data['estado'] === 'perdido') {
                                    $updateData['fecha_perdido'] = $now;
                                    $updateData['fecha_ganado'] = null;
                                    $updateData['motivo_nulo_id'] = $data['motivo_nulo_id'] ?? null;
                                } else {
                                    $updateData['fecha_ganado'] = null;
                                    $updateData['fecha_perdido'] = null;
                                    $updateData['motivo_nulo_id'] = null;
                                }
                                
                                $record->update($updateData);
                            }
                            
                            Notification::make()
                                ->success()
                                ->title(__('Status updated'))
                                ->body(__('Status updated for :count leads', ['count' => $records->count()]))
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    
                    BulkAction::make('updateSalesPhase')
                        ->label(__('Update sales phase'))
                        ->icon('heroicon-o-arrow-path')
                        ->form([
                            Select::make('fase_venta_id')
                                ->label(__('Sales Phase'))
                                ->relationship('salesPhase', 'nombre')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $records->each->update(['fase_venta_id' => $data['fase_venta_id']]);
                            
                            Notification::make()
                                ->success()
                                ->title(__('Sales phase updated'))
                                ->body(__('Sales phase updated for :count leads', ['count' => $records->count()]))
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    
                    BulkAction::make('assignAdvisor')
                        ->label(__('Assign advisor'))
                        ->icon('heroicon-o-user-plus')
                        ->form([
                            Select::make('asesor_id')
                                ->label(__('Advisor'))
                                ->relationship('asesor', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $records->each->update(['asesor_id' => $data['asesor_id']]);
                            
                            Notification::make()
                                ->success()
                                ->title(__('Advisor assigned'))
                                ->body(__('Advisor assigned to :count leads', ['count' => $records->count()]))
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    
                    BulkAction::make('updateCourse')
                        ->label(__('Update course'))
                        ->icon('heroicon-o-academic-cap')
                        ->form([
                            Select::make('curso_id')
                                ->label(__('Course'))
                                ->relationship('course', 'titulacion')
                                ->searchable()
                                ->preload()
                                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->codigo_curso} - {$record->titulacion}")
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $records->each->update(['curso_id' => $data['curso_id']]);
                            
                            Notification::make()
                                ->success()
                                ->title(__('Course updated'))
                                ->body(__('Course updated for :count leads', ['count' => $records->count()]))
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
