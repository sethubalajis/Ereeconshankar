import os
import subprocess

seeders = {}

seeders['CountrySeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class CountrySeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('countries')->truncate();
        DB::table('countries')->insert([
            'name' => 'India', 'code' => 'IND', 'status' => true
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['StateSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class StateSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
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
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['CitySeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class CitySeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cities')->truncate();
        $cities = ['Chennai', 'Coimbatore', 'Madurai', 'Trichy', 'Salem', 'Bangalore', 'Mumbai', 'Delhi', 'Kochi', 'Trivandrum'];
        foreach($cities as $index => $city) {
            DB::table('cities')->insert([
                'state_id' => ($index < 5) ? 1 : (($index == 5) ? 3 : (($index == 6) ? 4 : (($index == 7) ? 5 : 2))),
                'name' => $city, 'code' => strtoupper(substr($city, 0, 3)), 'status' => true
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['CompanySeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class CompanySeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
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
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['BranchSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class BranchSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
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
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['DepartmentSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class DepartmentSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('departments')->truncate();
        $deps = ['Sales', 'Purchase', 'Inventory', 'Accounts', 'HR', 'IT', 'Marketing', 'Customer Support', 'Logistics', 'Management'];
        foreach($deps as $index => $d) {
            DB::table('departments')->insert([
                'branch_id' => 1,
                'name' => $d,
                'code' => strtoupper(substr($d, 0, 3)) . $index,
                'status' => true,
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['DesignationSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class DesignationSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('designations')->truncate();
        $desigs = ['General Manager', 'Branch Manager', 'Sales Executive', 'Cashier', 'Store Keeper', 'Accountant', 'HR Manager', 'Purchase Officer', 'Marketing Head', 'Customer Care Executive'];
        foreach($desigs as $index => $d) {
            DB::table('designations')->insert([
                'name' => $d,
                'code' => strtoupper(substr(str_replace(' ', '', $d), 0, 4)) . $index,
                'status' => true,
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

for name, content in seeders.items():
    with open(f"database/seeders/{name}", "w", encoding="utf-8") as f:
        f.write(content)

print("Seeders created.")
