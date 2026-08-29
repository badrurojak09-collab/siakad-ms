<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['krs_details', 'krs_logs'] as $table) {
            if (! Schema::hasColumn($table, 'tenant_id')) {
                Schema::table($table, function (Blueprint $blueprint): void {
                    $blueprint->unsignedBigInteger('tenant_id')->nullable()->index()->after('id');
                });
            }
        }

        DB::table('krs_details')->whereNull('tenant_id')->orderBy('id')->chunkById(500, function ($rows): void {
            foreach ($rows as $row) {
                $tenantId = DB::table('krs_headers')->where('id', $row->krs_header_id)->value('tenant_id');
                if ($tenantId !== null) DB::table('krs_details')->where('id', $row->id)->update(['tenant_id' => $tenantId]);
            }
        });

        DB::table('krs_logs')->whereNull('tenant_id')->orderBy('id')->chunkById(500, function ($rows): void {
            foreach ($rows as $row) {
                $tenantId = DB::table('krs_headers')->where('id', $row->krs_header_id)->value('tenant_id');
                if ($tenantId !== null) DB::table('krs_logs')->where('id', $row->id)->update(['tenant_id' => $tenantId]);
            }
        });
    }

    public function down(): void
    {
        foreach (['krs_details', 'krs_logs'] as $table) {
            if (Schema::hasColumn($table, 'tenant_id')) Schema::table($table, fn (Blueprint $blueprint) => $blueprint->dropColumn('tenant_id'));
        }
    }
};
