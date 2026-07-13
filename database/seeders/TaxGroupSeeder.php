<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
<<<<<<< HEAD
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
=======

class TaxGroupSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'name' => 'GST 5% Fabric',
                'description' => 'Applicable GST rate for woven and knitted fabric sales',
                'total_tax_percentage' => 5.00,
            ],
            [
                'name' => 'GST 12% Readymade Garments',
                'description' => 'GST for readymade garments above threshold value',
                'total_tax_percentage' => 12.00,
            ],
            [
                'name' => 'GST 18% Textile Accessories',
                'description' => 'GST for textile accessories, trims, and related items',
                'total_tax_percentage' => 18.00,
            ],
            [
                'name' => 'GST 5% Yarn',
                'description' => 'Applicable GST rate for yarn and thread materials',
                'total_tax_percentage' => 5.00,
            ],
            [
                'name' => 'GST 12% Home Textiles',
                'description' => 'GST for home textile products such as bedsheets and curtains',
                'total_tax_percentage' => 12.00,
            ],
        ];

        foreach ($rows as $row) {
            DB::table('tax_groups')->insert([
                'name' => $row['name'],
                'description' => $row['description'],
                'total_tax_percentage' => $row['total_tax_percentage'],
            ]);
>>>>>>> origin/main
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
