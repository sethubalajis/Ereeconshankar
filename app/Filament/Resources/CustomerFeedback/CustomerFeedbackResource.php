<?php

namespace App\Filament\Resources\CustomerFeedback;

use App\Filament\Resources\CustomerFeedback\Pages\CreateCustomerFeedback;
use App\Filament\Resources\CustomerFeedback\Pages\EditCustomerFeedback;
use App\Filament\Resources\CustomerFeedback\Pages\ListCustomerFeedback;
use App\Filament\Resources\CustomerFeedback\Schemas\CustomerFeedbackForm;
use App\Filament\Resources\CustomerFeedback\Tables\CustomerFeedbackTable;
use App\Models\CustomerFeedback;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CustomerFeedbackResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '19. Service & Complaints';
    protected static ?int $navigationSort = 8;
    protected static ?string $model = CustomerFeedback::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return CustomerFeedbackForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomerFeedbackTable::configure($table);
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
            'index' => ListCustomerFeedback::route('/'),
            'create' => CreateCustomerFeedback::route('/create'),
            'edit' => EditCustomerFeedback::route('/{record}/edit'),
        ];
    }
}
