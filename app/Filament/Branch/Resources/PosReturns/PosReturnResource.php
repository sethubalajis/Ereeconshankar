<?php

namespace App\Filament\Branch\Resources\PosReturns;

use App\Filament\Branch\Resources\PosReturns\Pages\CreatePosReturn;
use App\Filament\Branch\Resources\PosReturns\Pages\EditPosReturn;
use App\Filament\Branch\Resources\PosReturns\Pages\ListPosReturns;
use App\Filament\Branch\Resources\PosReturns\Schemas\PosReturnForm;
use App\Filament\Branch\Resources\PosReturns\Tables\PosReturnsTable;
use App\Models\PosReturn;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PosReturnResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '11. Retail POS';
    protected static ?int $navigationSort = 8;
    protected static ?string $model = PosReturn::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptRefund;

    public static function form(Schema $schema): Schema
    {
        return PosReturnForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PosReturnsTable::configure($table);
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
            'index' => ListPosReturns::route('/'),
            'create' => CreatePosReturn::route('/create'),
            'edit' => EditPosReturn::route('/{record}/edit'),
        ];
    }
}
