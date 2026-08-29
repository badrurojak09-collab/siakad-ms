<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('academic_transcripts', function (Blueprint $table): void {
            $table->string('signature_algorithm', 32)->nullable()->after('finalized_by');
            $table->string('signature_hash', 128)->nullable()->after('signature_algorithm');
            $table->unsignedBigInteger('signed_by')->nullable()->after('signature_hash');
            $table->timestamp('signed_at')->nullable()->after('signed_by');
            $table->string('signer_name')->nullable()->after('signed_at');
            $table->string('signer_title')->nullable()->after('signer_name');
            $table->index(['tenant_id', 'signature_hash']);
        });
    }

    public function down(): void
    {
        Schema::table('academic_transcripts', function (Blueprint $table): void {
            $table->dropIndex(['academic_transcripts_tenant_id_signature_hash_index']);
            $table->dropColumn(['signature_algorithm', 'signature_hash', 'signed_by', 'signed_at', 'signer_name', 'signer_title']);
        });
    }
};
