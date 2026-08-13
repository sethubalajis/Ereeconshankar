<?php

namespace App\Filament\Branch\Resources\Rfqs;

use App\Filament\Branch\Resources\Rfqs\Pages\CreateRfq;
use App\Filament\Branch\Resources\Rfqs\Pages\EditRfq;
use App\Filament\Branch\Resources\Rfqs\Pages\ListRfqs;
use App\Filament\Branch\Resources\Rfqs\Schemas\RfqForm;
use App\Filament\Branch\Resources\Rfqs\Tables\RfqsTable;
use App\Models\Rfq;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RfqResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '5. Procurement';
    protected static ?string $model = Rfq::class;
    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    public static function form(Schema $schema): Schema
    {
        return RfqForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RfqsTable::configure($table);
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
            'index' => ListRfqs::route('/'),
            'create' => CreateRfq::route('/create'),
            'edit' => EditRfq::route('/{record}/edit'),
        ];
    }
}
