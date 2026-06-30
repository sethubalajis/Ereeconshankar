<?php

namespace App\Filament\Resources\ServiceAssignments;

use App\Filament\Resources\ServiceAssignments\Pages\CreateServiceAssignment;
use App\Filament\Resources\ServiceAssignments\Pages\EditServiceAssignment;
use App\Filament\Resources\ServiceAssignments\Pages\ListServiceAssignments;
use App\Filament\Resources\ServiceAssignments\Schemas\ServiceAssignmentForm;
use App\Filament\Resources\ServiceAssignments\Tables\ServiceAssignmentsTable;
use App\Models\ServiceAssignment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServiceAssignmentResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '19. Service & Complaints';
    protected static ?int $navigationSort = 5;
    protected static ?string $model = ServiceAssignment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ServiceAssignmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServiceAssignmentsTable::configure($table);
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
            'index' => ListServiceAssignments::route('/'),
            'create' => CreateServiceAssignment::route('/create'),
            'edit' => EditServiceAssignment::route('/{record}/edit'),
        ];
    }
}
