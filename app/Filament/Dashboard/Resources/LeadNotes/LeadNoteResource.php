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

    protected static bool $shouldRegisterNavigation = false;

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
        return 4;
    }

    public static function getRouteMiddleware(\Filament\Panel $panel): string|array
    {
        return [
            'crm.subscription',
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        $tenant = filament()->getTenant();
        
        if (!$tenant || !$user) {
            return false;
        }
        
        // Admins globales siempre ven la navegación
        if ($user->is_admin) {
            return true;
        }
        
        // Solo mostrar en navegación si tiene suscripción CRM
        return $user->isSubscribed('crm-plan', $tenant) || 
               $user->isTrialing('crm-plan', $tenant);
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
