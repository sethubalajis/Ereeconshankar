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
        Schema::table('payroll_processings', function (Blueprint $table) {
            $table->decimal('basic_pay', 15, 2)->default(0)->after('net_pay');
            $table->decimal('hra', 15, 2)->default(0)->after('basic_pay');
            $table->decimal('special_allowance', 15, 2)->default(0)->after('hra');
            $table->decimal('pf', 15, 2)->default(0)->after('special_allowance');
            $table->decimal('extra_deductions', 15, 2)->default(0)->after('pf');
            $table->decimal('bonuses', 15, 2)->default(0)->after('extra_deductions');
            $table->decimal('loss_of_pay', 15, 2)->default(0)->after('bonuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payroll_processings', function (Blueprint $table) {
            $table->dropColumn([
                'basic_pay',
                'hra',
                'special_allowance',
                'pf',
                'extra_deductions',
                'bonuses',
                'loss_of_pay',
            ]);
        });
    }
};
