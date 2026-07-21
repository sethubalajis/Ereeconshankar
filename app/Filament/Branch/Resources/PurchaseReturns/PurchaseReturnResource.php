<?php

namespace App\Filament\Branch\Resources\PurchaseReturns;

use App\Filament\Branch\Resources\PurchaseReturns\Pages\CreatePurchaseReturn;
use App\Filament\Branch\Resources\PurchaseReturns\Pages\EditPurchaseReturn;
use App\Filament\Branch\Resources\PurchaseReturns\Pages\ListPurchaseReturns;
use App\Filament\Branch\Resources\PurchaseReturns\Schemas\PurchaseReturnForm;
use App\Filament\Branch\Resources\PurchaseReturns\Tables\PurchaseReturnsTable;
use App\Models\PurchaseReturn;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PurchaseReturnResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '5. Procurement';
    protected static ?string $model = PurchaseReturn::class;
    protected static ?int $navigationSort = 7;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PurchaseReturnForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PurchaseReturnsTable::configure($table);
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
            'index' => ListPurchaseReturns::route('/'),
            'create' => CreatePurchaseReturn::route('/create'),
            'edit' => EditPurchaseReturn::route('/{record}/edit'),
        ];
    }
}
