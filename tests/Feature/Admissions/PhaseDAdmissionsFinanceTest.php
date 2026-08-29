<?php

namespace Tests\Feature\Admissions;

use App\Actions\Admissions\{GenerateAdmissionBillAction, RecordAdmissionPaymentAction, VerifyAdmissionDocumentAction, FinalizeApplicantSelectionAction};
use App\Models\{AdmissionBill, AdmissionDocument, AdmissionPeriod, Applicant, FeeType, Tenant, User};
use App\Services\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseDAdmissionsFinanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenant = Tenant::create(['code' => 'D', 'name' => 'Phase D', 'status' => 'active']);
        $this->actor = User::create(['name' => 'PMB Actor', 'email' => 'pmb-actor@example.test', 'password' => 'password']);
        app(TenantContext::class)->set($this->tenant);
        $this->period = AdmissionPeriod::create(['tenant_id' => $this->tenant->id, 'code' => 'PMB-2026', 'name' => 'PMB 2026', 'registration_start' => now()->subDay(), 'registration_end' => now()->addMonth(), 'status' => 'open']);
        $this->applicant = Applicant::create(['tenant_id' => $this->tenant->id, 'admission_period_id' => $this->period->id, 'registration_number' => 'REG-001', 'full_name' => 'Applicant D', 'email' => 'd@example.test', 'status' => 'draft']);
    }

    public function test_registration_bill_can_be_generated_and_paid(): void
    {
        $fee = FeeType::create(['tenant_id' => $this->tenant->id, 'code' => 'FORM', 'name' => 'Formulir PMB', 'default_amount' => 250000, 'is_active' => true]);
        $bill = app(GenerateAdmissionBillAction::class)->execute($this->applicant, $fee);
        app(RecordAdmissionPaymentAction::class)->execute($bill, 250000.0, 'transfer', 'REF-001');
        $this->assertEquals('paid', $bill->refresh()->status);
        $this->assertEquals(250000.0, (float) $bill->paid_amount);
    }

    public function test_document_verification_and_selection_finalization_workflow(): void
    {
        $document = AdmissionDocument::create(['tenant_id' => $this->tenant->id, 'applicant_id' => $this->applicant->id, 'document_type' => 'identity', 'file_url' => 'https://drive.google.com/file/d/test', 'verification_status' => 'pending']);
        app(VerifyAdmissionDocumentAction::class)->execute($document, true, $this->actor->id);
        $this->applicant->update(['status' => 'submitted']);
        $selected = app(FinalizeApplicantSelectionAction::class)->execute($this->applicant, true, $this->actor->id);
        $this->assertEquals('selection_passed', $selected->status);
    }
}
