<?php

namespace App\Filament\Resources\HsnSacCodes;

use App\Filament\Resources\HsnSacCodes\Pages\CreateHsnSacCode;
use App\Filament\Resources\HsnSacCodes\Pages\EditHsnSacCode;
use App\Filament\Resources\HsnSacCodes\Pages\ListHsnSacCodes;
use App\Filament\Resources\HsnSacCodes\Schemas\HsnSacCodeForm;
use App\Filament\Resources\HsnSacCodes\Tables\HsnSacCodesTable;
use App\Models\HsnSacCode;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HsnSacCodeResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '15. GST & Taxation';
    protected static ?int $navigationSort = 9;
    protected static ?string $model = HsnSacCode::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return HsnSacCodeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HsnSacCodesTable::configure($table);
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
            'index' => ListHsnSacCodes::route('/'),
            'create' => CreateHsnSacCode::route('/create'),
            'edit' => EditHsnSacCode::route('/{record}/edit'),
        ];
    }
}
