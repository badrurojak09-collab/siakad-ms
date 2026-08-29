<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('subscription_plan', 50)->default('trial')->after('status');
            $table->date('subscription_expiry')->nullable()->after('subscription_plan');
            $table->unsignedInteger('max_students')->default(0)->after('subscription_expiry');
            $table->unsignedInteger('max_lecturers')->default(0)->after('max_students');
            $table->unsignedBigInteger('created_by')->nullable()->after('max_lecturers');
            $table->unique('code', 'tenants_code_unique');
            $table->unique('domain', 'tenants_domain_unique');
            $table->index('status', 'tenants_status_index');
        });
        Schema::create('tenant_user', function (Blueprint $table) {
            $table->id(); $table->foreignId('tenant_id')->constrained()->cascadeOnDelete(); $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_active')->default(true); $table->timestamp('joined_at')->nullable(); $table->timestamps();
            $table->unique(['tenant_id', 'user_id']); $table->index(['user_id', 'is_active']);
        });
    }
    public function down(): void { Schema::dropIfExists('tenant_user'); Schema::table('tenants', function (Blueprint $table) { $table->dropUnique('tenants_code_unique'); $table->dropUnique('tenants_domain_unique'); $table->dropIndex('tenants_status_index'); $table->dropColumn(['subscription_plan','subscription_expiry','max_students','max_lecturers','created_by']); }); }
};
