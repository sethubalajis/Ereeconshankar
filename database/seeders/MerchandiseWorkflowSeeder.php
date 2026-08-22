<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Collection;
use App\Models\Season;
use App\Models\ProductLine;
use App\Models\Style;
use App\Models\Design;
use App\Models\Color;
use App\Models\Size;
use App\Models\FabricType;
use App\Models\PatternType;
use App\Models\MerchandisePlanning;
use App\Models\MerchandisePlanningItem;
use App\Models\FinancialYear;

class MerchandiseWorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            // 1. Create Classifiers
            $category = ProductCategory::firstOrCreate(['name' => 'Men\'s Wear']);
            $subCategory = ProductSubCategory::firstOrCreate(['name' => 'Shirts', 'product_category_id' => $category->id]);
            $brand = Brand::firstOrCreate(['name' => 'ABC Fashion']);

            // 2. Merchandise Metadata
            $collection = Collection::firstOrCreate(['name' => 'Summer 2026 Men\'s Collection'], ['code' => 'S26-M']);
            $season = Season::firstOrCreate(['name' => 'Summer 2026'], ['code' => 'S26']);
            $productLine = ProductLine::firstOrCreate(['name' => 'Premium Formal Wear'], ['code' => 'PL-PFW']);
            $style = Style::firstOrCreate(['name' => 'Slim Fit'], ['code' => 'SF']);
            $design = Design::firstOrCreate(['name' => 'DF-2026-001'], ['code' => 'D-001']);
            
            $fabricType = FabricType::firstOrCreate(['name' => 'Cotton'], ['code' => 'COT']);
            $patternType = PatternType::firstOrCreate(['name' => 'Solid'], ['code' => 'SOL']);
            
            $colors = [
                'Blue' => Color::firstOrCreate(['name' => 'Blue'], ['code' => 'BLU']),
                'White' => Color::firstOrCreate(['name' => 'White'], ['code' => 'WHT']),
            ];
            
            $sizes = [
                'M' => Size::firstOrCreate(['name' => 'M'], ['code' => 'M']),
                'L' => Size::firstOrCreate(['name' => 'L'], ['code' => 'L']),
                'XL' => Size::firstOrCreate(['name' => 'XL'], ['code' => 'XL']),
            ];

            // 3. Create Product Master
            $product = Product::firstOrCreate(
                ['name' => 'Men\'s Cotton Formal Shirt'],
                [
                    'code' => 'PRD-S26M01',
                    'brand_id' => $brand->id,
                    'product_category_id' => $category->id,
                    'product_sub_category_id' => $subCategory->id,
                    'is_active' => true,
                    'base_price' => 1500.00,
                ]
            );

            // 4. Create Product Variants
            $variants = [];
            foreach ($colors as $colorName => $colorModel) {
                foreach ($sizes as $sizeName => $sizeModel) {
                    $variant = ProductVariant::firstOrCreate([
                        'product_id' => $product->id,
                        'color_id' => $colorModel->id,
                        'size_id' => $sizeModel->id,
                    ], [
                        'sku' => "SKU-S26-{$colorName}-{$sizeName}",
                    ]);
                    
                    $variants[] = [
                        'variant' => $variant,
                        'color' => $colorModel,
                        'size' => $sizeModel,
                    ];
                }
            }

            // 5. Create Merchandise Planning
            $financialYear = FinancialYear::first(); // Assuming at least one exists
            
            $planning = MerchandisePlanning::create([
                'financial_year_id' => $financialYear->id ?? 1,
                'season_id' => $season->id,
                'target_sales' => 500000.00,
                'planned_budget' => 200000.00,
            ]);

            // 6. Create Merchandise Planning Items (Breakdown)
            $allocations = ['M' => 150, 'L' => 200, 'XL' => 150];

            foreach ($variants as $v) {
                $qty = $allocations[$v['size']->name];
                
                MerchandisePlanningItem::create([
                    'merchandise_planning_id' => $planning->id,
                    'product_id' => $product->id,
                    'product_variant_id' => $v['variant']->id,
                    'style_id' => $style->id,
                    'design_id' => $design->id,
                    'color_id' => $v['color']->id,
                    'size_id' => $v['size']->id,
                    'fabric_type_id' => $fabricType->id,
                    'pattern_type_id' => $patternType->id,
                    'planned_quantity' => $qty,
                    'allocated_quantity' => 0,
                    'remaining_quantity' => $qty,
                ]);
            }

            DB::commit();
            echo "Successfully seeded Merchandise Workflow data (Product + Planning + Items).\n";
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
