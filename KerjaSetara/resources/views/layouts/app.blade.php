<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'KerjaSetara — Platform karir inklusif No. 1 di Indonesia. Menghubungkan talenta penyandang disabilitas dengan perusahaan ramah aksesibilitas.')">
    <meta name="keywords" content="kerja inklusif, disabilitas, lowongan kerja aksesibel, KerjaSetara, karir setara">
    <meta property="og:title" content="@yield('title', 'KerjaSetara') — Platform Karir Inklusif">
    <meta property="og:description" content="Setiap karir berhak atas kesempatan yang setara.">
    <meta property="og:type" content="website">

    <title>@yield('title', 'KerjaSetara') — Platform Karir Inklusif</title>

    <!-- Preconnect for Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CSS (CDN fallback to prevent blank/unstyled white screen) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#193a7c',
                    }
                }
            }
        }
    </script>

    <!-- Vite Assets (Safe Check) -->
    @php
        $manifestExists = file_exists(public_path('build/manifest.json')) || file_exists(public_path('build/.vite/manifest.json'));
        $hotExists = file_exists(public_path('hot'));
    @endphp
    @if($hotExists || $manifestExists)
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    @stack('head')
</head>
<body class="bg-slate-50 text-slate-900 antialiased">

    <!-- Skip to content (Accessibility) -->
    <a href="#main-content" class="skip-link">Langsung ke konten utama</a>

    <!-- Flash Messages -->
    @if(session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-end="opacity-0"
             x-init="setTimeout(() => show = false, 5000)"
             class="fixed top-4 right-4 z-50 flex items-center gap-3 bg-green-600 text-white px-5 py-3 rounded-xl shadow-lg text-sm font-medium max-w-sm"
             role="alert"
             aria-live="polite">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="ml-auto hover:opacity-75" aria-label="Tutup notifikasi">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }"
             x-show="show"
             x-init="setTimeout(() => show = false, 5000)"
             class="fixed top-4 right-4 z-50 flex items-center gap-3 bg-red-600 text-white px-5 py-3 rounded-xl shadow-lg text-sm font-medium max-w-sm"
             role="alert"
             aria-live="assertive">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('error') }}</span>
            <button @click="show = false" class="ml-auto hover:opacity-75" aria-label="Tutup notifikasi">×</button>
        </div>
    @endif

    <!-- Navbar -->
    @section('navbar')
        @include('components.navbar')
    @show

    <!-- Main Content -->
    <main id="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.footer')

    @stack('scripts')
</body>
</html>
