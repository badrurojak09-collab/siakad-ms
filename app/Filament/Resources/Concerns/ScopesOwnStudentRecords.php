<?php

namespace App\Filament\Resources\Concerns;

use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait ScopesOwnStudentRecords
{
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();
        if (!$user || !$user->hasRole('student'))
            return $query;

        $studentId = $user->student?->getKey();
        if (!$studentId)
            return $query->whereRaw('1 = 0');
        if ($query->getModel() instanceof Student)
            return $query->whereKey($studentId);
        return $query->where($query->getModel()->qualifyColumn('student_id'), $studentId);
    }
}
