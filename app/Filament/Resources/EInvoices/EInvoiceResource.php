<?php

namespace App\Filament\Resources\EInvoices;

use App\Filament\Resources\EInvoices\Pages\CreateEInvoice;
use App\Filament\Resources\EInvoices\Pages\EditEInvoice;
use App\Filament\Resources\EInvoices\Pages\ListEInvoices;
use App\Filament\Resources\EInvoices\Schemas\EInvoiceForm;
use App\Filament\Resources\EInvoices\Tables\EInvoicesTable;
use App\Models\EInvoice;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EInvoiceResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '15. GST & Taxation';
    protected static ?int $navigationSort = 5;
    protected static ?string $model = EInvoice::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    public static function form(Schema $schema): Schema
    {
        return EInvoiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EInvoicesTable::configure($table);
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
            'index' => ListEInvoices::route('/'),
            'create' => CreateEInvoice::route('/create'),
            'edit' => EditEInvoice::route('/{record}/edit'),
        ];
    }
}
