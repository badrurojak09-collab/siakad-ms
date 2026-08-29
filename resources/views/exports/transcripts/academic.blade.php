<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Transkrip Akademik</title>
    <style>
        @page { margin: 26px 34px; }
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 10px; }
        h1 { text-align: center; font-size: 18px; margin: 0 0 4px; }
        h2 { text-align: center; font-size: 12px; margin: 0 0 18px; font-weight: normal; }
        .meta { width: 100%; margin-bottom: 14px; }
        .meta td { padding: 3px 0; vertical-align: top; }
        .meta td:first-child { width: 125px; font-weight: bold; }
        table.grades { width: 100%; border-collapse: collapse; }
        .grades th, .grades td { border: 1px solid #6b7280; padding: 5px; }
        .grades th { background: #e5e7eb; text-align: center; }
        .center { text-align: center; }
        .right { text-align: right; }
        .totals { margin-top: 12px; width: 100%; }
        .totals td { padding: 4px; }
        .signature { margin-top: 28px; width: 100%; }
        .signature td { width: 50%; vertical-align: top; text-align: center; }
        .signature-box { margin-top: 35px; }
        .hash { font-size: 7px; word-wrap: break-word; }
        .valid { color: #166534; font-weight: bold; }
        .invalid { color: #991b1b; font-weight: bold; }
    </style>
</head>
<body>
    <h1>TRANSKRIP AKADEMIK</h1>
    <h2>{{ strtoupper($transcript->type) }}</h2>

    <table class="meta">
        <tr><td>Mahasiswa</td><td>: {{ $transcript->student?->nim ?: '-' }} / ID {{ $transcript->student_id }}</td></tr>
        <tr><td>Transkrip</td><td>: #{{ $transcript->id }} — {{ $transcript->semester_id ? 'Semester '.$transcript->semester_id : 'Seluruh Semester' }}</td></tr>
        <tr><td>Status</td><td>: {{ strtoupper($transcript->status) }}</td></tr>
    </table>

    <table class="grades">
        <thead><tr><th>No</th><th>Kode</th><th>Mata Kuliah</th><th>SKS</th><th>Nilai</th><th>Huruf</th><th>Bobot</th><th>Mutu</th></tr></thead>
        <tbody>
        @foreach($items as $index => $item)
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td>{{ $item->course?->code ?: '-' }}</td>
                <td>{{ $item->course?->name ?: '-' }}</td>
                <td class="center">{{ number_format((float) $item->credits, 2) }}</td>
                <td class="right">{{ number_format((float) $item->score, 2) }}</td>
                <td class="center">{{ $item->letter_grade ?: '-' }}</td>
                <td class="right">{{ number_format((float) $item->grade_point, 2) }}</td>
                <td class="right">{{ number_format((float) $item->quality_points, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr><td>Total SKS</td><td>: {{ number_format((float) $transcript->total_credits, 2) }}</td><td>IPK/IPS</td><td>: <strong>{{ number_format((float) $transcript->gpa, 2) }}</strong></td></tr>
        <tr><td>Total Mutu</td><td>: {{ number_format((float) $transcript->total_quality_points, 2) }}</td><td>Generated</td><td>: {{ optional($transcript->generated_at)->format('d-m-Y H:i') ?: '-' }}</td></tr>
    </table>

    <table class="signature">
        <tr>
            <td></td>
            <td>
                <div>{{ $transcript->signer_title ?: 'Pejabat Akademik' }}</div>
                <div class="signature-box"><strong>{{ $transcript->signer_name ?: 'Belum diisi' }}</strong></div>
                <div>{{ optional($transcript->signed_at)->format('d-m-Y H:i') ?: '-' }}</div>
                <div class="{{ $signatureValid ? 'valid' : 'invalid' }}">Tanda tangan: {{ $signatureValid ? 'VALID' : 'TIDAK VALID' }}</div>
                <div class="hash">Hash HMAC-SHA256: {{ $transcript->signature_hash ?: '-' }}</div>
            </td>
        </tr>
    </table>
</body>
</html>
