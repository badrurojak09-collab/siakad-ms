<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('thesis_examiners', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->unsignedBigInteger('thesis_id');
            $table->unsignedBigInteger('lecturer_id');
            $table->string('role', 30)->default('examiner');
            $table->string('status', 20)->default('assigned');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['thesis_id', 'lecturer_id', 'role']);
            $table->index(['tenant_id', 'thesis_id']);
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('thesis_id')->references('id')->on('theses')->cascadeOnDelete();
            $table->foreign('lecturer_id')->references('id')->on('lecturers')->restrictOnDelete();
        });

        Schema::create('thesis_revisions', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->unsignedBigInteger('thesis_id');
            $table->unsignedInteger('revision_no');
            $table->text('description');
            $table->string('status', 20)->default('submitted');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['thesis_id', 'revision_no']);
            $table->index(['tenant_id', 'thesis_id', 'status']);
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('thesis_id')->references('id')->on('theses')->cascadeOnDelete();
        });

        Schema::create('graduation_documents', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->unsignedBigInteger('graduation_id');
            $table->string('document_type', 40);
            $table->string('file_url')->nullable();
            $table->string('verification_hash', 128)->nullable();
            $table->unsignedBigInteger('generated_by')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['graduation_id', 'document_type']);
            $table->index(['tenant_id', 'verification_hash']);
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('graduation_id')->references('id')->on('graduations')->cascadeOnDelete();
        });

        Schema::table('pddikti_sync_logs', function (Blueprint $table): void {
            $table->string('idempotency_key', 128)->nullable()->after('operation');
            $table->string('adapter', 80)->nullable()->after('idempotency_key');
            $table->timestamp('last_attempt_at')->nullable()->after('synced_at');
            $table->string('error_class')->nullable()->after('response_message');
            $table->unique(['tenant_id', 'idempotency_key']);
            $table->index(['tenant_id', 'entity_type', 'entity_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('pddikti_sync_logs', function (Blueprint $table): void {
            $table->dropUnique('pddikti_sync_logs_tenant_id_idempotency_key_unique');
            $table->dropIndex('pddikti_sync_logs_tenant_id_entity_type_entity_id_status_index');
            $table->dropColumn(['idempotency_key', 'adapter', 'last_attempt_at', 'error_class']);
        });
        Schema::dropIfExists('graduation_documents');
        Schema::dropIfExists('thesis_revisions');
        Schema::dropIfExists('thesis_examiners');
    }
};
