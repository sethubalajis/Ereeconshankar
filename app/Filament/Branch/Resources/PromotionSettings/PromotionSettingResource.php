<?php

namespace App\Filament\Branch\Resources\PromotionSettings;

use App\Filament\Branch\Resources\PromotionSettings\Pages\CreatePromotionSetting;
use App\Filament\Branch\Resources\PromotionSettings\Pages\EditPromotionSetting;
use App\Filament\Branch\Resources\PromotionSettings\Pages\ListPromotionSettings;
use App\Filament\Branch\Resources\PromotionSettings\Schemas\PromotionSettingForm;
use App\Filament\Branch\Resources\PromotionSettings\Tables\PromotionSettingsTable;
use App\Models\PromotionSetting;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PromotionSettingResource extends BaseBranchResource
{
    protected static ?string $model = PromotionSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static \UnitEnum|string|null $navigationGroup = '13. Promotions & Discounts';
    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return PromotionSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PromotionSettingsTable::configure($table);
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
            'index' => ListPromotionSettings::route('/'),
            'create' => CreatePromotionSetting::route('/create'),
            'edit' => EditPromotionSetting::route('/{record}/edit'),
        ];
    }
}
