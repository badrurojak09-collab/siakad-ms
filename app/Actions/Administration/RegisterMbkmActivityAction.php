<?php
namespace App\Actions\Administration;
use App\Models\MbkmActivity; use Illuminate\Validation\ValidationException;
class RegisterMbkmActivityAction { public function execute(array $data): MbkmActivity { if(empty($data['student_id'])||empty($data['activity_type'])) throw ValidationException::withMessages(['activity'=>'Mahasiswa dan jenis kegiatan wajib diisi.']); if(isset($data['start_date'],$data['end_date'])&&$data['end_date']<$data['start_date']) throw ValidationException::withMessages(['end_date'=>'Tanggal selesai tidak boleh sebelum tanggal mulai.']); return MbkmActivity::create(array_merge($data,['status'=>'pending'])); } }
