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
        Schema::create('purchase_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->date('report_date')->nullable();
            $table->decimal('total_purchases', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_reports');
    }
};
