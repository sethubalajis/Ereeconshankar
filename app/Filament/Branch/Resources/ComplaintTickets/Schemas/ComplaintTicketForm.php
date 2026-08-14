<?php

namespace App\Filament\Branch\Resources\ComplaintTickets\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ComplaintTicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('customer_id')->relationship('customer', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('complaint_category_id')->relationship('complaintCategory', 'name')
                    ->required(),
                TextInput::make('ticket_number')
                    ->default(function (): string {
                        $year = now()->format('Y');
                        $prefix = "TKT-{$year}-";
                        $latest = \App\Models\ComplaintTicket::query()
                            ->where('ticket_number', 'like', $prefix . '%')
                            ->orderByDesc('id')
                            ->value('ticket_number');

                        $next = 1;

                        if ($latest) {
                            $next = ((int) substr($latest, strlen($prefix))) + 1;
                        }

                        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
                    }),
                Textarea::make('description')
                    ->columnSpanFull(),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'open' => 'Open',
                        'in_progress' => 'In Progress',
                        'resolved' => 'Resolved',
                        'closed' => 'Closed',
                    ])
                    ->required()
                    ->default('open'),
            ]);
    }
}
