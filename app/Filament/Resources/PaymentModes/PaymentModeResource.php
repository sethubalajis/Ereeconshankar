<?php

namespace App\Filament\Resources\PaymentModes;

use App\Filament\Resources\PaymentModes\Pages\CreatePaymentMode;
use App\Filament\Resources\PaymentModes\Pages\EditPaymentMode;
use App\Filament\Resources\PaymentModes\Pages\ListPaymentModes;
use App\Filament\Resources\PaymentModes\Schemas\PaymentModeForm;
use App\Filament\Resources\PaymentModes\Tables\PaymentModesTable;
use App\Models\PaymentMode;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PaymentModeResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '11. Retail POS';
    protected static ?int $navigationSort = 10;
    protected static ?string $model = PaymentMode::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    public static function form(Schema $schema): Schema
    {
        return PaymentModeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentModesTable::configure($table);
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
            'index' => ListPaymentModes::route('/'),
            'create' => CreatePaymentMode::route('/create'),
            'edit' => EditPaymentMode::route('/{record}/edit'),
        ];
    }
}
