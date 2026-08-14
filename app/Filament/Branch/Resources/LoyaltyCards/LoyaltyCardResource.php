<?php

namespace App\Filament\Branch\Resources\LoyaltyCards;

use App\Filament\Branch\Resources\LoyaltyCards\Pages\CreateLoyaltyCard;
use App\Filament\Branch\Resources\LoyaltyCards\Pages\EditLoyaltyCard;
use App\Filament\Branch\Resources\LoyaltyCards\Pages\ListLoyaltyCards;
use App\Filament\Branch\Resources\LoyaltyCards\Schemas\LoyaltyCardForm;
use App\Filament\Branch\Resources\LoyaltyCards\Tables\LoyaltyCardsTable;
use App\Models\LoyaltyCard;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LoyaltyCardResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '12. CRM & Loyalty';
    protected static ?int $navigationSort = 4;
    protected static ?string $model = LoyaltyCard::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return LoyaltyCardForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LoyaltyCardsTable::configure($table);
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
            'index' => ListLoyaltyCards::route('/'),
            'create' => CreateLoyaltyCard::route('/create'),
            'edit' => EditLoyaltyCard::route('/{record}/edit'),
        ];
    }
}
