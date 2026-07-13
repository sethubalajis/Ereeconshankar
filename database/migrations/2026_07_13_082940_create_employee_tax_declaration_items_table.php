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
        Schema::create('employee_tax_declaration_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_tax_declaration_id');
            $table->foreign('employee_tax_declaration_id', 'etd_items_etd_id_fk')->references('id')->on('employee_tax_declarations')->onDelete('cascade');
            $table->foreignId('investment_type_id')->constrained('investment_types')->onDelete('cascade');
            $table->string('section')->nullable();
            $table->decimal('declared_amount', 15, 2)->default(0);
            $table->decimal('approved_amount', 15, 2)->nullable();
            $table->string('proof_document')->nullable();
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_tax_declaration_items');
    }
};
