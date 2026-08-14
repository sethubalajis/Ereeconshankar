<?php

namespace App\Filament\Branch\Resources\WarrantyClaims;

use App\Filament\Branch\Resources\WarrantyClaims\Pages\CreateWarrantyClaim;
use App\Filament\Branch\Resources\WarrantyClaims\Pages\EditWarrantyClaim;
use App\Filament\Branch\Resources\WarrantyClaims\Pages\ListWarrantyClaims;
use App\Filament\Branch\Resources\WarrantyClaims\Schemas\WarrantyClaimForm;
use App\Filament\Branch\Resources\WarrantyClaims\Tables\WarrantyClaimsTable;
use App\Models\WarrantyClaim;
use BackedEnum;
use App\Filament\Branch\Resources\BaseBranchResource;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WarrantyClaimResource extends BaseBranchResource
{
    protected static \UnitEnum|string|null $navigationGroup = '19. Service & Complaints';
    protected static ?int $navigationSort = 4;
    protected static ?string $model = WarrantyClaim::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return WarrantyClaimForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WarrantyClaimsTable::configure($table);
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
            'index' => ListWarrantyClaims::route('/'),
            'create' => CreateWarrantyClaim::route('/create'),
            'edit' => EditWarrantyClaim::route('/{record}/edit'),
        ];
    }
}
