import os
import re
from collections import defaultdict, deque

migrations_dir = r"d:\e-reckon-erp\database\migrations"
seeders_dir = r"d:\e-reckon-erp\database\seeders"

tables = {}
dependencies = defaultdict(list)
table_to_model = {}

# Parse migrations
for filename in os.listdir(migrations_dir):
    if not filename.endswith('.php'): continue
    filepath = os.path.join(migrations_dir, filename)
    with open(filepath, 'r') as f:
        content = f.read()
    
    match = re.search(r"Schema::create\('([^']+)'", content)
    if not match: continue
    table_name = match.group(1)
    
    # Guess model name
    words = table_name.split('_')
    if words[-1].endswith('ies'):
        words[-1] = words[-1][:-3] + 'y'
    elif words[-1].endswith('ses'):
        words[-1] = words[-1][:-2]
    elif words[-1].endswith('s'):
        words[-1] = words[-1][:-1]
    
    model_name = ''.join(word.capitalize() for word in words)
    table_to_model[table_name] = model_name
    
    columns = []
    for col_match in re.finditer(r"\$table->([a-zA-Z0-9_]+)\('([^']+)'", content):
        col_type = col_match.group(1)
        col_name = col_match.group(2)
        columns.append({'name': col_name, 'type': col_type})
        
        if col_type == 'foreignId' or col_name.endswith('_id'):
            constr = re.search(r"\$table->.*?\('" + col_name + r"'\).*?->constrained\('([^']+)'\)", content)
            if constr:
                dependencies[table_name].append(constr.group(1))
            else:
                ref_table = col_name[:-3] + 's'
                if ref_table == 'companys': ref_table = 'companies'
                if ref_table == 'branchs': ref_table = 'branches'
                dependencies[table_name].append(ref_table)

    tables[table_name] = columns

# Topological Sort
in_degree = {t: 0 for t in tables}
graph = defaultdict(list)

for u, deps in dependencies.items():
    for v in deps:
        if v in tables and u in tables:
            graph[v].append(u)
            in_degree[u] += 1

queue = deque([t for t in tables if in_degree[t] == 0])
sorted_tables = []

while queue:
    u = queue.popleft()
    sorted_tables.append(u)
    for v in graph[u]:
        in_degree[v] -= 1
        if in_degree[v] == 0:
            queue.append(v)

for t in tables:
    if t not in sorted_tables:
        sorted_tables.append(t)

def generate_faker(col_type, col_name):
    if col_name == 'email': return "$faker->unique()->safeEmail"
    if col_name == 'phone': return "$faker->numerify('##########')"
    if 'code' in col_name: return "substr(strtoupper($faker->unique()->bothify('???###')), 0, 3)"
    if col_name == 'symbol': return "$faker->randomElement(['$', '€', '₹', '£', '¥'])"
    if col_name == 'status': 
        if col_type == 'boolean': return "true"
        else: return "'active'"
    
    if col_type == 'string': return "$faker->word . ' ' . $faker->word"
    if col_type == 'text': return "$faker->sentence"
    if col_type == 'boolean': return "true"
    if col_type == 'date': return "now()->subDays(rand(1, 30))"
    if col_type == 'timestamp': return "now()"
    if col_type == 'time': return "now()->format('H:i:s')"
    if col_type in ['decimal', 'integer', 'unsignedBigInteger', 'bigInteger', 'tinyInteger', 'smallInteger', 'mediumInteger']: 
        return "rand(1, 9)"
    return "''"

# Generate Individual Seeders
database_seeder_calls = []

for table in sorted_tables:
    if table in ['migrations', 'users', 'sessions', 'cache', 'cache_locks', 'jobs', 'job_batches', 'failed_jobs']: continue
    
    model = table_to_model.get(table, "Unknown")
    seeder_name = f"{model}Seeder"
    database_seeder_calls.append(f"        $this->call({seeder_name}::class);")
    
    code = f"""<?php

namespace Database\\Seeders;

use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
use Faker\\Factory as Faker;

class {seeder_name} extends Seeder
{{
    public function run(): void
    {{
        $faker = Faker::create();
        
        for ($i = 0; $i < 5; $i++) {{
            DB::table('{table}')->insert([
"""
    for col in tables[table]:
        name = col['name']
        ctype = col['type']
        if name in ['id', 'created_at', 'updated_at']: continue
        
        if ctype == 'foreignId' or name.endswith('_id'):
            ref = name[:-3] + 's'
            if ref == 'companys': ref = 'companies'
            if ref == 'branchs': ref = 'branches'
            if ref == 'categorys': ref = 'categories'
            
            if ref == 'users':
                val = "DB::table('users')->inRandomOrder()->value('id') ?? 1"
            elif ref in tables:
                val = f"DB::table('{ref}')->inRandomOrder()->value('id') ?? 1"
            else:
                val = "1"
        else:
            val = generate_faker(ctype, name)
            
        code += f"                '{name}' => {val},\n"

    code += """            ]);
        }
    }
}
"""
    with open(os.path.join(seeders_dir, f"{seeder_name}.php"), 'w', encoding='utf-8') as f:
        f.write(code)

# Generate Main DatabaseSeeder
db_seeder = f"""<?php

namespace Database\\Seeders;

use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
use Illuminate\\Support\\Facades\\Schema;

class DatabaseSeeder extends Seeder
{{
    public function run(): void
    {{
        Schema::disableForeignKeyConstraints();
        
        // Seed an admin user first
        DB::table('users')->truncate();
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
{chr(10).join(database_seeder_calls)}

        Schema::enableForeignKeyConstraints();
    }}
}}
"""

with open(os.path.join(seeders_dir, "DatabaseSeeder.php"), 'w', encoding='utf-8') as f:
    f.write(db_seeder)

print("Generated individual related seeders for all models.")
