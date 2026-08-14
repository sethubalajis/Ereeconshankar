<?php

namespace App\Filament\Branch\Resources\ServiceRequests;

use App\Filament\Branch\Resources\ServiceRequests\Pages\CreateServiceRequest;
use App\Filament\Branch\Resources\ServiceRequests\Pages\EditServiceRequest;
use App\Filament\Branch\Resources\ServiceRequests\Pages\ListServiceRequests;
use App\Filament\Branch\Resources\ServiceRequests\Schemas\ServiceRequestForm;
use App\Filament\Branch\Resources\ServiceRequests\Tables\ServiceRequestsTable;
use App\Models\ServiceRequest;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServiceRequestResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '19. Service & Complaints';
    protected static ?int $navigationSort = 2;
    protected static ?string $model = ServiceRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return ServiceRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServiceRequestsTable::configure($table);
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
            'index' => ListServiceRequests::route('/'),
            'create' => CreateServiceRequest::route('/create'),
            'edit' => EditServiceRequest::route('/{record}/edit'),
        ];
    }
}
