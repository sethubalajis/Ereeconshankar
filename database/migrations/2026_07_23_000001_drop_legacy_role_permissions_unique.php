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
        if (! Schema::hasTable('role_permissions')) {
            return;
        }

        try {
            Schema::table('role_permissions', function (Blueprint $table): void {
                $table->dropUnique('role_permissions_unique_pair');
            });
        } catch (Throwable $e) {
            // The legacy unique index may already be absent.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('role_permissions')) {
            return;
        }

        try {
            Schema::table('role_permissions', function (Blueprint $table): void {
                $table->unique(['role_id', 'permission_id'], 'role_permissions_unique_pair');
            });
        } catch (Throwable $e) {
            // Ignore if the index cannot be recreated.
        }
    }
};
