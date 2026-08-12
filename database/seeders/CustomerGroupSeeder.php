<?php

namespace Database\Seeders;

use App\Models\CustomerGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CustomerGroupSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('customer_groups')->truncate();
        Schema::enableForeignKeyConstraints();
        
        $groups = [
            ['name' => 'Retail', 'code' => 'CG-RETL', 'discount_percentage' => 0.00, 'status' => true],
            ['name' => 'Wholesale', 'code' => 'CG-WHSL', 'discount_percentage' => 15.00, 'status' => true],
            ['name' => 'VIP', 'code' => 'CG-VIP', 'discount_percentage' => 10.00, 'status' => true],
            ['name' => 'Corporate', 'code' => 'CG-CORP', 'discount_percentage' => 12.00, 'status' => true],
            ['name' => 'Dealer', 'code' => 'CG-DLR', 'discount_percentage' => 20.00, 'status' => true],
        ];
        
        foreach ($groups as $group) {
            CustomerGroup::create($group);
        }
    }
}
