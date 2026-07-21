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
        if (! Schema::hasColumn('vendors', 'vendor_category_id')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->foreignId('vendor_category_id')->nullable()->constrained()->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('vendors', 'vendor_category_id')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->dropColumn('vendor_category_id');
            });
        }
    }
};
