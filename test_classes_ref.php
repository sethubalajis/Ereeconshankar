<?php
require 'vendor/autoload.php';

try {
    $ref = new ReflectionClass('Filament\Tables\Actions\Action');
    echo $ref->getFileName();
} catch (Exception $e) {
    echo "Class Filament\\Tables\\Actions\\Action not found\n";
}

try {
    $ref = new ReflectionClass('Filament\Tables\Actions\EditAction');
    echo $ref->getFileName();
} catch (Exception $e) {
    echo "Class Filament\\Tables\\Actions\\EditAction not found\n";
}
