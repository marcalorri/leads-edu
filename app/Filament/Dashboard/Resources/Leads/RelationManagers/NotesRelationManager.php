<?php

namespace App\Filament\Dashboard\Resources\Leads\RelationManagers;

use App\Models\LeadNote;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NotesRelationManager extends RelationManager
{
    protected static string $relationship = 'notes';

    protected static ?string $title = 'Notas';

    protected static ?string $modelLabel = 'Nota';

    protected static ?string $pluralModelLabel = 'Notas';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Textarea::make('contenido')
                    ->label('Contenido de la nota')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull()
                    ->placeholder('Escribe aquí el contenido de la nota...'),

                Forms\Components\Toggle::make('es_importante')
                    ->label('Marcar como importante')
                    ->default(false)
                    ->helperText('Las notas importantes aparecerán destacadas'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('contenido')
            ->heading('Notas del Lead')
            ->columns([
                Tables\Columns\TextColumn::make('contenido')
                    ->label('Contenido')
                    ->searchable()
                    ->wrap()
                    ->limit(100),

                Tables\Columns\IconColumn::make('es_importante')
                    ->label('Importante')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('usuario.name')
                    ->label('Creado por')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('es_importante')
                    ->label('Importante')
                    ->placeholder('Todas las notas')
                    ->trueLabel('Solo importantes')
                    ->falseLabel('No importantes'),
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make()
                    ->label('Nueva Nota')
                    ->icon('heroicon-o-plus')
                    ->modal()
                    ->modalHeading('Crear Nueva Nota')
                    ->modalWidth('lg')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['usuario_id'] = auth()->id();
                        $data['tenant_id'] = filament()->getTenant()->id;
                        return $data;
                    }),
            ])
            ->actions([
                \Filament\Actions\EditAction::make()
                    ->modal()
                    ->modalHeading('Editar Nota')
                    ->modalWidth('lg'),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Sin notas')
            ->emptyStateDescription('Aún no hay notas registradas para este lead. Crea la primera nota para comenzar el seguimiento.')
            ->emptyStateIcon('heroicon-o-document-text');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

}
