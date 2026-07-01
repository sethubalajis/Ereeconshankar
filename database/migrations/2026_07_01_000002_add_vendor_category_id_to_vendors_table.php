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
                $table->unsignedBigInteger('vendor_category_id')->nullable()->after('id');
                $table->index('vendor_category_id');
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
                $table->dropIndex(['vendor_category_id']);
                $table->dropColumn('vendor_category_id');
            });
        }
    }
};
