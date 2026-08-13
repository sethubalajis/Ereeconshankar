<?php

namespace App\Filament\Branch\Resources\Quotations;

use App\Filament\Branch\Resources\Quotations\Pages\CreateQuotation;
use App\Filament\Branch\Resources\Quotations\Pages\EditQuotation;
use App\Filament\Branch\Resources\Quotations\Pages\ListQuotations;
use App\Filament\Branch\Resources\Quotations\Schemas\QuotationForm;
use App\Filament\Branch\Resources\Quotations\Tables\QuotationsTable;
use App\Models\Quotation;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class QuotationResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '5. Procurement';
    protected static ?string $model = Quotation::class;
    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    public static function form(Schema $schema): Schema
    {
        return QuotationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuotationsTable::configure($table);
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
            'index' => ListQuotations::route('/'),
            'create' => CreateQuotation::route('/create'),
            'edit' => EditQuotation::route('/{record}/edit'),
        ];
    }
}
