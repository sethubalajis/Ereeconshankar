<?php

namespace App\Filament\Resources\EWayBills;

use App\Filament\Resources\EWayBills\Pages\CreateEWayBill;
use App\Filament\Resources\EWayBills\Pages\EditEWayBill;
use App\Filament\Resources\EWayBills\Pages\ListEWayBills;
use App\Filament\Resources\EWayBills\Schemas\EWayBillForm;
use App\Filament\Resources\EWayBills\Tables\EWayBillsTable;
use App\Models\EWayBill;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EWayBillResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '15. GST & Taxation';
    protected static ?int $navigationSort = 6;
    protected static ?string $model = EWayBill::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return EWayBillForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EWayBillsTable::configure($table);
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
            'index' => ListEWayBills::route('/'),
            'create' => CreateEWayBill::route('/create'),
            'edit' => EditEWayBill::route('/{record}/edit'),
        ];
    }
}
