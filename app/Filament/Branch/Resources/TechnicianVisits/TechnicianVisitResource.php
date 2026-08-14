<?php

namespace App\Filament\Branch\Resources\TechnicianVisits;

use App\Filament\Branch\Resources\TechnicianVisits\Pages\CreateTechnicianVisit;
use App\Filament\Branch\Resources\TechnicianVisits\Pages\EditTechnicianVisit;
use App\Filament\Branch\Resources\TechnicianVisits\Pages\ListTechnicianVisits;
use App\Filament\Branch\Resources\TechnicianVisits\Schemas\TechnicianVisitForm;
use App\Filament\Branch\Resources\TechnicianVisits\Tables\TechnicianVisitsTable;
use App\Models\TechnicianVisit;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TechnicianVisitResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '19. Service & Complaints';
    protected static ?int $navigationSort = 6;
    protected static ?string $model = TechnicianVisit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return TechnicianVisitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TechnicianVisitsTable::configure($table);
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
            'index' => ListTechnicianVisits::route('/'),
            'create' => CreateTechnicianVisit::route('/create'),
            'edit' => EditTechnicianVisit::route('/{record}/edit'),
        ];
    }
}
