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
        Schema::table('stock_reservations', function (Blueprint $table) {
            $table->string('reservation_number')->nullable()->unique()->after('id');
            $table->foreignId('company_id')->nullable()->constrained('companies')->after('reservation_number');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->after('branch_id');
            $table->string('reservation_type')->nullable()->after('quantity');
            $table->string('reference_document')->nullable()->after('reservation_type');
            $table->dateTime('reservation_date')->nullable()->after('reference_document');
            $table->dateTime('expiry_date')->nullable()->after('reservation_date');
            $table->foreignId('created_by')->nullable()->constrained('users')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_reservations', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['warehouse_id']);
            $table->dropForeign(['created_by']);
            $table->dropColumn([
                'reservation_number',
                'company_id',
                'warehouse_id',
                'reservation_type',
                'reference_document',
                'reservation_date',
                'expiry_date',
                'created_by'
            ]);
        });
    }
};
