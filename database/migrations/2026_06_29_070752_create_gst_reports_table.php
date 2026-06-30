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
        Schema::create('gst_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_type'); // GSTR1, GSTR3B
            $table->date('period_start');
            $table->date('period_end');
            $table->text('summary_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gst_reports');
    }
};
