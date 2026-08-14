<?php

namespace App\Filament\Branch\Resources\AssetAllocations;

use App\Filament\Branch\Resources\AssetAllocations\Pages\CreateAssetAllocation;
use App\Filament\Branch\Resources\AssetAllocations\Pages\EditAssetAllocation;
use App\Filament\Branch\Resources\AssetAllocations\Pages\ListAssetAllocations;
use App\Filament\Branch\Resources\AssetAllocations\Schemas\AssetAllocationForm;
use App\Filament\Branch\Resources\AssetAllocations\Tables\AssetAllocationsTable;
use App\Models\AssetAllocation;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AssetAllocationResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '18. Asset Management';
    protected static ?int $navigationSort = 3;
    protected static ?string $model = AssetAllocation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    public static function form(Schema $schema): Schema
    {
        return AssetAllocationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssetAllocationsTable::configure($table);
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
            'index' => ListAssetAllocations::route('/'),
            'create' => CreateAssetAllocation::route('/create'),
            'edit' => EditAssetAllocation::route('/{record}/edit'),
        ];
    }
}
