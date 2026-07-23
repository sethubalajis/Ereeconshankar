<?php

namespace App\Filament\Branch\Resources\TransferItems;

use App\Filament\Branch\Resources\TransferItems\Pages\CreateTransferItem;
use App\Filament\Branch\Resources\TransferItems\Pages\EditTransferItem;
use App\Filament\Branch\Resources\TransferItems\Pages\ListTransferItems;
use App\Filament\Branch\Resources\TransferItems\Schemas\TransferItemForm;
use App\Filament\Branch\Resources\TransferItems\Tables\TransferItemsTable;
use App\Models\TransferItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TransferItemResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '10. Stock Transfer';
    protected static ?int $navigationSort = 4;
    protected static ?string $model = TransferItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    public static function form(Schema $schema): Schema
    {
        return TransferItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransferItemsTable::configure($table);
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
            'index' => ListTransferItems::route('/'),
            'create' => CreateTransferItem::route('/create'),
            'edit' => EditTransferItem::route('/{record}/edit'),
        ];
    }
}
