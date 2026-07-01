<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('vendor_categories')->truncate();

        $now = now();

        DB::table('vendor_categories')->insert([
            [
                'name' => 'Fabric Mills',
                'code' => 'VEN001',
                'description' => 'Suppliers of woven and knitted greige or finished fabrics.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Yarn Suppliers',
                'code' => 'VEN002',
                'description' => 'Cotton, viscose, polyester, and blended yarn vendors.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Dyeing and Processing Units',
                'code' => 'VEN003',
                'description' => 'Dyeing, bleaching, mercerizing, and finishing job work units.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Printing Units',
                'code' => 'VEN004',
                'description' => 'Screen, rotary, and digital textile print service vendors.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Trims and Accessories',
                'code' => 'VEN005',
                'description' => 'Buttons, zippers, labels, elastics, lace, and sewing accessories.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Embroidery and Value Addition',
                'code' => 'VEN006',
                'description' => 'Embroidery, sequins, beadwork, and ornamental finishing vendors.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Garment Job Workers',
                'code' => 'VEN007',
                'description' => 'Cutting, stitching, and finishing job work partners.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Packing Material Suppliers',
                'code' => 'VEN008',
                'description' => 'Cartons, polybags, tags, and transport-safe packaging materials.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Chemical and Auxiliary Suppliers',
                'code' => 'VEN009',
                'description' => 'Textile chemicals, enzymes, softeners, and process auxiliaries.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Logistics and Transport',
                'code' => 'VEN010',
                'description' => 'Inbound and outbound textile shipment and freight vendors.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
