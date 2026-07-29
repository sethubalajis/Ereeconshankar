<?php
$files = glob('C:/Users/Admin/reckon/ereckontamil/app/Filament/Resources/*/Tables/*Table.php');
foreach($files as $f) {
    $content = file_get_contents($f);
    $newContent = str_replace('\Filament\Tables\Actions\Action::make(\'download_pdf\')', '\Filament\Tables\Actions\Action::make(\'download_pdf\')', $content); // wait
    $newContent = str_replace('\Filament\Tables\Actions\Action::make(\'download_pdf\')', '\Filament\Actions\Action::make(\'download_pdf\')', $content);
    if ($content !== $newContent) {
        file_put_contents($f, $newContent);
        echo "Reverted $f\n";
    }
}
