<?php

namespace App\Filament\Resources\AssetDisposals;

use App\Filament\Resources\AssetDisposals\Pages\CreateAssetDisposal;
use App\Filament\Resources\AssetDisposals\Pages\EditAssetDisposal;
use App\Filament\Resources\AssetDisposals\Pages\ListAssetDisposals;
use App\Filament\Resources\AssetDisposals\Schemas\AssetDisposalForm;
use App\Filament\Resources\AssetDisposals\Tables\AssetDisposalsTable;
use App\Models\AssetDisposal;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AssetDisposalResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '18. Asset Management';
    protected static ?int $navigationSort = 7;
    protected static ?string $model = AssetDisposal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    public static function form(Schema $schema): Schema
    {
        return AssetDisposalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssetDisposalsTable::configure($table);
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
            'index' => ListAssetDisposals::route('/'),
            'create' => CreateAssetDisposal::route('/create'),
            'edit' => EditAssetDisposal::route('/{record}/edit'),
        ];
    }
}
