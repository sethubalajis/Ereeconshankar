import os
import re

resources_dir = r"d:\e-reckon-erp\app\Filament\Resources"

updated_count = 0
for root, dirs, files in os.walk(resources_dir):
    for file in files:
        if file.endswith("Resource.php"):
            filepath = os.path.join(root, file)
            with open(filepath, 'r') as f:
                content = f.read()
                
            if "protected static ?string $navigationGroup" in content:
                # Need to replace and add use UnitEnum; if not present, but wait, UnitEnum is in global namespace in PHP 8.1+
                new_content = content.replace("protected static ?string $navigationGroup", "protected static \\UnitEnum|string|null $navigationGroup")
                with open(filepath, 'w') as f:
                    f.write(new_content)
                updated_count += 1
                
print(f"Updated {updated_count} resources.")
