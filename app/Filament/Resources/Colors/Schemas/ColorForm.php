<?php

namespace App\Filament\Resources\Colors\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ColorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (?string $state, Set $set) {
                        if (blank($state)) return;
                        
                        $colors = [
                            'red' => '#FF0000', 'blue' => '#0000FF', 'green' => '#008000', 
                            'black' => '#000000', 'white' => '#FFFFFF', 'yellow' => '#FFFF00',
                            'pink' => '#FFC0CB', 'purple' => '#800080', 'grey' => '#808080', 
                            'navy' => '#000080', 'orange' => '#FFA500', 'brown' => '#A52A2A',
                            'cyan' => '#00FFFF', 'magenta' => '#FF00FF', 'lime' => '#00FF00',
                            'maroon' => '#800000', 'olive' => '#808000', 'silver' => '#C0C0C0'
                        ];
                        
                        $key = strtolower(trim($state));
                        if (array_key_exists($key, $colors)) {
                            $set('hex_code', $colors[$key]);
                        }
                    }),
                TextInput::make('code')
                    ->required()
                    ->default(fn () => 'COL-' . str_pad((string)(\App\Models\Color::max('id') + 1), 4, '0', STR_PAD_LEFT))
                    ->disabled()
                    ->dehydrated()
                    ->unique(ignoreRecord: true),
                ColorPicker::make('hex_code'),
                Toggle::make('status')
                    ->required(),
            ]);
    }
}
