<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PatternTypeSeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('pattern_types')->truncate();
        $patterns = ['Solid', 'Striped', 'Checked', 'Floral', 'Printed', 'Polka Dot', 'Geometric'];
        foreach($patterns as $p) {
            DB::table('pattern_types')->insert([
                'name' => $p, 'code' => strtoupper(substr($p, 0, 3)), 'status' => true
            ]);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
