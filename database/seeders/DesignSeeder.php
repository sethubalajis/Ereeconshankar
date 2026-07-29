<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DesignSeeder extends Seeder
{
    public function run(): void
    {
        \Schema::disableForeignKeyConstraints();
        DB::table('designs')->truncate();
        
        $designs = [
            ['name' => 'Slim Fit', 'code' => 'SLIM', 'desc' => 'Tapered and fitted design.'],
            ['name' => 'Regular Fit', 'code' => 'REG', 'desc' => 'Standard comfort fit design.'],
            ['name' => 'Skinny Fit', 'code' => 'SKN', 'desc' => 'Very tight fit, hugging the body.'],
            ['name' => 'A-Line', 'code' => 'ALN', 'desc' => 'Fitted at the hips and gradually widens.'],
            ['name' => 'Anarkali', 'code' => 'ANR', 'desc' => 'Long, frock-style top and features a slim fitted bottom.'],
            ['name' => 'Straight Cut', 'code' => 'STR', 'desc' => 'Straight and uniform cut.'],
            ['name' => 'Pleated', 'code' => 'PLT', 'desc' => 'Folded or gathered fabric design.'],
            ['name' => 'V-Neck', 'code' => 'VNK', 'desc' => 'V-shaped neckline design.'],
        ];

        foreach ($designs as $design) {
            DB::table('designs')->insert([
                'name' => $design['name'],
                'code' => $design['code'],
                'description' => $design['desc'],
                'status' => true,
            ]);
        }
        
        \Schema::enableForeignKeyConstraints();
    }
}
