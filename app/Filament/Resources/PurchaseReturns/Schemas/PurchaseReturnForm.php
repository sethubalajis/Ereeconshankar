<?php

namespace App\Filament\Resources\PurchaseReturns\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use App\Models\Grn;

class PurchaseReturnForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('purchase_order_id')->relationship('purchaseOrder', 'po_number'),
                \Filament\Forms\Components\Select::make('grn_id')
                    ->relationship('grn', 'grn_number')
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $grn = Grn::find($state);
                            if ($grn) {
                                $set('vendor_id', $grn->vendor_id);
                                $set('branch_id', $grn->branch_id);
                                $set('purchase_order_id', $grn->purchase_order_id);
                            }
                        }
                    }),
                \Filament\Forms\Components\Select::make('vendor_id')->relationship('vendor', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                DatePicker::make('return_date')
                    ->required(),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Textarea::make('reason')
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                    
                Section::make('GRN Details')
                    ->relationship('grn')
                    ->schema(\App\Filament\Resources\Grns\Schemas\GrnForm::configure(\Filament\Schemas\Schema::make($schema->getLivewire()))->getComponents())
                    ->columnSpan('full')
                    ->columns(2)
                    ->visible(fn (callable $get) => filled($get('grn_id'))),
            ]);
    }
}
