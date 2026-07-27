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
        Schema::table('sales_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_reports', 'from_date')) {
                $table->date('from_date')->nullable()->after('branch_id');
            }
            if (!Schema::hasColumn('sales_reports', 'to_date')) {
                $table->date('to_date')->nullable()->after('from_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_reports', function (Blueprint $table) {
            $table->dropColumn(['from_date', 'to_date']);
        });
    }
};
