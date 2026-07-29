<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class GstCategorySeeder extends Seeder {
    public function run(): void {
        \Schema::disableForeignKeyConstraints();
        DB::table('gst_categories')->truncate();
        
        $categories = [
            ['name' => 'Exempted Goods', 'code' => 'EXMPT', 'status' => true],
            ['name' => 'Taxable Goods', 'code' => 'TXBL', 'status' => true],
            ['name' => 'Nil Rated', 'code' => 'NIL', 'status' => true],
        ];
        
        foreach($categories as $c) {
            DB::table('gst_categories')->insert($c);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
