<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('roles')) {
            return;
        }

        Schema::table('roles', function (Blueprint $table): void {
            if (! Schema::hasColumn('roles', 'description')) {
                $table->text('description')->nullable()->after('name');
            }

            if (! Schema::hasColumn('roles', 'status')) {
                $table->boolean('status')->default(true)->after('description');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('roles')) {
            return;
        }

        Schema::table('roles', function (Blueprint $table): void {
            if (Schema::hasColumn('roles', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('roles', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
