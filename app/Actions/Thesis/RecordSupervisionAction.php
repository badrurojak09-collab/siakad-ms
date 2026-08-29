<?php
namespace App\Actions\Thesis;
use App\Models\{Thesis,Supervision}; use Illuminate\Validation\ValidationException;
class RecordSupervisionAction { public function execute(Thesis $thesis,array $data): Supervision { if(!in_array($thesis->status,['in_progress','completed'],true)) throw ValidationException::withMessages(['status'=>'Bimbingan hanya dapat dicatat pada tugas akhir aktif.']); if(empty($data['supervisor_id'])||empty($data['meeting_date'])) throw ValidationException::withMessages(['meeting'=>'Pembimbing dan tanggal pertemuan wajib diisi.']); return $thesis->supervisions()->create(array_merge($data,['tenant_id'=>$thesis->tenant_id,'status'=>'conducted'])); } }
