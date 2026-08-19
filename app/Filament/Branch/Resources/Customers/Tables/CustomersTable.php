<?php

namespace App\Filament\Branch\Resources\Customers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customerGroup.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('dob')
                    ->date()
                    ->sortable(),
                TextColumn::make('anniversary')
                    ->date()
                    ->sortable(),
                TextColumn::make('gst_number')
                    ->searchable(),
                TextColumn::make('total_points')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('wallet_balance')
                    ->money('INR')
                    ->sortable(),
                IconColumn::make('status')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->modifyQueryUsing(function (\Illuminate\Database\Eloquent\Builder $query) {
                return $query->orderByDesc(
                    \App\Models\PosSale::select('created_at')
                        ->whereColumn('customer_id', 'customers.id')
                        ->where('status', 'completed')
                        ->latest('created_at')
                        ->take(1)
                )->orderByDesc('id');
            })
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
