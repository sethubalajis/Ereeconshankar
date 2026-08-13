<?php

namespace App\Filament\Branch\Resources\DiscountRules;

use App\Filament\Branch\Resources\DiscountRules\Pages\CreateDiscountRule;
use App\Filament\Branch\Resources\DiscountRules\Pages\EditDiscountRule;
use App\Filament\Branch\Resources\DiscountRules\Pages\ListDiscountRules;
use App\Filament\Branch\Resources\DiscountRules\Schemas\DiscountRuleForm;
use App\Filament\Branch\Resources\DiscountRules\Tables\DiscountRulesTable;
use App\Models\DiscountRule;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DiscountRuleResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '13. Promotions & Discounts';
    protected static ?int $navigationSort = 2;
    protected static ?string $model = DiscountRule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    public static function form(Schema $schema): Schema
    {
        return DiscountRuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DiscountRulesTable::configure($table);
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
            'index' => ListDiscountRules::route('/'),
            'create' => CreateDiscountRule::route('/create'),
            'edit' => EditDiscountRule::route('/{record}/edit'),
        ];
    }
}
