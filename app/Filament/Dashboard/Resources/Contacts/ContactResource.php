<?php

namespace App\Filament\Dashboard\Resources\Contacts;

use App\Filament\Dashboard\Resources\Contacts\Pages\CreateContact;
use App\Filament\Dashboard\Resources\Contacts\Pages\EditContact;
use App\Filament\Dashboard\Resources\Contacts\Pages\ListContacts;
use App\Filament\Dashboard\Resources\Contacts\Pages\ViewContact;
use App\Filament\Dashboard\Resources\Contacts\Schemas\ContactForm;
use App\Filament\Dashboard\Resources\Contacts\Schemas\ContactInfolist;
use App\Filament\Dashboard\Resources\Contacts\Tables\ContactsTable;
use App\Models\Contact;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static bool $isScopedToTenant = true;

    public static function getModelLabel(): string
    {
        return 'Contacto';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Contactos';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'CRM Principal';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function form(Schema $schema): Schema
    {
        return ContactForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ContactInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContactsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\LeadsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContacts::route('/'),
            'create' => CreateContact::route('/create'),
            'view' => ViewContact::route('/{record}'),
            'edit' => EditContact::route('/{record}/edit'),
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
