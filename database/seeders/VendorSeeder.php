<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class VendorSeeder extends Seeder {
    public function run(): void
    {
        $faker = \Faker\Factory::create('en_IN');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        if (! DB::table('vendor_categories')->exists()) {
            $this->call(VendorCategorySeeder::class);
        }

        $categoryIds = DB::table('vendor_categories')->pluck('id', 'code');

        DB::table('vendors')->truncate();

        $vendors = [
            ['name' => 'Surat Fabric Corporation', 'category_code' => 'VEN001'],
            ['name' => 'Tiruppur Yarn House', 'category_code' => 'VEN002'],
            ['name' => 'Navrang Dye Processors', 'category_code' => 'VEN003'],
            ['name' => 'PrintCraft Textiles', 'category_code' => 'VEN004'],
            ['name' => 'Trims World India', 'category_code' => 'VEN005'],
            ['name' => 'Elite Embroideries', 'category_code' => 'VEN006'],
            ['name' => 'Precision Stitch Works', 'category_code' => 'VEN007'],
            ['name' => 'PackPro Materials', 'category_code' => 'VEN008'],
            ['name' => 'TexChem Solutions', 'category_code' => 'VEN009'],
            ['name' => 'Swift Loom Logistics', 'category_code' => 'VEN010'],
        ];

        foreach ($vendors as $index => $vendor) {
            DB::table('vendors')->insert([
                'vendor_category_id' => $categoryIds[$vendor['category_code']] ?? null,
                'name' => $vendor['name'],
                'code' => 'VEN' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->numerify('9#########'),
                'contact_person' => $faker->name,
                'gst_number' => $faker->numerify('24AAACV####A#Z#'),
                'pan_number' => $faker->numerify('AAACV####A'),
                'address' => $faker->address,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
