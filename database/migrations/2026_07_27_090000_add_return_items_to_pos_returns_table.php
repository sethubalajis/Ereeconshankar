<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pos_returns', 'return_items')) {
            Schema::table('pos_returns', function (Blueprint $table) {
                $table->json('return_items')->nullable()->after('return_amount');
            });
        }
    }

    public function down(): void
    {
        Schema::table('pos_returns', function (Blueprint $table) {
            $table->dropColumn('return_items');
        });
    }
};
