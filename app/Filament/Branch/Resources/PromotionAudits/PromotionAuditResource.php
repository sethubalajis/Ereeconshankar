<?php

namespace App\Filament\Branch\Resources\PromotionAudits;

use App\Filament\Branch\Resources\PromotionAudits\Pages\CreatePromotionAudit;
use App\Filament\Branch\Resources\PromotionAudits\Pages\EditPromotionAudit;
use App\Filament\Branch\Resources\PromotionAudits\Pages\ListPromotionAudits;
use App\Filament\Branch\Resources\PromotionAudits\Schemas\PromotionAuditForm;
use App\Filament\Branch\Resources\PromotionAudits\Tables\PromotionAuditsTable;
use App\Models\PromotionAudit;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PromotionAuditResource extends BaseBranchResource
{
    protected static ?string $model = PromotionAudit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static \UnitEnum|string|null $navigationGroup = '13. Promotions & Discounts';
    protected static ?int $navigationSort = 9;

    public static function form(Schema $schema): Schema
    {
        return PromotionAuditForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PromotionAuditsTable::configure($table);
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
            'index' => ListPromotionAudits::route('/'),
            'create' => CreatePromotionAudit::route('/create'),
            'edit' => EditPromotionAudit::route('/{record}/edit'),
        ];
    }
}
