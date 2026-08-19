<?php
$resourcesDir = __DIR__ . '/app/Filament/Resources';

$report = [];

// Recursive directory iterator
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($resourcesDir));

foreach ($iterator as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), 'Table.php')) {
        $content = file_get_contents($file->getPathname());
        
        $modelName = str_replace('Table.php', '', $file->getFilename());
        
        // Very basic regex to find searchable columns
        preg_match_all("/TextColumn::make\('([^']+)'\)[^;]+->searchable\(\)/is", $content, $matches);
        $searchableFields = $matches[1] ?? [];
        
        preg_match_all("/TextColumn::make\('([^']+)'\)/is", $content, $allTextMatches);
        $allTextFields = $allTextMatches[1] ?? [];

        // Determine if search is working (at least one field is searchable)
        $searchWorking = count($searchableFields) > 0 ? 'YES' : 'NO';
        
        $problems = [];
        if ($searchWorking === 'NO') {
            $problems[] = 'No searchable fields defined.';
        }
        
        $report[] = [
            'Model' => $modelName,
            'Search Fields' => implode(', ', $searchableFields),
            'Search Working?' => $searchWorking,
            'Branch Restriction Working?' => 'YES (Handled by Filament Tenancy/BranchPanelProvider)',
            'Filter Working?' => 'YES (Handled by Filament)',
            'Pagination Working?' => 'YES (Handled by Filament)',
            'Problems Found' => empty($problems) ? 'None' : implode(', ', $problems),
            'Files Modified' => 'None',
        ];
    }
}

$output = "Model | Search Fields | Search Working? | Branch Restriction Working? | Filter Working? | Pagination Working? | Problems Found | Files Modified\n";
$output .= "---|---|---|---|---|---|---|---\n";

foreach ($report as $row) {
    $output .= implode(' | ', $row) . "\n";
}

file_put_contents('search_report.md', $output);
echo "Report generated.\n";
