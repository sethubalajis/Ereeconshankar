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
        Schema::create('document_number_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('document_type');
            $table->string('prefix')->nullable();
            $table->string('suffix')->nullable();
            $table->boolean('include_financial_year')->default(false);
            $table->string('financial_year_format')->nullable();
            $table->string('separator')->nullable();
            $table->integer('number_padding')->default(6);
            $table->unsignedBigInteger('starting_number')->default(1);
            $table->string('reset_frequency')->default('yearly');
            $table->boolean('branch_wise')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['company_id', 'document_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_number_configurations');
    }
};
