<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class VendorSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create('en_IN');
        DB::table('vendors')->truncate();
        $vendor_names = ['Surat Textiles', 'Tiruppur Cottons', 'Mumbai Silks', 'Kanchipuram Weavers', 'Delhi Garments', 'Ahmedabad Mills', 'Ludhiana Woolens', 'Kolkata Jute', 'Banaras Handlooms', 'Mysore Silks'];
        foreach ($vendor_names as $index => $name) {
            DB::table('vendors')->insert([
                'name' => $name,
                'code' => 'VEN' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->numerify('9#########'),
                'contact_person' => $faker->name,
                'gst_number' => $faker->numerify('24AAACV####A#Z#'),
                'pan_number' => $faker->numerify('AAACV####A'),
                'address' => $faker->address,
                'status' => true,
            ]);
        }
    }
}
