<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class StateSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('states')->truncate();
        $states = [
            ['Tamil Nadu', 'TN'], ['Kerala', 'KL'], ['Karnataka', 'KA'], 
            ['Maharashtra', 'MH'], ['Delhi', 'DL']
        ];
        foreach($states as $state) {
            DB::table('states')->insert([
                'country_id' => 1, 'name' => $state[0], 'code' => $state[1], 'status' => true
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
