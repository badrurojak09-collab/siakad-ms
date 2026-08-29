<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['curriculum_courses', 'curriculum_templates'] as $tableName) {
            if (! Schema::hasColumn($tableName, 'tenant_id')) {
                Schema::table($tableName, function (Blueprint $table): void {
                    $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
                    $table->index('tenant_id');
                });
            }
        }

        DB::table('curriculum_courses')->whereNull('tenant_id')->orderBy('id')->eachById(function ($row): void {
            $tenantId = DB::table('curriculums')->where('id', $row->curriculum_id)->value('tenant_id');
            if ($tenantId !== null) DB::table('curriculum_courses')->where('id', $row->id)->update(['tenant_id' => $tenantId]);
        });

        DB::table('curriculum_templates')->whereNull('tenant_id')->orderBy('id')->eachById(function ($row): void {
            $tenantId = DB::table('curriculums')->where('id', $row->curriculum_id)->value('tenant_id');
            if ($tenantId !== null) DB::table('curriculum_templates')->where('id', $row->id)->update(['tenant_id' => $tenantId]);
        });
    }

    public function down(): void
    {
        foreach (['curriculum_courses', 'curriculum_templates'] as $tableName) {
            if (Schema::hasColumn($tableName, 'tenant_id')) {
                Schema::table($tableName, function (Blueprint $table): void {
                    $table->dropIndex(['tenant_id']);
                    $table->dropColumn('tenant_id');
                });
            }
        }
    }
};
