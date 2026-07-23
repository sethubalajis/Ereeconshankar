<?php

namespace App\Filament\Resources\TaxGroups;

use App\Filament\Resources\TaxGroups\Pages\CreateTaxGroup;
use App\Filament\Resources\TaxGroups\Pages\EditTaxGroup;
use App\Filament\Resources\TaxGroups\Pages\ListTaxGroups;
use App\Filament\Resources\TaxGroups\Schemas\TaxGroupForm;
use App\Filament\Resources\TaxGroups\Tables\TaxGroupsTable;
use App\Models\TaxGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TaxGroupResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '3. Product Master';
    protected static ?string $model = TaxGroup::class;
    protected static ?int $navigationSort = 12;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    public static function form(Schema $schema): Schema
    {
        return TaxGroupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TaxGroupsTable::configure($table);
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
            'index' => ListTaxGroups::route('/'),
            'create' => CreateTaxGroup::route('/create'),
            'edit' => EditTaxGroup::route('/{record}/edit'),
        ];
    }
}
