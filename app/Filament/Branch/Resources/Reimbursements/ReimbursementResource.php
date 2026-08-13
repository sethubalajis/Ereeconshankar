<?php

namespace App\Filament\Branch\Resources\Reimbursements;

use App\Filament\Branch\Resources\Reimbursements\Pages\CreateReimbursement;
use App\Filament\Branch\Resources\Reimbursements\Pages\EditReimbursement;
use App\Filament\Branch\Resources\Reimbursements\Pages\ListReimbursements;
use App\Filament\Branch\Resources\Reimbursements\Schemas\ReimbursementForm;
use App\Filament\Branch\Resources\Reimbursements\Tables\ReimbursementsTable;
use App\Models\Reimbursement;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReimbursementResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '17. Payroll';
    protected static ?int $navigationSort = 7;
    protected static ?string $model = Reimbursement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ReimbursementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReimbursementsTable::configure($table);
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
            'index' => ListReimbursements::route('/'),
            'create' => CreateReimbursement::route('/create'),
            'edit' => EditReimbursement::route('/{record}/edit'),
        ];
    }
}
