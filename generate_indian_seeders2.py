import os

def write_seeder(name, content):
    path = os.path.join('database', 'seeders', name)
    with open(path, 'w', encoding='utf-8') as f:
        f.write(content)

seeders = {}

seeders['EmployeeSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
use Faker\\Factory as Faker;
class EmployeeSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create('en_IN');
        DB::table('employees')->truncate();
        for ($i = 1; $i <= 10; $i++) {
            DB::table('employees')->insert([
                'user_id' => clone $i, // linking to 1..10
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'employee_code' => 'EMP' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->numerify('9#########'),
                'department_id' => $faker->numberBetween(1, 10),
                'designation_id' => 1,
                'date_of_joining' => $faker->date(),
                'status' => 'active',
            ]);
        }
    }
}
"""

seeders['VendorSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
use Faker\\Factory as Faker;
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
                'city_id' => $faker->numberBetween(1, 10),
                'state_id' => $faker->numberBetween(1, 5),
                'country_id' => 1,
                'status' => true,
            ]);
        }
    }
}
"""

seeders['CustomerSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
use Faker\\Factory as Faker;
class CustomerSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create('en_IN');
        DB::table('customers')->truncate();
        for ($i = 1; $i <= 10; $i++) {
            DB::table('customers')->insert([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->numerify('9#########'),
                'address' => $faker->address,
                'city_id' => $faker->numberBetween(1, 10),
                'state_id' => $faker->numberBetween(1, 5),
                'country_id' => 1,
                'pin_code' => $faker->numerify('600###'),
                'status' => true,
            ]);
        }
    }
}
"""

seeders['BrandSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class BrandSeeder extends Seeder {
    public function run(): void {
        DB::table('brands')->truncate();
        $brands = ['Nalli', 'Pothys', 'Kumaran Silks', 'RMKV', 'FabIndia', 'Biba', 'Manyavar', 'Raymond', 'Peter England', 'Allen Solly'];
        foreach ($brands as $b) {
            DB::table('brands')->insert([
                'name' => $b, 'code' => strtoupper(substr($b, 0, 3)), 'status' => true
            ]);
        }
    }
}
"""

seeders['ProductCategorySeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class ProductCategorySeeder extends Seeder {
    public function run(): void {
        DB::table('product_categories')->truncate();
        $cats = ['Sarees', 'Silk Sarees', 'Cotton Sarees', 'Shirts', 'T-Shirts', 'Jeans', 'Kurtas', 'Kids Wear', 'Footwear', 'Handbags', 'Jewellery', 'Cosmetics', 'Grocery', 'Home Furnishing'];
        foreach ($cats as $c) {
            DB::table('product_categories')->insert([
                'name' => $c, 'code' => strtoupper(substr($c, 0, 3)) . rand(10, 99), 'status' => true
            ]);
        }
    }
}
"""

seeders['ProductSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
use Faker\\Factory as Faker;
class ProductSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create('en_IN');
        DB::table('products')->truncate();
        for ($i = 1; $i <= 15; $i++) {
            DB::table('products')->insert([
                'name' => 'Premium ' . $faker->word . ' ' . $faker->word,
                'code' => 'PRD' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'brand_id' => $faker->numberBetween(1, 10),
                'category_id' => $faker->numberBetween(1, 14),
                'hsn_code' => '620' . $faker->numberBetween(1, 9),
                'price' => $faker->randomFloat(2, 500, 5000),
                'cost' => $faker->randomFloat(2, 200, 3000),
                'status' => true,
            ]);
        }
    }
}
"""

for k, v in seeders.items():
    write_seeder(k, v)

print("Seeders generation part 2 complete.")
