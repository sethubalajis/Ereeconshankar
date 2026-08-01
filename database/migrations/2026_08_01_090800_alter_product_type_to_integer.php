<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, add a temporary column
        Schema::table('products', function (Blueprint $table) {
            $table->tinyInteger('product_type_int')->default(1);
        });

        // Map data
        DB::table('products')->where('product_type', 'Sellable')->update(['product_type_int' => 1]);
        DB::table('products')->where('product_type', 'Non-Sellable Inventory')->update(['product_type_int' => 2]);
        DB::table('products')->where('product_type', 'Asset')->update(['product_type_int' => 3]);

        // Drop old column
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('product_type');
        });

        // Rename temp column
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('product_type_int', 'product_type');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_type_str')->default('Sellable');
        });

        DB::table('products')->where('product_type', 1)->update(['product_type_str' => 'Sellable']);
        DB::table('products')->where('product_type', 2)->update(['product_type_str' => 'Non-Sellable Inventory']);
        DB::table('products')->where('product_type', 3)->update(['product_type_str' => 'Asset']);

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('product_type');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('product_type_str', 'product_type');
        });
    }
};
