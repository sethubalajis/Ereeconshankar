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
        Schema::create('tcs_records', function (Blueprint $table) {
            $table->id();
            $table->string('section_code');
            $table->decimal('tcs_percentage', 5, 2)->default(0);
            $table->unsignedBigInteger('collectee_id');
            $table->string('collectee_type');
            $table->decimal('amount', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tcs_records');
    }
};
