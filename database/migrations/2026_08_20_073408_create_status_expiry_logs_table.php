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
        Schema::create('status_expiry_logs', function (Blueprint $table) {
            $table->id();
            $table->string('model_type')->index();
            $table->unsignedBigInteger('model_id')->index();
            $table->string('previous_status')->nullable();
            $table->string('new_status');
            $table->timestamp('deadline_date')->nullable();
            $table->timestamp('status_changed_at')->useCurrent();
            $table->text('reason')->nullable();
            $table->string('processed_by')->default('SYSTEM');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_expiry_logs');
    }
};
