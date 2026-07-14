<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('model_has_roles') && Schema::hasColumn('model_has_roles', 'model_id') && ! Schema::hasColumn('model_has_roles', 'user_id')) {
            Schema::table('model_has_roles', function (Blueprint $table): void {
                $table->renameColumn('model_id', 'user_id');
            });
        }

        if (Schema::hasTable('model_has_permissions') && Schema::hasColumn('model_has_permissions', 'model_id') && ! Schema::hasColumn('model_has_permissions', 'user_id')) {
            Schema::table('model_has_permissions', function (Blueprint $table): void {
                $table->renameColumn('model_id', 'user_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('model_has_roles') && Schema::hasColumn('model_has_roles', 'user_id') && ! Schema::hasColumn('model_has_roles', 'model_id')) {
            Schema::table('model_has_roles', function (Blueprint $table): void {
                $table->renameColumn('user_id', 'model_id');
            });
        }

        if (Schema::hasTable('model_has_permissions') && Schema::hasColumn('model_has_permissions', 'user_id') && ! Schema::hasColumn('model_has_permissions', 'model_id')) {
            Schema::table('model_has_permissions', function (Blueprint $table): void {
                $table->renameColumn('user_id', 'model_id');
            });
        }
    }
};