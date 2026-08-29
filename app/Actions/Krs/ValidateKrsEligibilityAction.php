<?php

namespace App\Actions\Krs;

use App\Models\{KrsHeader, CourseClass, Student, Semester, StudentGrade};
use Illuminate\Validation\ValidationException;

class ValidateKrsEligibilityAction
{
    public function execute(Student $student, Semester $semester): array
    {
        if (!$semester->is_active) throw ValidationException::withMessages(['semester' => 'Semester tidak aktif.']);
        
        $now = now();
        if ($semester->krs_start_date && $now->lt($semester->krs_start_date)) throw ValidationException::withMessages(['period' => 'Periode KRS belum dibuka.']);
        if ($semester->krs_end_date && $now->gt($semester->krs_end_date)) throw ValidationException::withMessages(['period' => 'Periode KRS sudah ditutup.']);

        $gpa = $this->calculateGpa($student);
        $maxSks = $this->getMaxSks($gpa);

        return ['gpa' => $gpa, 'max_sks' => $maxSks];
    }

    public function validateEnrollment(KrsHeader $header, CourseClass $candidate): void
    {
        $student = Student::findOrFail($header->student_id);
        $candidate->loadMissing(['course.prerequisites', 'schedules']);
        
        $selected = $header->details()->with('courseClass.course.prerequisites', 'courseClass.schedules')->get();
        
        if ($selected->contains(fn($d) => (int)$d->courseClass?->course_id === (int)$candidate->course_id)) {
            throw ValidationException::withMessages(['course' => 'Mata kuliah yang sama sudah dipilih.']);
        }

        $passedCourseIds = StudentGrade::where('student_id', $student->id)
            ->where('status', 'published')
            ->whereIn('grade_letter', ['A', 'B', 'C'])
            ->join('course_classes', 'student_grades.course_class_id', '=', 'course_classes.id')
            ->pluck('course_classes.course_id')
            ->unique();

        foreach ($candidate->course->prerequisites as $pre) {
            if ($pre->is_mandatory && !$passedCourseIds->contains($pre->prerequisite_course_id)) {
                throw ValidationException::withMessages(['prerequisite' => 'Prasyarat mata kuliah belum terpenuhi.']);
            }
        }

        foreach ($selected as $detail) {
            foreach ($detail->courseClass?->schedules ?? [] as $existing) {
                foreach ($candidate->schedules as $incoming) {
                    if ($existing->day_of_week === $incoming->day_of_week && 
                        $existing->start_time < $incoming->end_time && 
                        $incoming->start_time < $existing->end_time) {
                        throw ValidationException::withMessages(['schedule' => 'Terjadi konflik jadwal.']);
                    }
                }
            }
        }

        $eligibility = $this->execute($student, $header->semester);
        $currentSks = (int)$selected->sum(fn($d) => (int)($d->courseClass?->course?->credits ?? 0));
        
        if ($currentSks + (int)$candidate->course->credits > $eligibility['max_sks']) {
            throw ValidationException::withMessages(['total_credits' => "Batas SKS berdasarkan IPK {$eligibility['gpa']} adalah {$eligibility['max_sks']} SKS."]);
        }
    }

    private function calculateGpa(Student $student): float
    {
        $grades = StudentGrade::where('student_id', $student->id)
            ->where('status', 'published')
            ->with('courseClass.course')
            ->get();
            
        if ($grades->isEmpty()) return 0.0;
        
        $points = ['A' => 4.0, 'B' => 3.0, 'C' => 2.0, 'D' => 1.0, 'E' => 0.0];
        $totalPoints = 0; $totalSks = 0;
        
        foreach ($grades as $g) {
            $sks = (int)($g->courseClass?->course?->credits ?? 0);
            $totalPoints += ($points[$g->grade_letter] ?? 0) * $sks;
            $totalSks += $sks;
        }
        
        return $totalSks > 0 ? round($totalPoints / $totalSks, 2) : 0.0;
    }

    private function getMaxSks(float $gpa): int
    {
        if ($gpa >= 3.50) return 24;
        if ($gpa >= 3.00) return 21;
        if ($gpa >= 2.50) return 18;
        return 15;
    }
}
