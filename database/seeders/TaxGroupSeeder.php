<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TaxGroupSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tax_groups')->truncate();
        
        $groups = [
            ['name' => 'GST 5%', 'description' => 'Standard GST at 5% for essential apparel', 'total_tax_percentage' => 5.00],
            ['name' => 'GST 12%', 'description' => 'Standard GST at 12% for apparel', 'total_tax_percentage' => 12.00],
            ['name' => 'GST 18%', 'description' => 'Standard GST at 18%', 'total_tax_percentage' => 18.00],
        ];
        
        foreach($groups as $g) {
            DB::table('tax_groups')->insert($g);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
