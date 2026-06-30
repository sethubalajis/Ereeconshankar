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
        Schema::create('tds_records', function (Blueprint $table) {
            $table->id();
            $table->string('section_code');
            $table->decimal('tds_percentage', 5, 2)->default(0);
            $table->unsignedBigInteger('deductee_id');
            $table->string('deductee_type');
            $table->decimal('amount', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tds_records');
    }
};
