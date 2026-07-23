<?php

namespace App\Filament\Branch\Resources\PurchaseRequisitions;

use App\Filament\Branch\Resources\PurchaseRequisitions\Pages\CreatePurchaseRequisition;
use App\Filament\Branch\Resources\PurchaseRequisitions\Pages\EditPurchaseRequisition;
use App\Filament\Branch\Resources\PurchaseRequisitions\Pages\ListPurchaseRequisitions;
use App\Filament\Branch\Resources\PurchaseRequisitions\Schemas\PurchaseRequisitionForm;
use App\Filament\Branch\Resources\PurchaseRequisitions\Tables\PurchaseRequisitionsTable;
use App\Models\PurchaseRequisition;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PurchaseRequisitionResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '5. Procurement';
    protected static ?string $model = PurchaseRequisition::class;
    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    public static function form(Schema $schema): Schema
    {
        return PurchaseRequisitionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PurchaseRequisitionsTable::configure($table);
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
            'index' => ListPurchaseRequisitions::route('/'),
            'create' => CreatePurchaseRequisition::route('/create'),
            'edit' => EditPurchaseRequisition::route('/{record}/edit'),
        ];
    }
}
