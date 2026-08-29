<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('course_prerequisites', 'tenant_id')) {
            Schema::table('course_prerequisites', function (Blueprint $table): void {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
                $table->index('tenant_id');
            });
        }

        DB::table('course_prerequisites')->whereNull('tenant_id')->orderBy('id')->eachById(function ($prerequisite): void {
            $tenantId = DB::table('courses')->where('id', $prerequisite->course_id)->value('tenant_id');
            if ($tenantId !== null) {
                DB::table('course_prerequisites')->where('id', $prerequisite->id)->update(['tenant_id' => $tenantId]);
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('course_prerequisites', 'tenant_id')) {
            Schema::table('course_prerequisites', function (Blueprint $table): void {
                $table->dropIndex(['tenant_id']);
                $table->dropColumn('tenant_id');
            });
        }
    }
};
