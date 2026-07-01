<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PatternTypeSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pattern_types')->truncate();
        $patterns = ['Solid', 'Striped', 'Checked', 'Floral', 'Printed', 'Polka Dot', 'Geometric'];
        foreach($patterns as $p) {
            DB::table('pattern_types')->insert([
                'name' => $p, 'code' => strtoupper(substr($p, 0, 3)), 'status' => true
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
