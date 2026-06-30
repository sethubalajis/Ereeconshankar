<?php

namespace App\Filament\Resources\TechnicianVisits;

use App\Filament\Resources\TechnicianVisits\Pages\CreateTechnicianVisit;
use App\Filament\Resources\TechnicianVisits\Pages\EditTechnicianVisit;
use App\Filament\Resources\TechnicianVisits\Pages\ListTechnicianVisits;
use App\Filament\Resources\TechnicianVisits\Schemas\TechnicianVisitForm;
use App\Filament\Resources\TechnicianVisits\Tables\TechnicianVisitsTable;
use App\Models\TechnicianVisit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TechnicianVisitResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '19. Service & Complaints';
    protected static ?int $navigationSort = 6;
    protected static ?string $model = TechnicianVisit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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
