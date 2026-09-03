<?php

namespace App\Actions\Reporting;

use App\Actions\Grading\SignAcademicTranscriptAction;
use App\Models\AcademicTranscript;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportAcademicTranscriptExcelAction
{
    public function execute(AcademicTranscript $transcript, int $actorId): BinaryFileResponse
    {
        $transcript->load(['student', 'semester', 'items.course']);
        abort_unless($transcript->status === 'final', 422, 'Hanya transkrip final yang dapat diekspor.');

        $signatureValid = app(SignAcademicTranscriptAction::class)->verify($transcript);
        $spreadsheet = new Spreadsheet();
        $summary = $spreadsheet->getActiveSheet();
        $summary->setTitle('Ringkasan');
        $summary->fromArray([
            ['TRANSKRIP AKADEMIK'],
            ['ID Transkrip', $transcript->id],
            ['ID Mahasiswa', $transcript->student_id],
            ['NIM', $transcript->student?->nim],
            ['Jenis', strtoupper($transcript->type)],
            ['Semester', $transcript->semester_id ?: 'Semua Semester'],
            ['Total SKS', (float) $transcript->total_credits],
            ['Total Mutu', (float) $transcript->total_quality_points],
            ['IPK/IPS', (float) $transcript->gpa],
            ['Status', strtoupper($transcript->status)],
            ['Tanda Tangan', $signatureValid ? 'VALID' : 'TIDAK VALID'],
            ['Algoritma', $transcript->signature_algorithm ?: '-'],
            ['Hash', $transcript->signature_hash ?: '-'],
            ['Penandatangan', $transcript->signer_name ?: '-'],
            ['Jabatan', $transcript->signer_title ?: '-'],
            ['Ditandatangani', optional($transcript->signed_at)->toDateTimeString() ?: '-'],
        ], null, 'A1');
        $summary->getStyle('A1:A16')->getFont()->setBold(true);
        $summary->getStyle('A1:B16')->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
        $summary->getColumnDimension('A')->setWidth(22);
        $summary->getColumnDimension('B')->setWidth(48);

        $detail = $spreadsheet->createSheet();
        $detail->setTitle('Rincian Nilai');
        $detail->fromArray([['No', 'Kode', 'Mata Kuliah', 'SKS', 'Nilai', 'Huruf', 'Bobot', 'Mutu']], null, 'A1');
        foreach ($transcript->items->sortBy('id')->values() as $index => $item) {
            $detail->fromArray([[
                $index + 1,
                $item->course?->code,
                $item->course?->name,
                (float) $item->credits,
                (float) $item->score,
                $item->letter_grade,
                (float) $item->grade_point,
                (float) $item->quality_points,
            ]], null, 'A' . ($index + 2));
        }
        $detail->getStyle('A1:H1')->getFont()->setBold(true);
        $detail->getStyle('A1:H1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        foreach (['A' => 8, 'B' => 15, 'C' => 40, 'D' => 10, 'E' => 12, 'F' => 10, 'G' => 10, 'H' => 12] as $column => $width) {
            $detail->getColumnDimension($column)->setWidth($width);
        }
        $detail->freezePane('A2');

        $path = storage_path('app/' . 'transkrip-' . $transcript->student_id . '-' . $transcript->id . '.xlsx');
        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        activity('academic')->causedBy($actorId)->performedOn($transcript)->withProperties([
            'format' => 'xlsx',
            'signature_valid' => $signatureValid,
        ])->log('transcript.exported');

        return response()->download($path, basename($path), [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}
