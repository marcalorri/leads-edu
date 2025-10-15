<?php

namespace App\Filament\Dashboard\Resources\Leads\RelationManagers;

use App\Models\LeadEvent;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    protected static ?string $title = 'Events and Tasks';

    protected static ?string $modelLabel = 'Event';

    protected static ?string $pluralModelLabel = 'Events';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('titulo')
                    ->label(__('Event title'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('e.g.: Follow-up call, Information meeting...'))
                    ->columnSpanFull(),

                Forms\Components\DateTimePicker::make('fecha_programada')
                    ->label(__('Scheduled date and time'))
                    ->required()
                    ->default(now()->addHour())
                    ->helperText(__('When this event should take place')),

                Forms\Components\TextInput::make('duracion_estimada')
                    ->label(__('Duration (minutes)'))
                    ->numeric()
                    ->default(15)
                    ->minValue(5)
                    ->maxValue(480)
                    ->step(5)
                    ->suffix('min')
                    ->helperText(__('Estimated event duration in minutes')),

                Forms\Components\Select::make('estado')
                    ->label(__('Status'))
                    ->options([
                        'pendiente' => __('Pending'),
                        'completada' => __('Completed'),
                        'cancelada' => __('Cancelled'),
                    ])
                    ->required()
                    ->default('pendiente')
                    ->live(),

                Forms\Components\Textarea::make('descripcion')
                    ->label(__('Description'))
                    ->rows(3)
                    ->placeholder(__('Additional details about the event...'))
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('resultado')
                    ->label(__('Result'))
                    ->rows(4)
                    ->placeholder(__('What happened in this event? Result, conclusions, next steps...'))
                    ->columnSpanFull()
                    ->visible(fn (callable $get) => $get('estado') === 'completada'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('titulo')
            ->heading(__('Lead Events and Tasks'))
            ->columns([
                Tables\Columns\TextColumn::make('titulo')
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(50),

                Tables\Columns\TextColumn::make('fecha_programada')
                    ->label(__('Scheduled date'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->color(fn ($record): string => 
                        $record->fecha_programada < now() && $record->estado === 'pendiente' 
                            ? 'danger' 
                            : 'primary'
                    ),

                Tables\Columns\TextColumn::make('estado')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pendiente' => 'warning',
                        'completada' => 'success',
                        'cancelada' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('duracion_estimada')
                    ->label(__('Duration'))
                    ->suffix(' min')
                    ->placeholder(__('Not defined'))
                    ->toggleable(),

                Tables\Columns\TextColumn::make('descripcion')
                    ->label(__('Description'))
                    ->limit(60)
                    ->placeholder(__('No description'))
                    ->toggleable(),

                Tables\Columns\TextColumn::make('usuario.name')
                    ->label(__('Assigned to'))
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->label(__('Status'))
                    ->options([
                        'pendiente' => __('Pending'),
                        'completada' => __('Completed'),
                        'cancelada' => __('Cancelled'),
                    ]),

                Tables\Filters\Filter::make('vencidos')
                    ->label(__('Overdue'))
                    ->query(fn (Builder $query): Builder => 
                        $query->where('fecha_programada', '<', now())
                              ->where('estado', 'pendiente')
                    ),

                Tables\Filters\Filter::make('hoy')
                    ->label(__('For today'))
                    ->query(fn (Builder $query): Builder => 
                        $query->whereDate('fecha_programada', today())
                    ),
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make()
                    ->label(__('New Event'))
                    ->icon('heroicon-o-plus')
                    ->modal()
                    ->modalHeading(__('Create New Event'))
                    ->modalWidth('lg')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['usuario_id'] = auth()->id();
                        $data['tenant_id'] = filament()->getTenant()->id;
                        
                        // Si se marca como completada, establecer fecha de finalización
                        if ($data['estado'] === 'completada') {
                            $data['fecha_completada'] = now();
                        }
                        
                        return $data;
                    }),
            ])
            ->actions([
                \Filament\Actions\EditAction::make()
                    ->modal()
                    ->modalHeading(__('Edit Event'))
                    ->modalWidth('lg')
                    ->mutateFormDataUsing(function (array $data): array {
                        // Si se marca como completada, establecer fecha de finalización
                        if ($data['estado'] === 'completada') {
                            $data['fecha_completada'] = now();
                        }
                        
                        return $data;
                    }),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->defaultSort('fecha_programada', 'asc')
            ->emptyStateHeading(__('No scheduled events'))
            ->emptyStateDescription(__('Create the first event to schedule tasks and track the lead.'))
            ->emptyStateIcon('heroicon-o-calendar-days');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

}
