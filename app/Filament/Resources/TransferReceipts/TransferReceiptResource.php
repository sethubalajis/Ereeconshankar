<?php

namespace App\Filament\Resources\TransferReceipts;

use App\Filament\Resources\TransferReceipts\Pages\CreateTransferReceipt;
use App\Filament\Resources\TransferReceipts\Pages\EditTransferReceipt;
use App\Filament\Resources\TransferReceipts\Pages\ListTransferReceipts;
use App\Filament\Resources\TransferReceipts\Schemas\TransferReceiptForm;
use App\Filament\Resources\TransferReceipts\Tables\TransferReceiptsTable;
use App\Models\TransferReceipt;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TransferReceiptResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '10. Stock Transfer';
    protected static ?int $navigationSort = 5;
    protected static ?string $model = TransferReceipt::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    public static function form(Schema $schema): Schema
    {
        return TransferReceiptForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransferReceiptsTable::configure($table);
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
            'index' => ListTransferReceipts::route('/'),
            'create' => CreateTransferReceipt::route('/create'),
            'edit' => EditTransferReceipt::route('/{record}/edit'),
        ];
    }
}
