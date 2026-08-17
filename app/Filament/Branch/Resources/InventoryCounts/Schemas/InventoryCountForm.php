<?php

namespace App\Filament\Branch\Resources\InventoryCounts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InventoryCountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),

                TextInput::make('count_code')
                    ->required()
                    ->default(fn () => 'IC-' . now()->format('Ymd') . '-' . str_pad((string) ((\App\Models\InventoryCount::max('id') ?? 0) + 1), 4, '0', STR_PAD_LEFT))
                    ->unique(ignoreRecord: true),
                DatePicker::make('count_date')
                    ->required()
                    ->default(now()->timezone('Asia/Kolkata')),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'approved' => 'Approved',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('draft'),
            ]);
    }
}
