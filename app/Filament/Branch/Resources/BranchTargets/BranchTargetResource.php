<?php

namespace App\Filament\Branch\Resources\BranchTargets;

use App\Filament\Branch\Resources\BranchTargets\Pages\CreateBranchTarget;
use App\Filament\Branch\Resources\BranchTargets\Pages\EditBranchTarget;
use App\Filament\Branch\Resources\BranchTargets\Pages\ListBranchTargets;
use App\Filament\Branch\Resources\BranchTargets\Schemas\BranchTargetForm;
use App\Filament\Branch\Resources\BranchTargets\Tables\BranchTargetsTable;
use App\Models\BranchTarget;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BranchTargetResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '9. Branch Management';
    protected static ?int $navigationSort = 6;
    protected static ?string $model = BranchTarget::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    public static function form(Schema $schema): Schema
    {
        return BranchTargetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BranchTargetsTable::configure($table);
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
            'index' => ListBranchTargets::route('/'),
            'create' => CreateBranchTarget::route('/create'),
            'edit' => EditBranchTarget::route('/{record}/edit'),
        ];
    }
}
