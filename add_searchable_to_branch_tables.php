<?php

$directory = 'c:\Users\Admin\reckon\ereckontamil\app\Filament\Branch\Resources';

// Recursive directory iterator
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
$regex = new RegexIterator($iterator, '/^.+Table\.php$/i', RecursiveRegexIterator::GET_MATCH);

$searchableFields = ['name', 'code', 'sku', 'email', 'phone', 'number', 'reference', 'title'];

foreach ($regex as $file) {
    $filepath = $file[0];
    
    // Make sure it is inside 'Tables' directory
    if (strpos($filepath, DIRECTORY_SEPARATOR . 'Tables' . DIRECTORY_SEPARATOR) === false) {
        continue;
    }
    
    $content = file_get_contents($filepath);
    $lines = explode("\n", $content);
    $newLines = [];
    $modified = false;
    
    foreach ($lines as $i => $line) {
        $newLines[] = $line;
        
        if (preg_match('/TextColumn::make\([\'"]([^\'"]+)[\'"]\)/', $line, $matches)) {
            $field = $matches[1];
            $fieldLower = strtolower($field);
            
            $isSearchableField = false;
            foreach ($searchableFields as $s) {
                if (str_ends_with($fieldLower, $s) || $fieldLower === $s) {
                    $isSearchableField = true;
                    break;
                }
            }
            
            if ($isSearchableField) {
                if (strpos($line, '->searchable()') !== false) {
                    continue;
                }
                
                $alreadySearchable = false;
                for ($j = 1; $j <= 5; $j++) {
                    if (isset($lines[$i + $j])) {
                        $nextLine = trim($lines[$i + $j]);
                        if (str_starts_with($nextLine, '->searchable(')) {
                            $alreadySearchable = true;
                            break;
                        }
                        if (str_starts_with($nextLine, 'TextColumn::make') || 
                            str_starts_with($nextLine, 'ImageColumn') || 
                            str_starts_with($nextLine, 'IconColumn') || 
                            str_starts_with($nextLine, '])')) {
                            break;
                        }
                    }
                }
                
                if (!$alreadySearchable) {
                    preg_match('/^(\s*)/', $line, $indentMatch);
                    $indent = strlen($indentMatch[1]);
                    $newLineStr = str_repeat(' ', $indent + 4) . '->searchable()';
                    $newLines[] = $newLineStr;
                    $modified = true;
                }
            }
        }
    }
    
    if ($modified) {
        file_put_contents($filepath, implode("\n", $newLines));
        echo "Updated " . basename($filepath) . "\n";
    }
}
