<?php

namespace App\Filament\Branch\Resources\LoyaltyPrograms;

use App\Filament\Branch\Resources\LoyaltyPrograms\Pages\CreateLoyaltyProgram;
use App\Filament\Branch\Resources\LoyaltyPrograms\Pages\EditLoyaltyProgram;
use App\Filament\Branch\Resources\LoyaltyPrograms\Pages\ListLoyaltyPrograms;
use App\Filament\Branch\Resources\LoyaltyPrograms\Schemas\LoyaltyProgramForm;
use App\Filament\Branch\Resources\LoyaltyPrograms\Tables\LoyaltyProgramsTable;
use App\Models\LoyaltyProgram;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LoyaltyProgramResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '12. CRM & Loyalty';
    protected static ?int $navigationSort = 3;
    protected static ?string $model = LoyaltyProgram::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return LoyaltyProgramForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LoyaltyProgramsTable::configure($table);
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
            'index' => ListLoyaltyPrograms::route('/'),
            'create' => CreateLoyaltyProgram::route('/create'),
            'edit' => EditLoyaltyProgram::route('/{record}/edit'),
        ];
    }
}
