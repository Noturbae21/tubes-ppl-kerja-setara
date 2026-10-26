<nav class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-100 shadow-sm"
     x-data="{ mobileOpen: false }"
     role="navigation"
     aria-label="Navigasi utama">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group" aria-label="KerjaSetara - Beranda">
                @if(file_exists(public_path('images/logo-kerjasetara.png')))
                    <img src="{{ asset('images/logo-kerjasetara.png') }}" alt="Logo KerjaSetara" class="h-8 w-auto">
                @else
                    <div class="w-8 h-8 bg-blue-900 rounded-lg flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                @endif
                <span class="text-lg font-bold text-blue-900 tracking-tight">KerjaSetara</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center gap-8" role="menubar">
                <a href="#tentang" class="navbar-link" role="menuitem">Tentang Kami</a>
                <a href="#aksesibilitas" class="navbar-link" role="menuitem">Fitur Aksesibilitas</a>
                <a href="#perusahaan" class="navbar-link" role="menuitem">Untuk Perusahaan</a>
                <a href="#lowongan" class="navbar-link" role="menuitem">Cari Kerja</a>
            </div>

            <!-- Desktop Auth Buttons -->
            <div class="hidden lg:flex items-center gap-3">
                @auth
                    <span class="text-sm text-slate-600 font-medium">
                        Halo, {{ auth()->user()->name }}
                    </span>
                    <a href="{{ route('dashboard') }}"
                       class="text-sm font-semibold text-blue-900 hover:text-blue-700 transition-colors px-3 py-2">
                        Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="text-sm border border-slate-300 text-slate-600 hover:border-red-300 hover:text-red-600 rounded-lg px-4 py-2 transition-colors font-medium">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="text-sm font-semibold border border-blue-900 text-blue-900 hover:bg-blue-50 rounded-lg px-4 py-2 transition-colors">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       class="text-sm font-semibold bg-blue-900 text-white hover:bg-blue-800 rounded-lg px-4 py-2 transition-colors shadow-sm">
                        Daftar Sekarang
                    </a>
                @endauth

                <!-- Accessibility toggle icon -->
                <button class="w-8 h-8 rounded-full bg-blue-50 hover:bg-blue-100 flex items-center justify-center transition-colors"
                        aria-label="Pengaturan aksesibilitas"
                        title="Aksesibilitas">
                    <svg class="w-4 h-4 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <circle cx="12" cy="4" r="1" stroke-width="2"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7v4M7 21l2.5-4.5M17 21l-2.5-4.5M8.5 13l-1.5 4M15.5 13l1.5 4M9 13h6"/>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu Button -->
            <button class="lg:hidden w-9 h-9 flex items-center justify-center rounded-lg hover:bg-slate-100 transition-colors"
                    @click="mobileOpen = !mobileOpen"
                    :aria-expanded="mobileOpen.toString()"
                    aria-controls="mobile-menu"
                    aria-label="Buka/tutup menu navigasi">
                <svg x-show="!mobileOpen" class="w-5 h-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="mobileOpen" x-cloak class="w-5 h-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Drawer -->
    <div id="mobile-menu"
         x-show="mobileOpen"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="lg:hidden border-t border-slate-100 bg-white shadow-lg"
         role="menu"
         aria-label="Menu mobile">
        <div class="max-w-7xl mx-auto px-4 py-4 space-y-1">
            <a href="#tentang" class="block px-3 py-2.5 text-sm font-medium text-slate-700 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors" role="menuitem" @click="mobileOpen = false">Tentang Kami</a>
            <a href="#aksesibilitas" class="block px-3 py-2.5 text-sm font-medium text-slate-700 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors" role="menuitem" @click="mobileOpen = false">Fitur Aksesibilitas</a>
            <a href="#perusahaan" class="block px-3 py-2.5 text-sm font-medium text-slate-700 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors" role="menuitem" @click="mobileOpen = false">Untuk Perusahaan</a>
            <a href="#lowongan" class="block px-3 py-2.5 text-sm font-medium text-slate-700 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors" role="menuitem" @click="mobileOpen = false">Cari Kerja</a>
            <div class="pt-3 mt-3 border-t border-slate-100 flex flex-col gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="block text-center btn-outline text-sm py-2.5">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full btn-primary text-sm py-2.5 justify-center">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block text-center btn-outline text-sm py-2.5">Masuk</a>
                    <a href="{{ route('register') }}" class="block text-center btn-primary text-sm py-2.5 justify-center">Daftar Sekarang</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
