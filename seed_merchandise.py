import os

seeders = {}

seeders['BrandSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class BrandSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('brands')->truncate();
        $brands = [
            ['name' => 'Levis', 'code' => 'LEV', 'desc' => 'Premium denim and casual wear.'],
            ['name' => 'Allen Solly', 'code' => 'ALS', 'desc' => 'Office wear and formals.'],
            ['name' => 'FabIndia', 'code' => 'FAB', 'desc' => 'Traditional and ethnic wear.'],
            ['name' => 'Puma', 'code' => 'PMA', 'desc' => 'Sports and activewear.'],
            ['name' => 'Biba', 'code' => 'BBA', 'desc' => 'Womens ethnic wear.'],
        ];
        foreach($brands as $b) {
            DB::table('brands')->insert([
                'name' => $b['name'], 'code' => $b['code'], 'description' => $b['desc'], 'status' => true
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['ProductCategorySeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class ProductCategorySeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_categories')->truncate();
        $categories = [
            ['name' => 'Mens Wear', 'code' => 'MENS'],
            ['name' => 'Womens Wear', 'code' => 'WMN'],
            ['name' => 'Kids Wear', 'code' => 'KID'],
            ['name' => 'Accessories', 'code' => 'ACC'],
            ['name' => 'Footwear', 'code' => 'FTW'],
        ];
        foreach($categories as $c) {
            DB::table('product_categories')->insert([
                'name' => $c['name'], 'code' => $c['code'], 'status' => true
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['ProductSubCategorySeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class ProductSubCategorySeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_sub_categories')->truncate();
        $subs = [
            ['cat_id' => 1, 'name' => 'Shirts', 'code' => 'M-SHR'],
            ['cat_id' => 1, 'name' => 'Trousers', 'code' => 'M-TRU'],
            ['cat_id' => 2, 'name' => 'Sarees', 'code' => 'W-SAR'],
            ['cat_id' => 2, 'name' => 'Kurtas', 'code' => 'W-KUR'],
            ['cat_id' => 3, 'name' => 'Boys T-Shirts', 'code' => 'K-TSH'],
        ];
        foreach($subs as $s) {
            DB::table('product_sub_categories')->insert([
                'product_category_id' => $s['cat_id'], 'name' => $s['name'], 'code' => $s['code'], 'status' => true
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['ColorSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class ColorSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('colors')->truncate();
        $colors = [
            ['name' => 'Red', 'code' => 'RED'],
            ['name' => 'Blue', 'code' => 'BLU'],
            ['name' => 'Green', 'code' => 'GRN'],
            ['name' => 'Black', 'code' => 'BLK'],
            ['name' => 'White', 'code' => 'WHT'],
            ['name' => 'Yellow', 'code' => 'YEL'],
            ['name' => 'Pink', 'code' => 'PNK'],
            ['name' => 'Purple', 'code' => 'PUR'],
            ['name' => 'Grey', 'code' => 'GRY'],
            ['name' => 'Navy', 'code' => 'NVY']
        ];
        foreach($colors as $c) {
            DB::table('colors')->insert([
                'name' => $c['name'], 'code' => $c['code'], 'status' => true
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['SizeSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class SizeSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sizes')->truncate();
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '28', '30', '32', '34'];
        foreach($sizes as $index => $s) {
            DB::table('sizes')->insert([
                'name' => $s, 'code' => $s, 'status' => true
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['FabricTypeSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class FabricTypeSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('fabric_types')->truncate();
        $fabrics = ['Cotton', 'Silk', 'Polyester', 'Linen', 'Wool', 'Denim', 'Rayon', 'Chiffon'];
        foreach($fabrics as $f) {
            DB::table('fabric_types')->insert([
                'name' => $f, 'code' => strtoupper(substr($f, 0, 3)), 'status' => true
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['PatternTypeSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class PatternTypeSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('pattern_types')->truncate();
        $patterns = ['Solid', 'Striped', 'Checked', 'Floral', 'Printed', 'Polka Dot', 'Geometric'];
        foreach($patterns as $p) {
            DB::table('pattern_types')->insert([
                'name' => $p, 'code' => strtoupper(substr($p, 0, 3)), 'status' => true
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['StyleSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class StyleSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('styles')->truncate();
        $styles = ['Casual', 'Formal', 'Party Wear', 'Ethnic', 'Sportswear', 'Lounge Wear'];
        foreach($styles as $s) {
            DB::table('styles')->insert([
                'name' => $s, 'code' => strtoupper(substr(str_replace(' ', '', $s), 0, 4)), 'status' => true
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

seeders['SeasonSeeder.php'] = """<?php
namespace Database\\Seeders;
use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
class SeasonSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('seasons')->truncate();
        $seasons = ['Summer 2026', 'Winter 2026', 'Autumn 2026', 'Spring 2027', 'Festive Collection'];
        foreach($seasons as $s) {
            DB::table('seasons')->insert([
                'name' => $s, 'code' => strtoupper(substr(str_replace(' ', '', $s), 0, 4)) . rand(10,99), 'status' => true
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
"""

for name, content in seeders.items():
    with open(f"database/seeders/{name}", "w", encoding="utf-8") as f:
        f.write(content)

print("Merchandise seeders created.")
