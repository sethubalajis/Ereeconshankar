<?php

namespace App\Filament\Branch\Resources\ServiceRequests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ServiceRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('customer_id')->relationship('customer', 'name')
                    ->required(),
                TextInput::make('request_number')
                    ->default(function (): string {
                        $year = now()->format('Y');
                        $prefix = "SR-{$year}-";
                        $latest = \App\Models\ServiceRequest::query()
                            ->where('request_number', 'like', $prefix . '%')
                            ->orderByDesc('id')
                            ->value('request_number');

                        $next = 1;

                        if ($latest) {
                            $next = ((int) substr($latest, strlen($prefix))) + 1;
                        }

                        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
                    }),
                DatePicker::make('request_date')
                    ->default(now()->timezone('Asia/Kolkata'))
                    ->required(),
                Textarea::make('issue_description')
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
