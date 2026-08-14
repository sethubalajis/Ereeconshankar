<?php

namespace App\Filament\Branch\Resources\TransferRequests;

use App\Filament\Branch\Resources\TransferRequests\Pages\CreateTransferRequest;
use App\Filament\Branch\Resources\TransferRequests\Pages\EditTransferRequest;
use App\Filament\Branch\Resources\TransferRequests\Pages\ListTransferRequests;
use App\Filament\Branch\Resources\TransferRequests\Schemas\TransferRequestForm;
use App\Filament\Branch\Resources\TransferRequests\Tables\TransferRequestsTable;
use App\Models\TransferRequest;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TransferRequestResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '10. Stock Transfer';
    protected static ?int $navigationSort = 1;
    protected static ?string $model = TransferRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    public static function form(Schema $schema): Schema
    {
        return TransferRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransferRequestsTable::configure($table);
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
            'index' => ListTransferRequests::route('/'),
            'create' => CreateTransferRequest::route('/create'),
            'edit' => EditTransferRequest::route('/{record}/edit'),
        ];
    }
}
