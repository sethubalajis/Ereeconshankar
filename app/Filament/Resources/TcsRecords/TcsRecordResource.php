<?php

namespace App\Filament\Resources\TcsRecords;

use App\Filament\Resources\TcsRecords\Pages\CreateTcsRecord;
use App\Filament\Resources\TcsRecords\Pages\EditTcsRecord;
use App\Filament\Resources\TcsRecords\Pages\ListTcsRecords;
use App\Filament\Resources\TcsRecords\Schemas\TcsRecordForm;
use App\Filament\Resources\TcsRecords\Tables\TcsRecordsTable;
use App\Models\TcsRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TcsRecordResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '15. GST & Taxation';
    protected static ?int $navigationSort = 8;
    protected static ?string $model = TcsRecord::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    public static function form(Schema $schema): Schema
    {
        return TcsRecordForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TcsRecordsTable::configure($table);
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
            'index' => ListTcsRecords::route('/'),
            'create' => CreateTcsRecord::route('/create'),
            'edit' => EditTcsRecord::route('/{record}/edit'),
        ];
    }
}
