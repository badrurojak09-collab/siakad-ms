<?php
namespace App\Policies;
class StudentPolicy extends AcademicPolicy
{
    protected bool $studentReadOnly = true;
}
