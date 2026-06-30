<?php

namespace App\Filament\Resources\PosCounters;

use App\Filament\Resources\PosCounters\Pages\CreatePosCounter;
use App\Filament\Resources\PosCounters\Pages\EditPosCounter;
use App\Filament\Resources\PosCounters\Pages\ListPosCounters;
use App\Filament\Resources\PosCounters\Schemas\PosCounterForm;
use App\Filament\Resources\PosCounters\Tables\PosCountersTable;
use App\Models\PosCounter;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PosCounterResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '11. Retail POS';
    protected static ?int $navigationSort = 2;
    protected static ?string $model = PosCounter::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PosCounterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PosCountersTable::configure($table);
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
            'index' => ListPosCounters::route('/'),
            'create' => CreatePosCounter::route('/create'),
            'edit' => EditPosCounter::route('/{record}/edit'),
        ];
    }
}
