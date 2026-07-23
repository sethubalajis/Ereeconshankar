<?php

namespace App\Filament\Resources\ContraEntries;

use App\Filament\Resources\ContraEntries\Pages\CreateContraEntry;
use App\Filament\Resources\ContraEntries\Pages\EditContraEntry;
use App\Filament\Resources\ContraEntries\Pages\ListContraEntries;
use App\Filament\Resources\ContraEntries\Schemas\ContraEntryForm;
use App\Filament\Resources\ContraEntries\Tables\ContraEntriesTable;
use App\Models\ContraEntry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ContraEntryResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '14. Finance & Accounts';
    protected static ?int $navigationSort = 10;
    protected static ?string $model = ContraEntry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    public static function form(Schema $schema): Schema
    {
        return ContraEntryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContraEntriesTable::configure($table);
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
            'index' => ListContraEntries::route('/'),
            'create' => CreateContraEntry::route('/create'),
            'edit' => EditContraEntry::route('/{record}/edit'),
        ];
    }
}
