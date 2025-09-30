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

    protected static ?string $title = 'Eventos y Tareas';

    protected static ?string $modelLabel = 'Evento';

    protected static ?string $pluralModelLabel = 'Eventos';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('titulo')
                    ->label('Título del evento')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ej: Llamada de seguimiento, Reunión informativa...')
                    ->columnSpanFull(),

                Forms\Components\DateTimePicker::make('fecha_programada')
                    ->label('Fecha y hora programada')
                    ->required()
                    ->default(now()->addHour())
                    ->helperText('Cuándo debe realizarse este evento'),

                Forms\Components\TextInput::make('duracion_estimada')
                    ->label('Duración (minutos)')
                    ->numeric()
                    ->default(15)
                    ->minValue(5)
                    ->maxValue(480)
                    ->step(5)
                    ->suffix('min')
                    ->helperText('Duración estimada del evento en minutos'),

                Forms\Components\Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'completada' => 'Completada',
                        'cancelada' => 'Cancelada',
                    ])
                    ->required()
                    ->default('pendiente')
                    ->live(),

                Forms\Components\Textarea::make('descripcion')
                    ->label('Descripción')
                    ->rows(3)
                    ->placeholder('Detalles adicionales sobre el evento...')
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('resultado')
                    ->label('Resultado')
                    ->rows(4)
                    ->placeholder('¿Qué pasó en este evento? Resultado, conclusiones, próximos pasos...')
                    ->columnSpanFull()
                    ->visible(fn (callable $get) => $get('estado') === 'completada'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('titulo')
            ->heading('Eventos y Tareas del Lead')
            ->columns([
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(50),

                Tables\Columns\TextColumn::make('fecha_programada')
                    ->label('Fecha programada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->color(fn ($record): string => 
                        $record->fecha_programada < now() && $record->estado === 'pendiente' 
                            ? 'danger' 
                            : 'primary'
                    ),

                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pendiente' => 'warning',
                        'completada' => 'success',
                        'cancelada' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('duracion_estimada')
                    ->label('Duración')
                    ->suffix(' min')
                    ->placeholder('No definida')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->limit(60)
                    ->placeholder('Sin descripción')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('usuario.name')
                    ->label('Asignado a')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'completada' => 'Completada',
                        'cancelada' => 'Cancelada',
                    ]),

                Tables\Filters\Filter::make('vencidos')
                    ->label('Vencidos')
                    ->query(fn (Builder $query): Builder => 
                        $query->where('fecha_programada', '<', now())
                              ->where('estado', 'pendiente')
                    ),

                Tables\Filters\Filter::make('hoy')
                    ->label('Para hoy')
                    ->query(fn (Builder $query): Builder => 
                        $query->whereDate('fecha_programada', today())
                    ),
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make()
                    ->label('Nuevo Evento')
                    ->icon('heroicon-o-plus')
                    ->modal()
                    ->modalHeading('Crear Nuevo Evento')
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
                    ->modalHeading('Editar Evento')
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
            ->emptyStateHeading('Sin eventos programados')
            ->emptyStateDescription('Crea el primer evento para programar tareas y hacer seguimiento del lead.')
            ->emptyStateIcon('heroicon-o-calendar-days');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

}
