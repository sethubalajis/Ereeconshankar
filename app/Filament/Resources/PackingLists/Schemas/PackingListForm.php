<?php

namespace App\Filament\Resources\PackingLists\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PackingListForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('picking_list_id')->relationship('pickingList', 'reference_type')
                    ->required(),
                Select::make('status')
                    ->options([
                        'packed' => 'Packed',
                        'active' => 'Active',
                        'pending' => 'Pending',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('packed'),
            ]);
    }
}
