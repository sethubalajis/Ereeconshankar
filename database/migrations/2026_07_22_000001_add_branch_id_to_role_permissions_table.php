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
        Schema::table('role_permissions', function (Blueprint $table): void {
            if (! Schema::hasColumn('role_permissions', 'branch_id')) {
                $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            }
        });

        Schema::table('role_permissions', function (Blueprint $table): void {
            $table->unique(['role_id', 'branch_id', 'permission_id'], 'role_permissions_branch_permission_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_permissions', function (Blueprint $table): void {
            $table->dropUnique('role_permissions_branch_permission_unique');
            if (Schema::hasColumn('role_permissions', 'branch_id')) {
                $table->dropConstrainedForeignId('branch_id');
            }
        });
    }
};
