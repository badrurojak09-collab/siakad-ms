<?php

namespace App\Actions\Grading;

use App\Models\StudentGrade;
use Illuminate\Validation\ValidationException;

class RecordFinalGradeAction
{
    public function execute(StudentGrade $grade, float $uts, float $uas, float $assignment = 0): StudentGrade
    {
        if (in_array($grade->grade_status, ['published', 'locked'], true) || $grade->locked_at) {
            throw ValidationException::withMessages(['status' => 'Nilai yang sudah dipublish atau dikunci tidak dapat diubah.']);
        }
        foreach (['uts' => $uts, 'uas' => $uas, 'assignment' => $assignment] as $key => $value) {
            if ($value < 0 || $value > 100) {
                throw ValidationException::withMessages([$key => 'Nilai harus berada antara 0 dan 100.']);
            }
        }

        $final = round($uts * .30 + $uas * .40 + $assignment * .30, 2);
        $letter = $final >= 85 ? 'A' : ($final >= 75 ? 'B' : ($final >= 65 ? 'C' : ($final >= 50 ? 'D' : 'E')));
        $gradePoint = ['A' => 4.0, 'B' => 3.0, 'C' => 2.0, 'D' => 1.0, 'E' => 0.0][$letter];

        $grade->update([
            'uts_score' => $uts,
            'uas_score' => $uas,
            'assignment_score' => $assignment,
            'final_score' => $final,
            'letter_grade' => $letter,
            'grade_point' => $gradePoint,
            'grade_status' => 'draft',
        ]);
        return $grade->refresh();
    }
}
