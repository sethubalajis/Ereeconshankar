<?php

namespace App\Filament\Branch\Resources\ServiceAssignments;

use App\Filament\Branch\Resources\ServiceAssignments\Pages\CreateServiceAssignment;
use App\Filament\Branch\Resources\ServiceAssignments\Pages\EditServiceAssignment;
use App\Filament\Branch\Resources\ServiceAssignments\Pages\ListServiceAssignments;
use App\Filament\Branch\Resources\ServiceAssignments\Schemas\ServiceAssignmentForm;
use App\Filament\Branch\Resources\ServiceAssignments\Tables\ServiceAssignmentsTable;
use App\Models\ServiceAssignment;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServiceAssignmentResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '19. Service & Complaints';
    protected static ?int $navigationSort = 5;
    protected static ?string $model = ServiceAssignment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

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
