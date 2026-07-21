<?php

namespace App\Filament\Branch\Resources\BranchSettings;

use App\Filament\Branch\Resources\BranchSettings\Pages\CreateBranchSetting;
use App\Filament\Branch\Resources\BranchSettings\Pages\EditBranchSetting;
use App\Filament\Branch\Resources\BranchSettings\Pages\ListBranchSettings;
use App\Filament\Branch\Resources\BranchSettings\Schemas\BranchSettingForm;
use App\Filament\Branch\Resources\BranchSettings\Tables\BranchSettingsTable;
use App\Models\BranchSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BranchSettingResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '9. Branch Management';
    protected static ?int $navigationSort = 2;
    protected static ?string $model = BranchSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return BranchSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BranchSettingsTable::configure($table);
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
            'index' => ListBranchSettings::route('/'),
            'create' => CreateBranchSetting::route('/create'),
            'edit' => EditBranchSetting::route('/{record}/edit'),
        ];
    }
}
