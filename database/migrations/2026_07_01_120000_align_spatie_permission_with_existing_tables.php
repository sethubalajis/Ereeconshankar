<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('roles') && ! Schema::hasColumn('roles', 'guard_name')) {
            Schema::table('roles', function (Blueprint $table): void {
                $table->string('guard_name')->default('web')->after('name');
            });
        }

        if (Schema::hasTable('permissions') && ! Schema::hasColumn('permissions', 'guard_name')) {
            Schema::table('permissions', function (Blueprint $table): void {
                $table->string('guard_name')->default('web')->after('name');
            });
        }

        if (Schema::hasTable('user_roles') && ! Schema::hasColumn('user_roles', 'model_type')) {
            Schema::table('user_roles', function (Blueprint $table): void {
                $table->string('model_type')->nullable()->after('role_id');
            });

            DB::table('user_roles')->update([
                'model_type' => 'App\\Models\\User',
            ]);

            Schema::table('user_roles', function (Blueprint $table): void {
                $table->index(['user_id', 'model_type'], 'user_roles_user_model_index');
                $table->unique(['role_id', 'user_id', 'model_type'], 'user_roles_unique_triplet');
            });
        }

        if (! Schema::hasTable('model_has_permissions')) {
            Schema::create('model_has_permissions', function (Blueprint $table): void {
                $table->unsignedBigInteger('permission_id');
                $table->string('model_type');
                $table->unsignedBigInteger('user_id');

                $table->index(['user_id', 'model_type'], 'model_has_permissions_user_model_index');
                $table->foreign('permission_id')
                    ->references('id')
                    ->on('permissions')
                    ->onDelete('cascade');

                $table->primary(['permission_id', 'user_id', 'model_type'], 'model_has_permissions_primary');
            });
        }

        if (Schema::hasTable('role_permissions')) {
            try {
                Schema::table('role_permissions', function (Blueprint $table): void {
                    $table->unique(['role_id', 'permission_id'], 'role_permissions_unique_pair');
                });
            } catch (Throwable $e) {
                // Ignore if unique index already exists or duplicates prevent creation.
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('role_permissions')) {
            try {
                Schema::table('role_permissions', function (Blueprint $table): void {
                    $table->dropUnique('role_permissions_unique_pair');
                });
            } catch (Throwable $e) {
                // Ignore rollback issues for optional index.
            }
        }

        if (Schema::hasTable('model_has_permissions')) {
            Schema::dropIfExists('model_has_permissions');
        }

        if (Schema::hasTable('user_roles') && Schema::hasColumn('user_roles', 'model_type')) {
            try {
                Schema::table('user_roles', function (Blueprint $table): void {
                    $table->dropUnique('user_roles_unique_triplet');
                    $table->dropIndex('user_roles_user_model_index');
                });
            } catch (Throwable $e) {
                // Ignore rollback issues for optional indexes.
            }

            Schema::table('user_roles', function (Blueprint $table): void {
                $table->dropColumn('model_type');
            });
        }

        if (Schema::hasTable('permissions') && Schema::hasColumn('permissions', 'guard_name')) {
            Schema::table('permissions', function (Blueprint $table): void {
                $table->dropColumn('guard_name');
            });
        }

        if (Schema::hasTable('roles') && Schema::hasColumn('roles', 'guard_name')) {
            Schema::table('roles', function (Blueprint $table): void {
                $table->dropColumn('guard_name');
            });
        }
    }
};
