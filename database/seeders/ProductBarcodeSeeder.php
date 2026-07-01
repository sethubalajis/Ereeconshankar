<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ProductBarcodeSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('product_barcodes')->truncate();
        
        $variants = DB::table('product_variants')->get();
        if($variants->isEmpty()) return;

        foreach($variants as $v) {
            DB::table('product_barcodes')->insert([
                'product_id' => $v->product_id,
                'product_variant_id' => $v->id,
                'barcode' => $v->barcode,
                'barcode_type' => 'EAN13'
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
