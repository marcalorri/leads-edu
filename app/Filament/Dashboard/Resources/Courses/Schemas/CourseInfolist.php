<?php

namespace App\Filament\Dashboard\Resources\Courses\Schemas;

use App\Models\Course;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CourseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tenant.name')
                    ->label(__('Tenant')),
                TextEntry::make('codigo_curso')
                    ->label(__('Course Code')),
                TextEntry::make('titulacion')
                    ->label(__('Degree')),
                TextEntry::make('area.nombre')
                    ->label(__('Area')),
                TextEntry::make('businessUnit.nombre')
                    ->label(__('Business Unit')),
                TextEntry::make('duration.nombre')
                    ->label(__('Duration')),
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
                    ->visible(fn (Course $record): bool => $record->trashed()),
            ]);
    }
}
