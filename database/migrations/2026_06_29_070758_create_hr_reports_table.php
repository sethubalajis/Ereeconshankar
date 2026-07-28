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
        Schema::create('hr_reports', function (Blueprint $table) {
            $table->id();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->date('report_month')->nullable();
            $table->integer('total_employees')->default(0);
            $table->integer('new_hires')->default(0);
            $table->integer('exits')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_reports');
    }
};
