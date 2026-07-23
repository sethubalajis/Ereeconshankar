<?php

namespace App\Filament\Resources\LoyaltyPoints;

use App\Filament\Resources\LoyaltyPoints\Pages\CreateLoyaltyPoint;
use App\Filament\Resources\LoyaltyPoints\Pages\EditLoyaltyPoint;
use App\Filament\Resources\LoyaltyPoints\Pages\ListLoyaltyPoints;
use App\Filament\Resources\LoyaltyPoints\Schemas\LoyaltyPointForm;
use App\Filament\Resources\LoyaltyPoints\Tables\LoyaltyPointsTable;
use App\Models\LoyaltyPoint;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LoyaltyPointResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '12. CRM & Loyalty';
    protected static ?int $navigationSort = 5;
    protected static ?string $model = LoyaltyPoint::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return LoyaltyPointForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LoyaltyPointsTable::configure($table);
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
            'index' => ListLoyaltyPoints::route('/'),
            'create' => CreateLoyaltyPoint::route('/create'),
            'edit' => EditLoyaltyPoint::route('/{record}/edit'),
        ];
    }
}
