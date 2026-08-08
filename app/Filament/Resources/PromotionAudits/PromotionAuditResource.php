<?php

namespace App\Filament\Resources\PromotionAudits;

use App\Filament\Resources\PromotionAudits\Pages\CreatePromotionAudit;
use App\Filament\Resources\PromotionAudits\Pages\EditPromotionAudit;
use App\Filament\Resources\PromotionAudits\Pages\ListPromotionAudits;
use App\Filament\Resources\PromotionAudits\Schemas\PromotionAuditForm;
use App\Filament\Resources\PromotionAudits\Tables\PromotionAuditsTable;
use App\Models\PromotionAudit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PromotionAuditResource extends Resource
{
    protected static ?string $model = PromotionAudit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static \UnitEnum|string|null $navigationGroup = 'Promotions & Discounts';

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
