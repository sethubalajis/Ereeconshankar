<?php

namespace App\Filament\Branch\Resources\TransferApprovals;

use App\Filament\Branch\Resources\TransferApprovals\Pages\CreateTransferApproval;
use App\Filament\Branch\Resources\TransferApprovals\Pages\EditTransferApproval;
use App\Filament\Branch\Resources\TransferApprovals\Pages\ListTransferApprovals;
use App\Filament\Branch\Resources\TransferApprovals\Schemas\TransferApprovalForm;
use App\Filament\Branch\Resources\TransferApprovals\Tables\TransferApprovalsTable;
use App\Models\TransferApproval;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TransferApprovalResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '10. Stock Transfer';
    protected static ?int $navigationSort = 2;
    protected static ?string $model = TransferApproval::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    public static function form(Schema $schema): Schema
    {
        return TransferApprovalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransferApprovalsTable::configure($table);
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
            'index' => ListTransferApprovals::route('/'),
            'create' => CreateTransferApproval::route('/create'),
            'edit' => EditTransferApproval::route('/{record}/edit'),
        ];
    }
}
