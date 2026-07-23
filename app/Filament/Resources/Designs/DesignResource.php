<?php

namespace App\Filament\Resources\Designs;

use App\Filament\Resources\Designs\Pages\CreateDesign;
use App\Filament\Resources\Designs\Pages\EditDesign;
use App\Filament\Resources\Designs\Pages\ListDesigns;
use App\Filament\Resources\Designs\Schemas\DesignForm;
use App\Filament\Resources\Designs\Tables\DesignsTable;
use App\Models\Design;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DesignResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '4. Merchandise Management';
    protected static ?string $model = Design::class;
    protected static ?int $navigationSort = 5;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    public static function form(Schema $schema): Schema
    {
        return DesignForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DesignsTable::configure($table);
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
            'index' => ListDesigns::route('/'),
            'create' => CreateDesign::route('/create'),
            'edit' => EditDesign::route('/{record}/edit'),
        ];
    }
}
