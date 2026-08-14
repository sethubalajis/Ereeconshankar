<?php

namespace App\Filament\Branch\Resources\PromotionTypes;

use App\Filament\Branch\Resources\PromotionTypes\Pages\CreatePromotionType;
use App\Filament\Branch\Resources\PromotionTypes\Pages\EditPromotionType;
use App\Filament\Branch\Resources\PromotionTypes\Pages\ListPromotionTypes;
use App\Filament\Branch\Resources\PromotionTypes\Schemas\PromotionTypeForm;
use App\Filament\Branch\Resources\PromotionTypes\Tables\PromotionTypesTable;
use App\Models\PromotionType;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PromotionTypeResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '13. Promotions & Discounts';
    protected static ?int $navigationSort = 1;
    protected static ?string $model = PromotionType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    public static function form(Schema $schema): Schema
    {
        return PromotionTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PromotionTypesTable::configure($table);
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
            'index' => ListPromotionTypes::route('/'),
            'create' => CreatePromotionType::route('/create'),
            'edit' => EditPromotionType::route('/{record}/edit'),
        ];
    }
}
