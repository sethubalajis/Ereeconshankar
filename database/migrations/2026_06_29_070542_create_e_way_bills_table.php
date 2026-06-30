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
        Schema::create('e_way_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_invoice_id')->constrained('tax_invoices');
            $table->string('eway_bill_no')->unique();
            $table->date('valid_until')->nullable();
            $table->string('transporter_id')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_way_bills');
    }
};
