<?php

namespace App\Filament\Resources\VendorAgreements;

use App\Filament\Resources\VendorAgreements\Pages\CreateVendorAgreement;
use App\Filament\Resources\VendorAgreements\Pages\EditVendorAgreement;
use App\Filament\Resources\VendorAgreements\Pages\ListVendorAgreements;
use App\Filament\Resources\VendorAgreements\Schemas\VendorAgreementForm;
use App\Filament\Resources\VendorAgreements\Tables\VendorAgreementsTable;
use App\Models\VendorAgreement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VendorAgreementResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '6. Vendor Management';
    protected static ?string $model = VendorAgreement::class;
    protected static ?int $navigationSort = 4;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return VendorAgreementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VendorAgreementsTable::configure($table);
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
            'index' => ListVendorAgreements::route('/'),
            'create' => CreateVendorAgreement::route('/create'),
            'edit' => EditVendorAgreement::route('/{record}/edit'),
        ];
    }
}
