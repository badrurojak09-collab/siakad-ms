@extends('public.pmb.layout')

@section('title', 'Pendaftaran Mandiri PMB')

@section('content')
<div class="text-center max-w-3xl mx-auto mb-12">
    <span
        class="px-4 py-1.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-600 border border-indigo-100 inline-block mb-4">
        🎓 Penerimaan Mahasiswa Baru
    </span>
    <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 leading-tight mb-4">
        Pendaftaran Mandiri <span class="bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">PMB</span>
    </h1>
    <p class="text-slate-600 leading-relaxed">
        Isi formulir di bawah ini untuk mendaftar. Setelah terdaftar, simpan nomor pendaftaran Anda untuk memantau
        progres verifikasi dan seleksi melalui menu <a href="{{ route('pmb.status') }}"
            class="font-semibold text-indigo-600 hover:underline">Cek Status</a>.
    </p>
</div>

<div class="max-w-3xl mx-auto">
    @if (session('registered'))
    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 text-sm">
        Pendaftaran berhasil. Silakan simpan nomor pendaftaran Anda.
    </div>
    @endif

    @if ($errors->any())
    <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 p-4 text-rose-800 text-sm">
        <p class="font-semibold mb-1">Periksa kembali isian berikut:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('pmb.store') }}"
        class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-8 space-y-8">
        @csrf

        <div>
            <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i data-lucide="calendar-days" class="w-5 h-5 text-indigo-600"></i>
                Gelombang Pendaftaran
            </h2>

            @if ($periods->isEmpty())
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-800 text-sm">
                <p class="font-semibold mb-1">Pendaftaran sedang ditutup.</p>
                <p>Saat ini tidak ada gelombang PMB yang aktif. Silakan kembali lagi nanti atau hubungi panitia
                    PMB.</p>
            </div>
            @else
            <div class="space-y-3">
                @foreach ($periods as $period)
                <label
                    class="flex items-start gap-4 p-4 rounded-xl border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/40 transition-all cursor-pointer has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                    <input type="radio" name="admission_period_id" value="{{ $period->id }}"
                        class="mt-1 w-4 h-4 text-indigo-600 accent-indigo-600"
                        @checked(old('admission_period_id', $defaultPeriod?->id) == $period->id)
                    @disabled($period->status === 'closed') required>
                    <span class="flex-1">
                        <span class="block font-semibold text-slate-900">{{ $period->name }}
                            <span class="text-xs font-mono text-slate-400">{{ $period->code }}</span>
                            @if ($period->status === 'closed')
                            <span
                                class="ml-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-500">Ditutup</span>
                            @endif
                        </span>
                        <span class="block text-sm text-slate-500 mt-0.5">
                            Pendaftaran: {{ $period->registration_start->translatedFormat('d M Y') }} —
                            {{ $period->registration_end->translatedFormat('d M Y') }}
                        </span>
                        @if ($period->selection_end)
                        <span class="block text-xs text-slate-400 mt-0.5">
                            Seleksi s.d. {{ $period->selection_end->translatedFormat('d M Y') }}
                        </span>
                        @endif
                    </span>
                </label>
                @endforeach
            </div>
            @endif
            @error('admission_period_id')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <h2 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i data-lucide="user-round" class="w-5 h-5 text-indigo-600"></i>
                Data Pendaftar
            </h2>
            <div class="grid sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label for="full_name" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap <span
                            class="text-rose-500">*</span></label>
                    <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" required
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('full_name')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Surel <span
                            class="text-rose-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('email')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-700 mb-1.5">Nomor WhatsApp /
                        Telepon <span class="text-rose-500">*</span></label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('phone')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="identity_number" class="block text-sm font-medium text-slate-700 mb-1.5">Nomor
                        Identitas (NIK) <span class="text-rose-500">*</span></label>
                    <input type="text" id="identity_number" name="identity_number" value="{{ old('identity_number') }}"
                        required
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('identity_number')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="school_origin" class="block text-sm font-medium text-slate-700 mb-1.5">Asal Sekolah <span
                            class="text-rose-500">*</span></label>
                    <input type="text" id="school_origin" name="school_origin" value="{{ old('school_origin') }}"
                        required
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('school_origin')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-lg font-bold text-slate-900 mb-1 flex items-center gap-2">
                <i data-lucide="list-checks" class="w-5 h-5 text-indigo-600"></i>
                Pilihan Program Studi
            </h2>
            <p class="text-sm text-slate-500 mb-4">Pilih 1–3 program studi. Pilihan pertama menjadi prioritas utama.
            </p>
            <div class="space-y-3">
                @forelse ($programs as $program)
                <label
                    class="flex items-start gap-4 p-4 rounded-xl border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/40 transition-all cursor-pointer has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                    <input type="checkbox" name="program_choices[]" value="{{ $program->id }}"
                        class="mt-1 w-4 h-4 text-indigo-600 accent-indigo-600"
                        @checked(in_array((string) $program->id, old('program_choices', [])))>
                    <span>
                        <span class="block font-semibold text-slate-900">{{ $program->name }}</span>
                        <span class="block text-xs text-slate-400 font-mono">{{ $program->code }}</span>
                    </span>
                </label>
                @empty
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-800 text-sm">
                    Program studi belum tersedia. Hubungi panitia PMB.
                </div>
                @endforelse
            </div>
            @error('program_choices.*')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        @unless ($periods->isEmpty() || $programs->isEmpty())
        <button type="submit"
            class="w-full px-8 py-4 rounded-xl font-semibold bg-indigo-600 text-white shadow-xl shadow-indigo-200 hover:bg-indigo-700 transition-all flex items-center justify-center gap-2">
            <span>Daftar Sekarang</span>
            <i data-lucide="arrow-right" class="w-5 h-5"></i>
        </button>
        <p class="text-center text-xs text-slate-400">
            Dengan mendaftar, Anda menyetujui persyaratan penerimaan mahasiswa baru yang berlaku.
        </p>
        @endunless
    </form>
</div>
@endsection