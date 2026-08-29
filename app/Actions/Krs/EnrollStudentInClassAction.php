<?php
namespace App\Actions\Krs;
use App\Models\{KrsHeader,KrsDetail,CourseClass};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class EnrollStudentInClassAction {
 public function execute(KrsHeader $krs, CourseClass $class): KrsDetail {
  return DB::transaction(function() use($krs,$class){
   if($krs->status !== 'draft' && $krs->status !== 'revision_required') throw ValidationException::withMessages(['status'=>'KRS tidak dapat diubah pada status ini.']);
   if(!$class->semester || !$class->semester->is_active) throw ValidationException::withMessages(['class'=>'Kelas bukan bagian dari semester aktif.']);
   if($class->status !== 'planned' && $class->status !== 'active') throw ValidationException::withMessages(['class'=>'Kelas tidak menerima pendaftaran.']);
   if($krs->details()->where('course_class_id',$class->getKey())->where('status','registered')->exists()) throw ValidationException::withMessages(['class'=>'Kelas sudah ada di KRS.']);
   $registered=$class->krsDetails()->where('status','registered')->count(); if($registered >= $class->capacity) throw ValidationException::withMessages(['class'=>'Kapasitas kelas sudah penuh.']);
   $course=$class->course; $current=(int)$krs->details()->where('status','registered')->with('courseClass.course')->get()->sum(fn($d)=>(int)($d->courseClass?->course?->credits ?? 0));
   if($current + (int)$course->credits > 24) throw ValidationException::withMessages(['total_credits'=>'Total KRS tidak boleh melebihi 24 SKS.']);
   app(ValidateKrsEligibilityAction::class)->validateEnrollment($krs, $class);
   return $krs->details()->create(['tenant_id' => $krs->tenant_id, 'course_class_id' => $class->getKey(), 'status' => 'registered', 'registered_at' => now()]);
  });
 }
}
