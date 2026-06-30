import os

models_dir = r"d:\e-reckon-erp\app\Models"
seeders_dir = r"d:\e-reckon-erp\database\seeders"

models = [f.replace('.php', '') for f in os.listdir(models_dir) if f.endswith('.php')]

database_seeder_calls = []

for model in models:
    if model in ['User', 'Session']:  # Skip built-in models or ones we already seeded manually if we want
        continue
        
    seeder_name = f"{model}Seeder"
    seeder_code = f"""<?php

namespace Database\\Seeders;

use Illuminate\\Database\\Seeder;
use App\\Models\\{model};

class {seeder_name} extends Seeder
{{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {{
        // TODO: Add dummy data for {model}
        // {model}::create([
        //     'column_name' => 'value',
        // ]);
    }}
}}
"""
    with open(os.path.join(seeders_dir, f"{seeder_name}.php"), 'w') as f:
        f.write(seeder_code)
        
    database_seeder_calls.append(f"        $this->call({seeder_name}::class);")

# Update DatabaseSeeder to call all of them
db_seeder_path = os.path.join(seeders_dir, "DatabaseSeeder.php")
with open(db_seeder_path, 'r') as f:
    db_seeder_content = f.read()

# Replace the run method or just append to it if it doesn't already have these calls.
# It's better to just generate a clean DatabaseSeeder.

new_db_seeder = f"""<?php

namespace Database\\Seeders;

use Illuminate\\Database\\Seeder;

class DatabaseSeeder extends Seeder
{{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {{
{chr(10).join(database_seeder_calls)}
    }}
}}
"""

with open(db_seeder_path, 'w') as f:
    f.write(new_db_seeder)

print(f"Generated {len(models)-2} seeders.")
