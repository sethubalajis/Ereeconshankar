<?php

namespace App\Filament\Resources\Reimbursements;

use App\Filament\Resources\Reimbursements\Pages\CreateReimbursement;
use App\Filament\Resources\Reimbursements\Pages\EditReimbursement;
use App\Filament\Resources\Reimbursements\Pages\ListReimbursements;
use App\Filament\Resources\Reimbursements\Schemas\ReimbursementForm;
use App\Filament\Resources\Reimbursements\Tables\ReimbursementsTable;
use App\Models\Reimbursement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReimbursementResource extends Resource
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
