<?php

namespace App\Filament\Dashboard\Resources\LeadNotes\Schemas;

use App\Models\LeadNote;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LeadNoteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tenant.name')
                    ->label('Tenant'),
                TextEntry::make('lead.id')
                    ->label('Lead'),
                TextEntry::make('usuario.name')
                    ->label('Usuario'),
                TextEntry::make('titulo')
                    ->placeholder('-'),
                TextEntry::make('contenido')
                    ->columnSpanFull(),
                TextEntry::make('tipo')
                    ->badge(),
                IconEntry::make('es_importante')
                    ->boolean(),
                TextEntry::make('fecha_seguimiento')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (LeadNote $record): bool => $record->trashed()),
            ]);
    }
}
