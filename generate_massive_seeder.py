import os
import re
from collections import defaultdict, deque

migrations_dir = r"d:\e-reckon-erp\database\migrations"

tables = {}
dependencies = defaultdict(list)

# Parse migrations
for filename in os.listdir(migrations_dir):
    if not filename.endswith('.php'): continue
    filepath = os.path.join(migrations_dir, filename)
    with open(filepath, 'r') as f:
        content = f.read()
    
    # Find Schema::create('table_name'
    match = re.search(r"Schema::create\('([^']+)'", content)
    if not match: continue
    table_name = match.group(1)
    
    # Extract columns
    columns = []
    # Match $table->type('name')
    for col_match in re.finditer(r"\$table->([a-zA-Z0-9_]+)\('([^']+)'", content):
        col_type = col_match.group(1)
        col_name = col_match.group(2)
        columns.append({'name': col_name, 'type': col_type})
        
        # Check for dependencies
        if col_type == 'foreignId' or col_name.endswith('_id'):
            # try to find constrained('table')
            constr = re.search(r"\$table->.*?\('" + col_name + r"'\).*?->constrained\('([^']+)'\)", content)
            if constr:
                dependencies[table_name].append(constr.group(1))
            else:
                # Guess table name
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

# Add any remaining (cycles)
for t in tables:
    if t not in sorted_tables:
        sorted_tables.append(t)

# Generate Seeder
seeder_code = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
use Faker\\Factory as Faker;
use Illuminate\\Support\\Facades\\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        Schema::disableForeignKeyConstraints();
        
        // Truncate all tables
"""
for t in reversed(sorted_tables):
    if t not in ['migrations', 'users', 'sessions']:
        seeder_code += f"        DB::table('{t}')->truncate();\n"

seeder_code += """
        // Users
        DB::table('users')->truncate();
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
"""

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

for table in sorted_tables:
    if table in ['migrations', 'users', 'sessions', 'cache', 'cache_locks', 'jobs', 'job_batches', 'failed_jobs']: continue
    
    seeder_code += f"\n        // Seeding {table}\n"
    seeder_code += f"        echo \"Seeding {table}...\\n\";\n"
    seeder_code += f"        for($i=0; $i<5; $i++) {{\n"
    seeder_code += f"            DB::table('{table}')->insert([\n"
    
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
                val = "$adminId"
            elif ref in tables:
                val = f"DB::table('{ref}')->inRandomOrder()->value('id') ?? 1"
            else:
                val = "1"
        else:
            val = generate_faker(ctype, name)
            
        seeder_code += f"                '{name}' => {val},\n"
        
    seeder_code += "            ]);\n"
    seeder_code += "        }\n"

seeder_code += """
        Schema::enableForeignKeyConstraints();
        $this->command->info('All modules seeded successfully with random sample data!');
    }
}
"""

with open(os.path.join(r"d:\e-reckon-erp\database\seeders", "DatabaseSeeder.php"), 'w', encoding='utf-8') as f:
    f.write(seeder_code)

print("Master Seeder Generated.")
