<?php

namespace App\Filament\Dashboard\Resources\Courses;

use App\Filament\Dashboard\Resources\Courses\Pages\CreateCourse;
use App\Filament\Dashboard\Resources\Courses\Pages\EditCourse;
use App\Filament\Dashboard\Resources\Courses\Pages\ListCourses;
use App\Filament\Dashboard\Resources\Courses\Pages\ViewCourse;
use App\Filament\Dashboard\Resources\Courses\Schemas\CourseForm;
use App\Filament\Dashboard\Resources\Courses\Schemas\CourseInfolist;
use App\Filament\Dashboard\Resources\Courses\Tables\CoursesTable;
use App\Models\Course;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $modelLabel = 'Curso';
    
    protected static ?string $pluralModelLabel = 'Cursos';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static bool $isScopedToTenant = false;

    public static function getNavigationGroup(): ?string
    {
        return 'CRM Principal';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
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
        return CourseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CourseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CoursesTable::configure($table);
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
            'index' => ListCourses::route('/'),
            'create' => CreateCourse::route('/create'),
            'view' => ViewCourse::route('/{record}'),
            'edit' => EditCourse::route('/{record}/edit'),
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
