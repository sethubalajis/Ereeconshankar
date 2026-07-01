<?php

namespace App\Filament\Resources\Grns\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;
use App\Models\PurchaseOrder;

class GrnForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('purchase_order_id')
                    ->relationship('purchaseOrder', 'po_number')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $po = PurchaseOrder::find($state);
                            if ($po) {
                                $set('vendor_id', $po->vendor_id);
                                $set('branch_id', $po->branch_id);
                            }
                        }
                    })
                    ->createOptionForm(fn (\Filament\Schemas\Schema $schema) => \App\Filament\Resources\PurchaseOrders\Schemas\PurchaseOrderForm::configure($schema)->getComponents())
                    ->editOptionForm(fn (\Filament\Schemas\Schema $schema) => \App\Filament\Resources\PurchaseOrders\Schemas\PurchaseOrderForm::configure($schema)->getComponents()),
                    
                \Filament\Forms\Components\Select::make('vendor_id')->relationship('vendor', 'name')
                    ->required(),
                \Filament\Forms\Components\Select::make('branch_id')->relationship('branch', 'name')
                    ->required(),
                TextInput::make('grn_number')
                    ->required(),
                DatePicker::make('receive_date')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('received'),
                    
                Section::make('Purchase Order Details')
                    ->relationship('purchaseOrder')
                    ->schema(\App\Filament\Resources\PurchaseOrders\Schemas\PurchaseOrderForm::configure(\Filament\Schemas\Schema::make($schema->getLivewire()))->getComponents())
                    ->columnSpan('full')
                    ->columns(2)
                    ->visible(fn (callable $get) => filled($get('purchase_order_id'))),
            ]);
    }
}
