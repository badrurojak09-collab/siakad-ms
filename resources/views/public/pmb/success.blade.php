@extends('public.pmb.layout')

@section('title', 'Pendaftaran Berhasil')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-violet-600 p-8 text-center text-white">
            <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="check-check" class="w-8 h-8"></i>
            </div>
            <h1 class="text-2xl font-extrabold mb-1">Pendaftaran Berhasil!</h1>
            <p class="text-indigo-100 text-sm">Data Anda telah masuk ke sistem PMB dan akan diverifikasi oleh
                panitia.</p>
        </div>

        <div class="p-8 space-y-6">
            <div class="rounded-xl border-2 border-dashed border-indigo-200 bg-indigo-50/50 p-5 text-center">
                <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1">Nomor Pendaftaran</p>
                <p class="text-2xl font-mono font-bold text-slate-900 tracking-wide">
                    {{ $applicant->registration_number }}
                </p>
                <p class="text-xs text-slate-500 mt-2">Simpan nomor ini untuk <a
                        href="{{ route('pmb.status') }}" class="font-semibold text-indigo-600 hover:underline">cek
                        status</a> pendaftaran Anda.</p>
            </div>

            <dl class="grid sm:grid-cols-2 gap-4 text-sm">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <dt class="text-slate-500">Nama Lengkap</dt>
                    <dd class="font-semibold text-slate-900 mt-0.5">{{ $applicant->full_name }}</dd>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <dt class="text-slate-500">Surel</dt>
                    <dd class="font-semibold text-slate-900 mt-0.5">{{ $applicant->email }}</dd>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <dt class="text-slate-500">Program Studi Pilihan</dt>
                    <dd class="mt-1 space-y-1">
                        @forelse ($selections as $selection)
                        <span class="block font-medium text-slate-800">
                            {{ $selection->choice_order }}. {{ $selection->studyProgram?->name ?? '-' }}
                        </span>
                        @empty
                        <span class="text-slate-500">-</span>
                        @endforelse
                    </dd>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <dt class="text-slate-500">Status</dt>
                    <dd class="mt-1"><span
                            class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">Draf
                            — menunggu pengajuan dokumen</span></dd>
                </div>
            </dl>

            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                <h3 class="font-bold text-slate-900 text-sm mb-2 flex items-center gap-2">
                    <i data-lucide="info" class="w-4 h-4 text-indigo-600"></i> Langkah selanjutnya
                </h3>
                <ol class="text-sm text-slate-600 space-y-1.5 list-decimal list-inside">
                    <li>Panitia PMB akan memverifikasi data dan dokumen Anda.</li>
                    <li>Pantau status pendaftaran melalui menu <a href="{{ route('pmb.status') }}"
                            class="font-semibold text-indigo-600 hover:underline">Cek Status</a> menggunakan nomor
                        pendaftaran + surel.</li>
                    <li>Hasil seleksi dan langkah daftar ulang akan diinformasikan oleh panitia PMB.</li>
                </ol>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('pmb.status') }}"
                    class="flex-1 px-6 py-3 rounded-xl font-semibold bg-indigo-600 text-white text-center shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all">
                    Cek Status Pendaftaran
                </a>
                <a href="{{ route('pmb.index') }}"
                    class="flex-1 px-6 py-3 rounded-xl font-semibold bg-white text-slate-700 border border-slate-200 text-center hover:bg-slate-50 transition-all">
                    Kembali ke Formulir
                </a>
            </div>
        </div>
    </div>
</div>
@endsection