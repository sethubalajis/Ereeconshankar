<?php

namespace App\Filament\Branch\Resources\MembershipPlans;

use App\Filament\Branch\Resources\MembershipPlans\Pages\CreateMembershipPlan;
use App\Filament\Branch\Resources\MembershipPlans\Pages\EditMembershipPlan;
use App\Filament\Branch\Resources\MembershipPlans\Pages\ListMembershipPlans;
use App\Filament\Branch\Resources\MembershipPlans\Schemas\MembershipPlanForm;
use App\Filament\Branch\Resources\MembershipPlans\Tables\MembershipPlansTable;
use App\Models\MembershipPlan;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MembershipPlanResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '12. CRM & Loyalty';
    protected static ?int $navigationSort = 7;
    protected static ?string $model = MembershipPlan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return MembershipPlanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MembershipPlansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\MembershipPlans\RelationManagers\CustomerMembershipsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMembershipPlans::route('/'),
            'create' => CreateMembershipPlan::route('/create'),
            'edit' => EditMembershipPlan::route('/{record}/edit'),
        ];
    }
}
