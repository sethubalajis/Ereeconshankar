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
            $table->decimal('incentives', 15, 2)->default(0)->after('bonuses');
            $table->decimal('reimbursement', 15, 2)->default(0)->after('incentives');
            $table->decimal('professional_taxes', 15, 2)->default(0)->after('esi');
            $table->decimal('tds_records', 15, 2)->default(0)->after('professional_taxes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payroll_processings', function (Blueprint $table) {
            $table->dropColumn(['incentives', 'reimbursement', 'professional_taxes', 'tds_records']);
        });
    }
};
