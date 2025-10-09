<?php

namespace App\Filament\Dashboard\Resources\LeadEvents;

use App\Filament\Dashboard\Resources\LeadEvents\Pages\CreateLeadEvent;
use App\Filament\Dashboard\Resources\LeadEvents\Pages\EditLeadEvent;
use App\Filament\Dashboard\Resources\LeadEvents\Pages\ListLeadEvents;
use App\Filament\Dashboard\Resources\LeadEvents\Pages\ViewLeadEvent;
use App\Filament\Dashboard\Resources\LeadEvents\Pages\CalendarView;
use App\Filament\Dashboard\Resources\LeadEvents\Schemas\LeadEventForm;
use App\Filament\Dashboard\Resources\LeadEvents\Schemas\LeadEventInfolist;
use App\Filament\Dashboard\Resources\LeadEvents\Tables\LeadEventsTable;
use App\Models\LeadEvent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeadEventResource extends Resource
{
    protected static ?string $model = LeadEvent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static bool $isScopedToTenant = true;

    public static function getModelLabel(): string
    {
        return 'Evento de Lead';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Eventos de Leads';
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
        
        // Solo mostrar en navegación si tiene CUALQUIER suscripción activa
        return $user->isSubscribed(null, $tenant) || 
               $user->isTrialing(null, $tenant);
    }

    public static function form(Schema $schema): Schema
    {
        return LeadEventForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LeadEventInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeadEventsTable::configure($table);
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
            'index' => ListLeadEvents::route('/'),
            'create' => CreateLeadEvent::route('/create'),
            'view' => ViewLeadEvent::route('/{record}'),
            'edit' => EditLeadEvent::route('/{record}/edit'),
            'calendar' => CalendarView::route('/calendar'),
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
