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
        Schema::create('gst_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_type'); // GSTR1, GSTR3B
            $table->date('period_start');
            $table->date('period_end');
            $table->date('filing_date')->nullable();
            $table->string('arn_number')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gst_returns');
    }
};
