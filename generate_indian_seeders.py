import os

def write_seeder(name, content):
    path = os.path.join('database', 'seeders', name)
    with open(path, 'w', encoding='utf-8') as f:
        f.write(content)

seeders = {}

seeders['CountrySeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class CountrySeeder extends Seeder {
    public function run(): void {
        DB::table('countries')->truncate();
        DB::table('countries')->insert([
            'name' => 'India', 'code' => 'IND', 'status' => true
        ]);
    }
}
"""

seeders['StateSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class StateSeeder extends Seeder {
    public function run(): void {
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
    }
}
"""

seeders['CitySeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class CitySeeder extends Seeder {
    public function run(): void {
        DB::table('cities')->truncate();
        $cities = ['Chennai', 'Coimbatore', 'Madurai', 'Trichy', 'Salem', 'Bangalore', 'Mumbai', 'Delhi', 'Kochi', 'Trivandrum'];
        foreach($cities as $index => $city) {
            DB::table('cities')->insert([
                'state_id' => ($index < 5) ? 1 : (($index == 5) ? 3 : (($index == 6) ? 4 : (($index == 7) ? 5 : 2))),
                'name' => $city, 'code' => strtoupper(substr($city, 0, 3)), 'status' => true
            ]);
        }
    }
}
"""

seeders['CompanySeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class CompanySeeder extends Seeder {
    public function run(): void {
        DB::table('companies')->truncate();
        DB::table('companies')->insert([
            'name' => 'The Chennai Silks', 'code' => 'TCS',
            'gst_number' => '33AADCT4422E1Z2', 'pan_number' => 'AADCT4422E',
            'email' => 'info@thechennaisilks.com', 'phone' => '9876543210',
            'website' => 'www.thechennaisilks.com', 'logo' => 'logo.png',
            'address' => 'No. 31, Usman Road, T Nagar',
            'city_id' => 1, 'state_id' => 1, 'country_id' => 1,
            'pin_code' => '600017', 'status' => true,
        ]);
    }
}
"""

seeders['BrancheSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
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
"""

seeders['WarehousSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class WarehousSeeder extends Seeder {
    public function run(): void {
        DB::table('warehouses')->truncate();
        $whs = [
            ['name' => 'Chennai Central Warehouse', 'code' => 'WH-CHE'],
            ['name' => 'Coimbatore Hub', 'code' => 'WH-CBE']
        ];
        foreach($whs as $index => $w) {
            DB::table('warehouses')->insert([
                'branch_id' => ($index == 0) ? 1 : 3,
                'name' => $w['name'],
                'code' => $w['code'],
                'email' => strtolower($w['code']) . '@thechennaisilks.com',
                'phone' => '998877665' . $index,
                'address' => $w['name'] . ' Ind. Estate',
                'city_id' => ($index == 0) ? 1 : 2,
                'state_id' => 1,
                'country_id' => 1,
                'pin_code' => '6000' . (50 + $index),
                'status' => true,
            ]);
        }
    }
}
"""

seeders['DepartmentSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class DepartmentSeeder extends Seeder {
    public function run(): void {
        DB::table('departments')->truncate();
        $deps = ['Sales', 'Purchase', 'Inventory', 'Accounts', 'HR', 'IT', 'Marketing', 'Customer Support', 'Logistics', 'Management'];
        foreach($deps as $index => $d) {
            DB::table('departments')->insert([
                'name' => $d,
                'code' => strtoupper(substr($d, 0, 3)) . $index,
                'status' => true,
            ]);
        }
    }
}
"""

seeders['RoleSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class RoleSeeder extends Seeder {
    public function run(): void {
        DB::table('roles')->truncate();
        $roles = ['Super Admin', 'Branch Manager', 'Sales Executive', 'Cashier', 'Store Keeper', 'Accountant', 'HR Manager', 'Purchase Officer', 'Marketing Head', 'Customer Care Executive'];
        foreach($roles as $r) {
            DB::table('roles')->insert([
                'name' => $r,
                'guard_name' => 'web',
            ]);
        }
    }
}
"""

seeders['UserSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
use Faker\\Factory as Faker;
use Illuminate\\Support\\Facades\\Hash;
class UserSeeder extends Seeder {
    public function run(): void {
        $faker = Faker::create('en_IN');
        DB::table('users')->truncate();
        
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'created_at' => now(), 'updated_at' => now(),
        ]);
        
        for ($i = 0; $i < 9; $i++) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }
}
"""

for k, v in seeders.items():
    write_seeder(k, v)

print("Seeders generation part 1 complete.")
