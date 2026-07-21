<?php

namespace App\Filament\Resources\DashboardWidgets\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class DashboardWidgetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('name')
                    ->options([
                        'Sales Overview' => 'Sales Overview',
                        'Inventory Status' => 'Inventory Status',
                        'Recent Orders' => 'Recent Orders',
                        'Top Products' => 'Top Products',
                        'Revenue Chart' => 'Revenue Chart',
                        'Expense Summary' => 'Expense Summary',
                    ])
                    ->required(),
                Select::make('type')
                    ->options([
                        'chart' => 'Chart',
                        'table' => 'Table',
                        'metric' => 'Metric',
                        'list' => 'List',
                        'bar' => 'Bar Chart',
                        'line' => 'Line Chart',
                        'pie' => 'Pie Chart',
                    ])
                    ->required(),
                Textarea::make('config')
                    ->columnSpanFull(),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
