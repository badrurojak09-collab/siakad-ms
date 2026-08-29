<?php
namespace App\Actions\Administration;
use App\Models\LeaveRequest; use Illuminate\Validation\ValidationException;
class ApproveLeaveRequestAction { public function execute(LeaveRequest $request,int $approverId, bool $approve=true,?string $note=null): LeaveRequest { if($request->status!=='pending') throw ValidationException::withMessages(['status'=>'Pengajuan cuti bukan berstatus pending.']); $request->update(['status'=>$approve?'approved':'rejected','approved_by'=>$approverId,'approved_at'=>now(),'metadata'=>array_merge($request->metadata??[],['approval_note'=>$note])]); activity('administration')->causedBy($approverId)->performedOn($request)->log($approve?'leave.approved':'leave.rejected'); return $request->refresh(); } }
