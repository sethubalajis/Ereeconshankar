<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'Men Shirts', 'code' => 'MSH'],
            ['name' => 'Women Tops', 'code' => 'WTP'],
            ['name' => 'Kids Wear', 'code' => 'KWR'],
            ['name' => 'Jeans', 'code' => 'JNS'],
            ['name' => 'Trousers', 'code' => 'TRS'],
        ];

        foreach ($rows as $row) {
            DB::table('product_sub_categories')->insert([
                'product_category_id' => 1,
                'name' => $row['name'],
                'code' => $row['code'],
                'description' => $row['name'] . ' sub category',
                'status' => true,
            ]);
        }
        \Schema::enableForeignKeyConstraints();
    }
}
