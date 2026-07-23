<?php

namespace App\Filament\Resources\GiftVouchers;

use App\Filament\Resources\GiftVouchers\Pages\CreateGiftVoucher;
use App\Filament\Resources\GiftVouchers\Pages\EditGiftVoucher;
use App\Filament\Resources\GiftVouchers\Pages\ListGiftVouchers;
use App\Filament\Resources\GiftVouchers\Schemas\GiftVoucherForm;
use App\Filament\Resources\GiftVouchers\Tables\GiftVouchersTable;
use App\Models\GiftVoucher;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GiftVoucherResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '11. Retail POS';
    protected static ?int $navigationSort = 9;
    protected static ?string $model = GiftVoucher::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    public static function form(Schema $schema): Schema
    {
        return GiftVoucherForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GiftVouchersTable::configure($table);
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
            'index' => ListGiftVouchers::route('/'),
            'create' => CreateGiftVoucher::route('/create'),
            'edit' => EditGiftVoucher::route('/{record}/edit'),
        ];
    }
}
