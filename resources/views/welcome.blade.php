<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAKAD NextGen - Sistem Informasi Akademik Terpadu</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Inter & Lucide Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased">

    <!-- Navbar -->
    <header class="fixed top-0 left-0 right-0 bg-white/80 backdrop-blur-md z-50 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <div class="bg-indigo-600 p-2.5 rounded-xl text-white">
                    <i data-lucide="graduation-cap" class="w-6 h-6"></i>
                </div>
                <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">
                    SIAKAD<span class="text-slate-800">NextGen</span>
                </span>
            </div>

            <!-- Nav Links (Desktop) -->
            <nav class="hidden md:flex items-center gap-8 font-medium text-slate-600">
                <a href="#fitur" class="hover:text-indigo-600 transition-colors">Petunjuk Penggunaan</a>
                <a href="#peran" class="hover:text-indigo-600 transition-colors">Modul Pengguna</a>
                <a href="#keunggulan" class="hover:text-indigo-600 transition-colors">Keunggulan</a>
                <a href="#testimoni" class="hover:text-indigo-600 transition-colors">Testimoni</a>
            </nav>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <a href="/admin/login" class="px-5 py-2.5 rounded-xl font-medium text-slate-700 hover:bg-slate-100 transition-all">
                    Portal Akademik
                </a>
                <a href="/pmb" class="px-5 py-2.5 rounded-xl font-medium text-slate-700 hover:bg-slate-100 transition-all">
                    PMB
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="pt-36 pb-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="px-4 py-1.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-600 border border-indigo-100 inline-block mb-4">
                    🚀 Solusi Digitalisasi Kampus Modern
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-tight mb-6">
                    Kelola Layanan Akademik Lebih <span class="bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">Cepat & Cerdas</span>
                </h1>
                <p class="text-lg text-slate-600 mb-8 leading-relaxed">
                    Sistem Informasi Akademik terintegrasi untuk mempermudah KRS online, penilaian dosen, manajemen jadwal, hingga integrasi otomatis laporan PDDikti.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="#demo" class="w-full sm:w-auto px-8 py-4 rounded-xl font-semibold bg-indigo-600 text-white shadow-xl shadow-indigo-200 hover:bg-indigo-700 transition-all flex items-center justify-center gap-2">
                        <span>Mulai Konsultasi Gratis</span>
                        <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </a>
                    <a href="#fitur" class="w-full sm:w-auto px-8 py-4 rounded-xl font-semibold bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 transition-all text-center">
                        Pelajari Fitur
                    </a>
                </div>
            </div>

            <!-- Hero Image / Mockup Preview -->
            <div class="relative mx-auto max-w-5xl">
                <div class="bg-gradient-to-tr from-indigo-500 to-violet-500 rounded-2xl p-2 sm:p-4 shadow-2xl">
                    <div class="bg-white rounded-xl overflow-hidden shadow-inner border border-slate-100">
                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=1200&q=80" alt="Dashboard SIAKAD Preview" class="w-full h-auto object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 bg-white border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-3xl sm:text-4xl font-bold text-indigo-600 mb-1">50+</div>
                    <div class="text-sm text-slate-500 font-medium">Perguruan Tinggi</div>
                </div>
                <div>
                    <div class="text-3xl sm:text-4xl font-bold text-indigo-600 mb-1">250K+</div>
                    <div class="text-sm text-slate-500 font-medium">Mahasiswa Aktif</div>
                </div>
                <div>
                    <div class="text-3xl sm:text-4xl font-bold text-indigo-600 mb-1">99.9%</div>
                    <div class="text-sm text-slate-500 font-medium">Uptime Server</div>
                </div>
                <div>
                    <div class="text-3xl sm:text-4xl font-bold text-indigo-600 mb-1">100%</div>
                    <div class="text-sm text-slate-500 font-medium">Kompatibel PDDikti</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur Utama -->
    <section id="fitur" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Fitur Unggulan Siakad</h2>
                <p class="text-slate-600">Dirancang khusus untuk memenuhi kebutuhan ekosistem kampus digital yang efisien dan terintegrasi.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-6">
                        <i data-lucide="book-open" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">KRS & KHS Online</h3>
                    <p class="text-slate-600 leading-relaxed">Pengisian KRS secara mandiri dengan validasi dosen pembimbing serta cetak KHS dan Transkrip Nilai instan.</p>
                </div>
                <!-- Card 2 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-6">
                        <i data-lucide="credit-card" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Keuangan & UKT</h3>
                    <p class="text-slate-600 leading-relaxed">Integrasi *Payment Gateway* untuk pembayaran UKT, cek tagihan, dan histori transaksi secara *real-time*.</p>
                </div>
                <!-- Card 3 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-6">
                        <i data-lucide="refresh-cw" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Sync PDDikti Otomatis</h3>
                    <p class="text-slate-600 leading-relaxed">Sinkronisasi data master akademik, kelas, nilai, dan kelulusan ke Feeder PDDikti tanpa kendala.</p>
                </div>
                <!-- Card 4 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-6">
                        <i data-lucide="calendar" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Jadwal & Presensi</h3>
                    <p class="text-slate-600 leading-relaxed">Pengaturan jadwal kuliah otomatis bebas *bentrok* dilengkapi presensi dosen dan mahasiswa berbasis QR Code/Mobile.</p>
                </div>
                <!-- Card 5 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-6">
                        <i data-lucide="award" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Modul Tugas Akhir / Skripsi</h3>
                    <p class="text-slate-600 leading-relaxed">Alur pengajuan judul, bimbingan online, penjadwalan sidang, hingga yudisium dalam satu pintu.</p>
                </div>
                <!-- Card 6 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-6">
                        <i data-lucide="smartphone" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Aplikasi Mobile</h3>
                    <p class="text-slate-600 leading-relaxed">Akses informasi akademik melalui smartphone (Android & iOS) untuk dosen dan mahasiswa kapan saja.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Modul Peran Pengguna -->
    <section id="peran" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="text-indigo-600 font-semibold text-sm tracking-wider uppercase mb-2 block">Akses Terorganisir</span>
                    <h2 class="text-3xl font-bold text-slate-900 mb-6">Satu Sistem untuk Seluruh Sivitas Akademika</h2>
                    <p class="text-slate-600 mb-8">SIAKAD memberikan hak akses yang disesuaikan dengan peran masing-masing pengguna untuk menjaga keamanan data dan efisiensi kerja.</p>

                    <div class="space-y-4">
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <div class="p-2 bg-indigo-600 text-white rounded-lg mt-1"><i data-lucide="user-check" class="w-5 h-5"></i></div>
                            <div>
                                <h4 class="font-bold text-slate-900">Portal Mahasiswa</h4>
                                <p class="text-sm text-slate-600">Lihat jadwal, kontrak KRS, cek nilai KHS, pembayaran UKT, dan ajukan surat keterangan.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <div class="p-2 bg-indigo-600 text-white rounded-lg mt-1"><i data-lucide="user-cog" class="w-5 h-5"></i></div>
                            <div>
                                <h4 class="font-bold text-slate-900">Portal Dosen</h4>
                                <p class="text-sm text-slate-600">Input nilai, validasi KRS mahasiswa bimbingan, kelola bahan ajar, dan isi jurnal perkuliahan.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <div class="p-2 bg-indigo-600 text-white rounded-lg mt-1"><i data-lucide="shield-check" class="w-5 h-5"></i></div>
                            <div>
                                <h4 class="font-bold text-slate-900">Portal Admin & BAAK</h4>
                                <p class="text-sm text-slate-600">Pengelolaan master data, Kurikulum MBKM, penataan jadwal, dan pelaporan akreditasi.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="bg-gradient-to-tr from-violet-600 to-indigo-600 rounded-3xl p-8 text-white">
                        <h3 class="text-2xl font-bold mb-4">Siap Bertransformasi Digital?</h3>
                        <p class="text-indigo-100 mb-6">Tingkatkan efisiensi operasional kampus Anda hingga 80% dengan SIAKAD berbasis cloud kami.</p>
                        <a href="#demo" class="inline-block px-6 py-3 bg-white text-indigo-600 font-semibold rounded-xl hover:bg-slate-100 transition-all shadow-lg">Ajukan Demonstrasi Sistem</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center gap-3 text-white mb-4">
                        <div class="bg-indigo-600 p-2 rounded-lg">
                            <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                        </div>
                        <span class="text-lg font-bold">SIAKADNextGen</span>
                    </div>
                    <p class="text-sm">Platform Sistem Informasi Akademik masa kini untuk perguruan tinggi di Indonesia.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Tautan</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#fitur" class="hover:text-white transition-colors">Fitur</a></li>
                        <li><a href="#peran" class="hover:text-white transition-colors">Modul Pengguna</a></li>
                        <li><a href="#keunggulan" class="hover:text-white transition-colors">Keunggulan</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Dukungan</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Dokumentasi API</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Panduan Feeder PDDikti</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Pusat Bantuan</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center gap-2"><i data-lucide="mail" class="w-4 h-4"></i> info@siakadnextgen.ac.id</li>
                        <li class="flex items-center gap-2"><i data-lucide="phone" class="w-4 h-4"></i> +62 812-3456-7890</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-800 pt-8 text-center text-xs">
                <p>&copy; 2026 SIAKAD NextGen. Hak Cipta Dilindungi Undang-Undang.</p>
            </div>
        </div>
    </footer>

    <!-- Lucide Icons Render Script -->
    <script>
        lucide.createIcons();
    </script>
</body>

</html>