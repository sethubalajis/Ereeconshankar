<?php
require 'vendor/autoload.php';

$classes = [
    'Filament\Tables\Actions\Action',
    'Filament\Actions\Action',
    'Filament\Tables\Actions\EditAction',
    'Filament\Actions\EditAction'
];

foreach ($classes as $class) {
    echo $class . ' => ' . (class_exists($class) ? 'EXISTS' : 'NOT FOUND') . "\n";
}
