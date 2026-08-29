<?php
namespace App\Actions\Thesis;
use App\Models\Thesis; use Illuminate\Validation\ValidationException;
class SubmitThesisAction { public function execute(Thesis $thesis): Thesis { if($thesis->status!=='proposed') throw ValidationException::withMessages(['status'=>'Pengajuan tugas akhir harus berstatus proposed.']); if(!$thesis->supervisor_1_id) throw ValidationException::withMessages(['supervisor'=>'Dosen pembimbing utama wajib ditentukan.']); $thesis->update(['status'=>'in_progress']); activity('thesis')->performedOn($thesis)->log('thesis.started'); return $thesis->refresh(); } }
