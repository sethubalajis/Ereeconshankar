<?php

namespace App\Filament\Resources\PackingLists;

use App\Filament\Resources\PackingLists\Pages\CreatePackingList;
use App\Filament\Resources\PackingLists\Pages\EditPackingList;
use App\Filament\Resources\PackingLists\Pages\ListPackingLists;
use App\Filament\Resources\PackingLists\Schemas\PackingListForm;
use App\Filament\Resources\PackingLists\Tables\PackingListsTable;
use App\Models\PackingList;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PackingListResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '8. Warehouse Management';
    protected static ?string $model = PackingList::class;
    protected static ?int $navigationSort = 8;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    public static function form(Schema $schema): Schema
    {
        return PackingListForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PackingListsTable::configure($table);
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
            'index' => ListPackingLists::route('/'),
            'create' => CreatePackingList::route('/create'),
            'edit' => EditPackingList::route('/{record}/edit'),
        ];
    }
}
