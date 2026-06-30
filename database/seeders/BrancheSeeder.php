<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class BrancheSeeder extends Seeder {
    public function run(): void {
        DB::table('branches')->truncate();
        $branches = [
            ['name' => 'T Nagar Branch', 'code' => 'TGR'],
            ['name' => 'Velachery Branch', 'code' => 'VLC'],
            ['name' => 'Coimbatore Main', 'code' => 'CBE']
        ];
        foreach($branches as $index => $b) {
            DB::table('branches')->insert([
                'company_id' => 1,
                'name' => $b['name'],
                'code' => $b['code'],
                'email' => strtolower($b['code']) . '@thechennaisilks.com',
                'phone' => '987654321' . $index,
                'address' => $b['name'] . ' Main Road',
                'city_id' => ($index == 2) ? 2 : 1,
                'state_id' => 1,
                'country_id' => 1,
                'pin_code' => '6000' . (10 + $index),
                'status' => true,
            ]);
        }
    }
}
