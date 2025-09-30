<?php

namespace App\Filament\Dashboard\Resources\Leads;

use App\Filament\Dashboard\Resources\Leads\Pages\CreateLead;
use App\Filament\Dashboard\Resources\Leads\Pages\EditLead;
use App\Filament\Dashboard\Resources\Leads\Pages\ListLeads;
use App\Filament\Dashboard\Resources\Leads\Pages\ViewLead;
use App\Filament\Dashboard\Resources\Leads\Pages\EventsCalendar;
use App\Filament\Dashboard\Resources\Leads\RelationManagers\NotesRelationManager;
use App\Filament\Dashboard\Resources\Leads\RelationManagers\EventsRelationManager;
use App\Filament\Dashboard\Resources\Leads\Schemas\LeadForm;
use App\Filament\Dashboard\Resources\Leads\Schemas\LeadInfolist;
use App\Filament\Dashboard\Resources\Leads\Tables\LeadsTable;
use App\Models\Lead;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;
    
    protected static string|\UnitEnum|null $navigationGroup = 'CRM Principal';
    
    protected static ?int $navigationSort = 1;
    
    protected static bool $isScopedToTenant = true;

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function canView(Model $record): bool
    {
        $user = auth()->user();
        
        // Admin puede ver todo
        if ($user->isAdmin()) {
            return true;
        }
        
        // Usuario solo puede ver sus leads asignados
        return $record->asesor_id === $user->id;
    }

    public static function canEdit(Model $record): bool
    {
        return static::canView($record);
    }

    public static function canDelete(Model $record): bool
    {
        return static::canView($record);
    }

    public static function canCreate(): bool
    {
        return auth()->check();
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

    public static function getModelLabel(): string
    {
        return 'Lead';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Leads';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'CRM Principal';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function form(Schema $schema): Schema
    {
        return LeadForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LeadInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeadsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            NotesRelationManager::class,
            EventsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLeads::route('/'),
            'create' => CreateLead::route('/create'),
            'view' => ViewLead::route('/{record}'),
            'edit' => EditLead::route('/{record}/edit'),
            'calendar' => EventsCalendar::route('/calendar'),
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
