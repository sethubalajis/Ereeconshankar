<?php

namespace App\Filament\Branch\Resources\CashRegisters;

use App\Filament\Branch\Resources\CashRegisters\Pages\CreateCashRegister;
use App\Filament\Branch\Resources\CashRegisters\Pages\EditCashRegister;
use App\Filament\Branch\Resources\CashRegisters\Pages\ListCashRegisters;
use App\Filament\Branch\Resources\CashRegisters\Schemas\CashRegisterForm;
use App\Filament\Branch\Resources\CashRegisters\Tables\CashRegistersTable;
use App\Models\CashRegister;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CashRegisterResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '11. Retail POS';
    protected static ?int $navigationSort = 3;
    protected static ?string $model = CashRegister::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    public static function form(Schema $schema): Schema
    {
        return CashRegisterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CashRegistersTable::configure($table);
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
            'index' => ListCashRegisters::route('/'),
            'create' => CreateCashRegister::route('/create'),
            'edit' => EditCashRegister::route('/{record}/edit'),
        ];
    }
}
