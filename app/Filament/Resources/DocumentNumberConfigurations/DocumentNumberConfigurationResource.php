<?php

namespace App\Filament\Resources\DocumentNumberConfigurations;

use App\Filament\Resources\DocumentNumberConfigurations\Pages\CreateDocumentNumberConfiguration;
use App\Filament\Resources\DocumentNumberConfigurations\Pages\EditDocumentNumberConfiguration;
use App\Filament\Resources\DocumentNumberConfigurations\Pages\ListDocumentNumberConfigurations;
use App\Filament\Resources\DocumentNumberConfigurations\Schemas\DocumentNumberConfigurationForm;
use App\Filament\Resources\DocumentNumberConfigurations\Tables\DocumentNumberConfigurationsTable;
use App\Models\DocumentNumberConfiguration;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DocumentNumberConfigurationResource extends Resource
{
    protected static ?string $model = DocumentNumberConfiguration::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return DocumentNumberConfigurationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentNumberConfigurationsTable::configure($table);
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
            'index' => ListDocumentNumberConfigurations::route('/'),
            'create' => CreateDocumentNumberConfiguration::route('/create'),
            'edit' => EditDocumentNumberConfiguration::route('/{record}/edit'),
        ];
    }
}
