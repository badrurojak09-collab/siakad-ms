<?php
namespace App\Enums;

enum KrsStatus: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Finalized = 'finalized';
    case RevisionRequired = 'revision_required';
}
