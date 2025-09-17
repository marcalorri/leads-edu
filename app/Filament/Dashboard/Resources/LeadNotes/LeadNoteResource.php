<?php

namespace App\Filament\Dashboard\Resources\LeadNotes;

use App\Filament\Dashboard\Resources\LeadNotes\Pages\CreateLeadNote;
use App\Filament\Dashboard\Resources\LeadNotes\Pages\EditLeadNote;
use App\Filament\Dashboard\Resources\LeadNotes\Pages\ListLeadNotes;
use App\Filament\Dashboard\Resources\LeadNotes\Pages\ViewLeadNote;
use App\Filament\Dashboard\Resources\LeadNotes\Schemas\LeadNoteForm;
use App\Filament\Dashboard\Resources\LeadNotes\Schemas\LeadNoteInfolist;
use App\Filament\Dashboard\Resources\LeadNotes\Tables\LeadNotesTable;
use App\Models\LeadNote;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeadNoteResource extends Resource
{
    protected static ?string $model = LeadNote::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static bool $isScopedToTenant = true;

    public static function getModelLabel(): string
    {
        return 'Nota de Lead';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Notas de Leads';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'CRM Principal';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function form(Schema $schema): Schema
    {
        return LeadNoteForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LeadNoteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeadNotesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLeadNotes::route('/'),
            'create' => CreateLeadNote::route('/create'),
            'view' => ViewLeadNote::route('/{record}'),
            'edit' => EditLeadNote::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
