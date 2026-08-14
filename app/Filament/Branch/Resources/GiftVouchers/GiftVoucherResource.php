<?php

namespace App\Filament\Branch\Resources\GiftVouchers;

use App\Filament\Branch\Resources\GiftVouchers\Pages\CreateGiftVoucher;
use App\Filament\Branch\Resources\GiftVouchers\Pages\EditGiftVoucher;
use App\Filament\Branch\Resources\GiftVouchers\Pages\ListGiftVouchers;
use App\Filament\Branch\Resources\GiftVouchers\Schemas\GiftVoucherForm;
use App\Filament\Branch\Resources\GiftVouchers\Tables\GiftVouchersTable;
use App\Models\GiftVoucher;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GiftVoucherResource extends BaseBranchResource
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
