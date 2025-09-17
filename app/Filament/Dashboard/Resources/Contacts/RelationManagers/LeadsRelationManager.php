<?php

namespace App\Filament\Dashboard\Resources\Contacts\RelationManagers;

use App\Filament\Dashboard\Resources\Leads\LeadResource;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Table;

class LeadsRelationManager extends RelationManager
{
    protected static string $relationship = 'leads';

    protected static ?string $relatedResource = LeadResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                SelectColumn::make('estado')
                    ->options([
                        'abierto' => 'Abierto',
                        'ganado' => 'Ganado',
                        'perdido' => 'Perdido',
                    ])
                    ->label('Estado'),
                TextColumn::make('course.codigo_curso')
                    ->label('Curso')
                    ->searchable(),
                TextColumn::make('campus.nombre')
                    ->label('Sede')
                    ->searchable(),
                TextColumn::make('asesor.name')
                    ->label('Asesor')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
                    ->label('Creado')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
