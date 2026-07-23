<?php

namespace App\Filament\Resources\HsnCodes;

use App\Filament\Resources\HsnCodes\Pages\CreateHsnCode;
use App\Filament\Resources\HsnCodes\Pages\EditHsnCode;
use App\Filament\Resources\HsnCodes\Pages\ListHsnCodes;
use App\Filament\Resources\HsnCodes\Schemas\HsnCodeForm;
use App\Filament\Resources\HsnCodes\Tables\HsnCodesTable;
use App\Models\HsnCode;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HsnCodeResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '3. Product Master';
    protected static ?string $model = HsnCode::class;
    protected static ?int $navigationSort = 11;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    public static function form(Schema $schema): Schema
    {
        return HsnCodeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HsnCodesTable::configure($table);
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
            'index' => ListHsnCodes::route('/'),
            'create' => CreateHsnCode::route('/create'),
            'edit' => EditHsnCode::route('/{record}/edit'),
        ];
    }
}
