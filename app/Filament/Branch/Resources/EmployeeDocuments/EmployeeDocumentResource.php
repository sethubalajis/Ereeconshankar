<?php

namespace App\Filament\Branch\Resources\EmployeeDocuments;

use App\Filament\Branch\Resources\EmployeeDocuments\Pages\CreateEmployeeDocument;
use App\Filament\Branch\Resources\EmployeeDocuments\Pages\EditEmployeeDocument;
use App\Filament\Branch\Resources\EmployeeDocuments\Pages\ListEmployeeDocuments;
use App\Filament\Branch\Resources\EmployeeDocuments\Schemas\EmployeeDocumentForm;
use App\Filament\Branch\Resources\EmployeeDocuments\Tables\EmployeeDocumentsTable;
use App\Models\EmployeeDocument;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EmployeeDocumentResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '16. Human Resources';
    protected static ?int $navigationSort = 4;
    protected static ?string $model = EmployeeDocument::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    public static function form(Schema $schema): Schema
    {
        return EmployeeDocumentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmployeeDocumentsTable::configure($table);
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
            'index' => ListEmployeeDocuments::route('/'),
            'create' => CreateEmployeeDocument::route('/create'),
            'edit' => EditEmployeeDocument::route('/{record}/edit'),
        ];
    }
}
