<?php

namespace App\Filament\Branch\Resources\BranchExpenses;

use App\Filament\Branch\Resources\BranchExpenses\Pages\CreateBranchExpense;
use App\Filament\Branch\Resources\BranchExpenses\Pages\EditBranchExpense;
use App\Filament\Branch\Resources\BranchExpenses\Pages\ListBranchExpenses;
use App\Filament\Branch\Resources\BranchExpenses\Schemas\BranchExpenseForm;
use App\Filament\Branch\Resources\BranchExpenses\Tables\BranchExpensesTable;
use App\Models\BranchExpense;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BranchExpenseResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '9. Branch Management';
    protected static ?int $navigationSort = 5;
    protected static ?string $model = BranchExpense::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    public static function form(Schema $schema): Schema
    {
        return BranchExpenseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BranchExpensesTable::configure($table);
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
            'index' => ListBranchExpenses::route('/'),
            'create' => CreateBranchExpense::route('/create'),
            'edit' => EditBranchExpense::route('/{record}/edit'),
        ];
    }
}
