<?php

namespace App\Filament\Branch\Resources\AssetTransfers;

use App\Filament\Branch\Resources\AssetTransfers\Pages\CreateAssetTransfer;
use App\Filament\Branch\Resources\AssetTransfers\Pages\EditAssetTransfer;
use App\Filament\Branch\Resources\AssetTransfers\Pages\ListAssetTransfers;
use App\Filament\Branch\Resources\AssetTransfers\Schemas\AssetTransferForm;
use App\Filament\Branch\Resources\AssetTransfers\Tables\AssetTransfersTable;
use App\Models\AssetTransfer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AssetTransferResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '18. Asset Management';
    protected static ?int $navigationSort = 4;
    protected static ?string $model = AssetTransfer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return AssetTransferForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssetTransfersTable::configure($table);
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
            'index' => ListAssetTransfers::route('/'),
            'create' => CreateAssetTransfer::route('/create'),
            'edit' => EditAssetTransfer::route('/{record}/edit'),
        ];
    }
}
