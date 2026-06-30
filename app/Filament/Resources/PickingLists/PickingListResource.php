<?php

namespace App\Filament\Resources\PickingLists;

use App\Filament\Resources\PickingLists\Pages\CreatePickingList;
use App\Filament\Resources\PickingLists\Pages\EditPickingList;
use App\Filament\Resources\PickingLists\Pages\ListPickingLists;
use App\Filament\Resources\PickingLists\Schemas\PickingListForm;
use App\Filament\Resources\PickingLists\Tables\PickingListsTable;
use App\Models\PickingList;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PickingListResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '8. Warehouse Management';
    protected static ?string $model = PickingList::class;
    protected static ?int $navigationSort = 7;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PickingListForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PickingListsTable::configure($table);
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
            'index' => ListPickingLists::route('/'),
            'create' => CreatePickingList::route('/create'),
            'edit' => EditPickingList::route('/{record}/edit'),
        ];
    }
}
