<?php

namespace App\Filament\Resources\TaxInvoices;

use App\Filament\Resources\TaxInvoices\Pages\CreateTaxInvoice;
use App\Filament\Resources\TaxInvoices\Pages\EditTaxInvoice;
use App\Filament\Resources\TaxInvoices\Pages\ListTaxInvoices;
use App\Filament\Resources\TaxInvoices\Schemas\TaxInvoiceForm;
use App\Filament\Resources\TaxInvoices\Tables\TaxInvoicesTable;
use App\Models\TaxInvoice;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TaxInvoiceResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '15. GST & Taxation';
    protected static ?int $navigationSort = 4;
    protected static ?string $model = TaxInvoice::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TaxInvoiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TaxInvoicesTable::configure($table);
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
            'index' => ListTaxInvoices::route('/'),
            'create' => CreateTaxInvoice::route('/create'),
            'edit' => EditTaxInvoice::route('/{record}/edit'),
        ];
    }
}
