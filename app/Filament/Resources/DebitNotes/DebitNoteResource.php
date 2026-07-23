<?php

namespace App\Filament\Resources\DebitNotes;

use App\Filament\Resources\DebitNotes\Pages\CreateDebitNote;
use App\Filament\Resources\DebitNotes\Pages\EditDebitNote;
use App\Filament\Resources\DebitNotes\Pages\ListDebitNotes;
use App\Filament\Resources\DebitNotes\Schemas\DebitNoteForm;
use App\Filament\Resources\DebitNotes\Tables\DebitNotesTable;
use App\Models\DebitNote;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DebitNoteResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '14. Finance & Accounts';
    protected static ?string $model = DebitNote::class;
    protected static ?int $navigationSort = 12;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    public static function form(Schema $schema): Schema
    {
        return DebitNoteForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DebitNotesTable::configure($table);
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
            'index' => ListDebitNotes::route('/'),
            'create' => CreateDebitNote::route('/create'),
            'edit' => EditDebitNote::route('/{record}/edit'),
        ];
    }
}
