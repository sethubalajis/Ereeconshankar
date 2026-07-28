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
        if (Schema::hasTable('hr_reports')) {
            Schema::table('hr_reports', function (Blueprint $table) {
                if (!Schema::hasColumn('hr_reports', 'from_date')) {
                    $table->date('from_date')->nullable();
                }
                if (!Schema::hasColumn('hr_reports', 'to_date')) {
                    $table->date('to_date')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('hr_reports')) {
            Schema::table('hr_reports', function (Blueprint $table) {
                if (Schema::hasColumn('hr_reports', 'from_date')) {
                    $table->dropColumn('from_date');
                }
                if (Schema::hasColumn('hr_reports', 'to_date')) {
                    $table->dropColumn('to_date');
                }
            });
        }
    }
};
