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

    protected static ?string $title = 'Notes';

    protected static ?string $modelLabel = 'Note';

    protected static ?string $pluralModelLabel = 'Notes';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Textarea::make('contenido')
                    ->label(__('Note content'))
                    ->required()
                    ->rows(4)
                    ->columnSpanFull()
                    ->placeholder(__('Write the note content here...')),

                Forms\Components\Toggle::make('es_importante')
                    ->label(__('Mark as important'))
                    ->default(false)
                    ->helperText(__('Important notes will appear highlighted')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('contenido')
            ->heading(__('Lead Notes'))
            ->columns([
                Tables\Columns\TextColumn::make('contenido')
                    ->label(__('Content'))
                    ->searchable()
                    ->wrap()
                    ->limit(100),

                Tables\Columns\IconColumn::make('es_importante')
                    ->label(__('Important'))
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('usuario.name')
                    ->label(__('Created by'))
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Date'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('es_importante')
                    ->label(__('Important'))
                    ->placeholder(__('All notes'))
                    ->trueLabel(__('Important only'))
                    ->falseLabel(__('Not important')),
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make()
                    ->label(__('New Note'))
                    ->icon('heroicon-o-plus')
                    ->modal()
                    ->modalHeading(__('Create New Note'))
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
                    ->modalHeading(__('Edit Note'))
                    ->modalWidth('lg'),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading(__('No notes'))
            ->emptyStateDescription(__('There are no notes registered for this lead yet. Create the first note to start tracking.'))
            ->emptyStateIcon('heroicon-o-document-text');
    }

    public function isReadOnly(): bool
    {
        return false;
    }

}
