<?php

namespace App\Services\Pddikti;

use App\Models\{AcademicTranscript, Student};

class PddiktiPayloadMapper
{
    public function student(Student $student): array
    {
        return ['external_key' => 'student:'.$student->id, 'nim' => $student->nim, 'study_program_id' => $student->study_program_id, 'entry_year' => $student->entry_year, 'status' => $student->status];
    }

    public function transcript(AcademicTranscript $transcript): array
    {
        return ['external_key' => 'transcript:'.$transcript->id, 'student_id' => $transcript->student_id, 'type' => $transcript->type, 'semester_id' => $transcript->semester_id, 'total_credits' => (float) $transcript->total_credits, 'gpa' => (float) $transcript->gpa, 'items' => $transcript->items->sortBy('id')->map(fn ($item): array => ['course_id' => $item->course_id, 'credits' => (float) $item->credits, 'letter_grade' => $item->letter_grade, 'grade_point' => (float) $item->grade_point])->values()->all()];
    }
}
