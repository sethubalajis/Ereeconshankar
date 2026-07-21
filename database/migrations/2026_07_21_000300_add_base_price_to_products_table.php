<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('products', 'base_price')) {
            Schema::table('products', function (Blueprint $table): void {
                $table->decimal('base_price', 15, 2)->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('products', 'base_price')) {
            Schema::table('products', function (Blueprint $table): void {
                $table->dropColumn('base_price');
            });
        }
    }
};
