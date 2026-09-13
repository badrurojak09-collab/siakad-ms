<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PMB') - SIAKAD NextGen</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
            <a href="/" class="flex items-center gap-3">
                <div class="bg-indigo-600 p-2.5 rounded-xl text-white">
                    <i data-lucide="graduation-cap" class="w-6 h-6"></i>
                </div>
                <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">
                    SIAKAD<span class="text-slate-800">NextGen</span>
                </span>
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ route('pmb.status') }}"
                    class="px-5 py-2.5 rounded-xl font-medium text-slate-700 hover:bg-slate-100 transition-all">
                    Cek Status
                </a>
                <a href="{{ route('pmb.index') }}"
                    class="px-5 py-2.5 rounded-xl font-semibold bg-indigo-600 text-white shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </header>

    <main class="pt-28 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <footer class="bg-slate-900 text-slate-400 py-8 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm">&copy; 2026 SIAKAD NextGen. Hak Cipta Dilindungi Undang-Undang.</p>
            <a href="/" class="text-sm hover:text-white transition-colors flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Beranda
            </a>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>