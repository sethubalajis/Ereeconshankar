<?php

namespace App\Filament\Resources\ComplaintTickets;

use App\Filament\Resources\ComplaintTickets\Pages\CreateComplaintTicket;
use App\Filament\Resources\ComplaintTickets\Pages\EditComplaintTicket;
use App\Filament\Resources\ComplaintTickets\Pages\ListComplaintTickets;
use App\Filament\Resources\ComplaintTickets\Schemas\ComplaintTicketForm;
use App\Filament\Resources\ComplaintTickets\Tables\ComplaintTicketsTable;
use App\Models\ComplaintTicket;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ComplaintTicketResource extends Resource
{
    protected static \UnitEnum|string|null $navigationGroup = '19. Service & Complaints';
    protected static ?int $navigationSort = 3;
    protected static ?string $model = ComplaintTicket::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ComplaintTicketForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComplaintTicketsTable::configure($table);
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
            'index' => ListComplaintTickets::route('/'),
            'create' => CreateComplaintTicket::route('/create'),
            'edit' => EditComplaintTicket::route('/{record}/edit'),
        ];
    }
}
