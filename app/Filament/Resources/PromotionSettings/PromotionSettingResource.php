<?php

namespace App\Filament\Resources\PromotionSettings;

use App\Filament\Resources\PromotionSettings\Pages\CreatePromotionSetting;
use App\Filament\Resources\PromotionSettings\Pages\EditPromotionSetting;
use App\Filament\Resources\PromotionSettings\Pages\ListPromotionSettings;
use App\Filament\Resources\PromotionSettings\Schemas\PromotionSettingForm;
use App\Filament\Resources\PromotionSettings\Tables\PromotionSettingsTable;
use App\Models\PromotionSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PromotionSettingResource extends Resource
{
    protected static ?string $model = PromotionSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static \UnitEnum|string|null $navigationGroup = 'Promotions & Discounts';

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
