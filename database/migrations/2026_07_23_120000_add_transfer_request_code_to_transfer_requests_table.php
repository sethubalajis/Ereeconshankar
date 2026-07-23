<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transfer_requests', function (Blueprint $table) {
            $table->string('transfer_request_code')->nullable()->unique()->after('id');
        });

        DB::table('transfer_requests')
            ->select(['id', 'request_date'])
            ->orderBy('id')
            ->get()
            ->each(function ($request): void {
                $datePart = $request->request_date
                    ? date('Ymd', strtotime((string) $request->request_date))
                    : date('Ymd');

                DB::table('transfer_requests')
                    ->where('id', $request->id)
                    ->update([
                        'transfer_request_code' => sprintf('TR-%s-%06d', $datePart, $request->id),
                    ]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transfer_requests', function (Blueprint $table) {
            $table->dropUnique(['transfer_request_code']);
            $table->dropColumn('transfer_request_code');
        });
    }
};
