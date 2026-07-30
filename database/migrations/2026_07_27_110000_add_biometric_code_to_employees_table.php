<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('employees', 'biometric_code')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->string('biometric_code')->nullable()->unique()->after('employee_code');
            });
        }
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropUnique(['biometric_code']);
            $table->dropColumn('biometric_code');
        });
    }
};
