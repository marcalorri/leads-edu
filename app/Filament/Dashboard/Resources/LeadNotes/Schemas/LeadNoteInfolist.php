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
                    ->label(__('Tenant')),
                TextEntry::make('lead.nombre')
                    ->label(__('Lead')),
                TextEntry::make('usuario.name')
                    ->label(__('User')),
                TextEntry::make('titulo')
                    ->label(__('Title'))
                    ->placeholder('-'),
                TextEntry::make('contenido')
                    ->label(__('Content'))
                    ->columnSpanFull(),
                TextEntry::make('tipo')
                    ->label(__('Type'))
                    ->badge(),
                IconEntry::make('es_importante')
                    ->label(__('Important'))
                    ->boolean(),
                TextEntry::make('fecha_seguimiento')
                    ->label(__('Follow-up Date'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->label(__('Created'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label(__('Updated'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->label(__('Deleted'))
                    ->dateTime()
                    ->visible(fn (LeadNote $record): bool => $record->trashed()),
            ]);
    }
}
