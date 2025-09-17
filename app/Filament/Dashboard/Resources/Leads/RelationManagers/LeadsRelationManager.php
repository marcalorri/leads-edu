<?php

namespace App\Filament\Dashboard\Resources\Leads\RelationManagers;

use App\Filament\Dashboard\Resources\Leads\LeadResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class LeadsRelationManager extends RelationManager
{
    protected static string $relationship = 'leads';

    protected static ?string $relatedResource = LeadResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
