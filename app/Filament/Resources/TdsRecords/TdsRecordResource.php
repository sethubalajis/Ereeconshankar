<?php

namespace App\Filament\Resources\TdsRecords;

use App\Filament\Resources\TdsRecords\Pages\CreateTdsRecord;
use App\Filament\Resources\TdsRecords\Pages\EditTdsRecord;
use App\Filament\Resources\TdsRecords\Pages\ListTdsRecords;
use App\Filament\Resources\TdsRecords\Schemas\TdsRecordForm;
use App\Filament\Resources\TdsRecords\Tables\TdsRecordsTable;
use App\Models\TdsRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TdsRecordResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '17. Payroll';
    protected static ?int $navigationSort = 12;
    protected static ?string $model = TdsRecord::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    public static function form(Schema $schema): Schema
    {
        return TdsRecordForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TdsRecordsTable::configure($table);
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
            'index' => ListTdsRecords::route('/'),
            'create' => CreateTdsRecord::route('/create'),
            'edit' => EditTdsRecord::route('/{record}/edit'),
        ];
    }
}
