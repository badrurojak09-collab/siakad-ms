<?php
namespace App\Actions\Admissions;
use App\Models\Applicant; use Illuminate\Validation\ValidationException;
class FinalizeApplicantSelectionAction { public function execute(Applicant $applicant,bool $passed,int $actorId): Applicant { if($applicant->status!=='submitted') throw ValidationException::withMessages(['status'=>'Pendaftar harus berstatus submitted.']); if($applicant->documents()->where('verification_status','!=','verified')->exists()) throw ValidationException::withMessages(['documents'=>'Semua dokumen wajib terverifikasi.']); $applicant->update(['status'=>$passed?'selection_passed':'rejected']); activity('pmb')->causedBy($actorId)->performedOn($applicant)->log($passed?'selection.passed':'selection.rejected');return $applicant->refresh(); } }
