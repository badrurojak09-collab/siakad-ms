<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void {
 Schema::table('academic_transcripts',function(Blueprint $t){$t->timestamp('finalized_at')->nullable()->after('generated_at');$t->unsignedBigInteger('finalized_by')->nullable()->after('finalized_at');$t->index(['tenant_id','student_id','status']);});
} public function down(): void {Schema::table('academic_transcripts',function(Blueprint $t){$t->dropColumn(['finalized_at','finalized_by']);});} };
