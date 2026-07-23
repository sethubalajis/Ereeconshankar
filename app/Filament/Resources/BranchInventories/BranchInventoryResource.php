<?php

namespace App\Filament\Resources\BranchInventories;

use App\Filament\Resources\BranchInventories\Pages\CreateBranchInventory;
use App\Filament\Resources\BranchInventories\Pages\EditBranchInventory;
use App\Filament\Resources\BranchInventories\Pages\ListBranchInventories;
use App\Filament\Resources\BranchInventories\Schemas\BranchInventoryForm;
use App\Filament\Resources\BranchInventories\Tables\BranchInventoriesTable;
use App\Models\BranchInventory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BranchInventoryResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '9. Branch Management';
    protected static ?int $navigationSort = 3;
    protected static ?string $model = BranchInventory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    public static function form(Schema $schema): Schema
    {
        return BranchInventoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BranchInventoriesTable::configure($table);
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
            'index' => ListBranchInventories::route('/'),
            'create' => CreateBranchInventory::route('/create'),
            'edit' => EditBranchInventory::route('/{record}/edit'),
        ];
    }
}
