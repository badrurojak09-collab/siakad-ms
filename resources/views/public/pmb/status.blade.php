@extends('public.pmb.layout')

@section('title', 'Cek Status Pendaftaran')

@section('content')
<div class="max-w-2xl mx-auto">
    @if (!isset($applicant))
    <div class="text-center max-w-2xl mx-auto mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-3">Cek Status Pendaftaran</h1>
        <p class="text-slate-600">Masukkan nomor pendaftaran dan surel yang Anda gunakan saat mendaftar untuk
            melihat progres PMB Anda.</p>
    </div>
    @endif

    <form method="POST" action="{{ route('pmb.check') }}"
        class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-8 mb-8">
        @csrf
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label for="registration_number"
                    class="block text-sm font-medium text-slate-700 mb-1.5">Nomor Pendaftaran <span
                        class="text-rose-500">*</span></label>
                <input type="text" id="registration_number" name="registration_number"
                    value="{{ old('registration_number', $applicant->registration_number ?? '') }}" required
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="PMB-...">
                @error('registration_number')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Surel <span
                        class="text-rose-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email', $applicant->email ?? '') }}"
                    required
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                @error('email')
                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <button type="submit"
            class="mt-4 w-full sm:w-auto px-8 py-3 rounded-xl font-semibold bg-indigo-600 text-white shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all flex items-center justify-center gap-2">
            <i data-lucide="search" class="w-4 h-4"></i>
            <span>Lihat Status</span>
        </button>
    </form>

    @if (isset($applicant))
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8 border-b border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <p class="text-xs font-mono text-slate-400">{{ $applicant->registration_number }}</p>
                    <h2 class="text-xl font-bold text-slate-900">{{ $applicant->full_name }}</h2>
                    <p class="text-sm text-slate-500">{{ $applicant->email }}</p>
                </div>
                @php
                $statusLabels = \App\Http\Controllers\PublicPmbController::statusLabels();
                $badgeClasses = \App\Http\Controllers\PublicPmbController::statusBadgeClasses();
                $status = $applicant->status;
                @endphp
                <span
                    class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold border {{ $badgeClasses[$status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                    {{ $statusLabels[$status] ?? ucfirst($status) }}
                </span>
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            <div>
                <h3 class="font-bold text-slate-900 text-sm mb-3 flex items-center gap-2">
                    <i data-lucide="list-checks" class="w-4 h-4 text-indigo-600"></i> Pilihan Program Studi
                </h3>
                <div class="space-y-2">
                    @forelse ($selections as $selection)
                    <div
                        class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-sm text-slate-700 font-medium">
                            {{ $selection->choice_order }}. {{ $selection->studyProgram?->name ?? '-' }}
                        </span>
                        @if (!is_null($selection->score))
                        <span class="text-sm font-semibold text-slate-900">Nilai:
                            {{ number_format((float) $selection->score, 2) }}</span>
                        @endif
                    </div>
                    @empty
                    <p class="text-sm text-slate-500">Belum ada pilihan prodi.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <h3 class="font-bold text-slate-900 text-sm mb-3 flex items-center gap-2">
                    <i data-lucide="file-check-2" class="w-4 h-4 text-indigo-600"></i> Dokumen
                </h3>
                <div class="space-y-2">
                    @forelse ($applicant->documents as $document)
                    <div
                        class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 border border-slate-100">
                        <a href="{{ $document->file_url }}" target="_blank" rel="noopener"
                            class="text-sm text-indigo-600 font-medium hover:underline">
                            {{ \Illuminate\Str\Str::headline($document->document_type) }}
                        </a>
                        <span
                            class="px-3 py-1 rounded-full text-xs font-semibold border {{ (\App\Http\Controllers\PublicPmbController::documentStatusClasses())[$document->verification_status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                            {{ (\App\Http\Controllers\PublicPmbController::documentStatusLabels())[$document->verification_status] ?? ucfirst($document->verification_status) }}
                        </span>
                    </div>
                    @empty
                    <p class="text-sm text-slate-500">
                        Belum ada dokumen. Panitia PMB akan menghubungi Anda untuk proses unggah/verifikasi
                        dokumen.
                    </p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 text-sm text-slate-600">
                <p class="font-semibold text-slate-900 mb-1">Catatan</p>
                <p>Status <strong>Draf</strong> berarti pendaftaran Anda masih menunggu kelengkapan dokumen.
                    Setelah dokumen lengkap dan diverifikasi, panitia akan mengajukan pendaftaran Anda ke tahap
                    seleksi. Hasil seleksi akan tampil pada halaman ini.</p>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection