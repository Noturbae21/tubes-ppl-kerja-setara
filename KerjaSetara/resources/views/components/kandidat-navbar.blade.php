<nav class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-sm"
     x-data="{ mobileOpen: false, notifOpen: false, profileOpen: false }"
     @keydown.escape.window="notifOpen = false; profileOpen = false; mobileOpen = false"
     role="navigation"
     aria-label="Navigasi Kandidat KerjaSetara">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <!-- Left: Logo & Portal Badge -->
            <div class="flex items-center gap-4">
                <a href="{{ route('kandidat.home') }}" 
                   class="flex items-center gap-2.5 group focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none rounded-lg p-1" 
                   aria-label="KerjaSetara Beranda Kandidat">
                    @if(file_exists(public_path('images/logo-kerjasetara.png')))
                        <img src="{{ asset('images/logo-kerjasetara.png') }}" alt="Logo KerjaSetara" class="h-8 w-auto">
                    @else
                        <div class="w-9 h-9 bg-blue-900 rounded-xl flex items-center justify-center shadow-sm text-white group-hover:bg-blue-800 transition-colors">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    @endif
                    <div class="flex flex-col">
                        <span class="text-lg font-extrabold text-blue-900 tracking-tight leading-none">KerjaSetara</span>
                        <span class="text-[10px] font-semibold text-blue-600 tracking-wider uppercase mt-0.5">Portal Kandidat</span>
                    </div>
                </a>
            </div>

            <!-- Center: Navigation Menu -->
            <div class="hidden md:flex items-center gap-6 lg:gap-8 h-16" role="menubar">
                <a href="{{ route('kandidat.home') }}"
                   class="inline-flex items-center h-full px-1 text-sm font-semibold transition-all focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none {{ request()->routeIs('kandidat.home') || request()->routeIs('kandidat.lowongan.*') ? 'text-blue-900 font-bold border-b-2 border-blue-600' : 'text-slate-600 hover:text-blue-900' }}"
                   role="menuitem"
                   aria-current="{{ request()->routeIs('kandidat.home') ? 'page' : 'false' }}">
                    Cari Kerja
                </a>

                <a href="{{ route('kandidat.profil') }}"
                   class="inline-flex items-center h-full px-1 text-sm font-semibold transition-all focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none {{ request()->routeIs('kandidat.profil') ? 'text-blue-900 font-bold border-b-2 border-blue-600' : 'text-slate-600 hover:text-blue-900' }}"
                   role="menuitem"
                   aria-current="{{ request()->routeIs('kandidat.profil') ? 'page' : 'false' }}">
                    Profil Saya
                </a>

                <a href="{{ route('kandidat.lamaran') }}"
                   class="inline-flex items-center h-full px-1 text-sm font-semibold transition-all focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none {{ request()->routeIs('kandidat.lamaran') || request()->routeIs('kandidat.riwayat') ? 'text-blue-900 font-bold border-b-2 border-blue-600' : 'text-slate-600 hover:text-blue-900' }}"
                   role="menuitem"
                   aria-current="{{ request()->routeIs('kandidat.lamaran') || request()->routeIs('kandidat.riwayat') ? 'page' : 'false' }}">
                    Riwayat Lamaran
                </a>
            </div>

            <!-- Right: Notifications & User Profile Dropdown -->
            <div class="flex items-center gap-3">

                <!-- Accessibility Help Button -->
                <a href="{{ route('home') }}#aksesibilitas" 
                   class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-800 hover:bg-blue-100 transition-colors border border-blue-200/60 focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none"
                   title="Panduan Fitur Aksesibilitas"
                   aria-label="Panduan Fitur Aksesibilitas">
                    <svg class="w-3.5 h-3.5 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Standar WCAG 2.1 AA</span>
                </a>

                <!-- Notification Dropdown -->
                <div class="relative" @click.outside="notifOpen = false">
                    <button @click="notifOpen = !notifOpen; profileOpen = false"
                            class="relative p-2 rounded-xl text-slate-600 hover:text-blue-900 hover:bg-slate-100 transition-colors focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none"
                            :aria-expanded="notifOpen.toString()"
                            aria-haspopup="true"
                            aria-label="Pemberitahuan notifikasi baru (2 belum dibaca)">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <!-- Badge Counter -->
                        <span class="absolute top-1 right-1 flex h-4 w-4">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-4 w-4 bg-blue-700 text-[10px] font-bold text-white items-center justify-center">2</span>
                        </span>
                    </button>

                    <!-- Notification Menu Popover -->
                    <div x-show="notifOpen"
                         x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-slate-150 py-3 z-50 overflow-hidden"
                         role="region"
                         aria-label="Daftar notifikasi terbaru">
                        <div class="px-4 pb-2 border-b border-slate-100 flex items-center justify-between">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Notifikasi Terkini</h3>
                            <span class="text-[11px] font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full">2 Baru</span>
                        </div>
                        <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                            <!-- Item 1 -->
                            <a href="{{ route('kandidat.riwayat') }}" class="flex items-start gap-3 p-3.5 hover:bg-slate-50 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="flex-1 text-xs">
                                    <p class="font-semibold text-slate-900 leading-snug">Lamaran Anda Diterima Sistem</p>
                                    <p class="text-slate-500 mt-0.5">Berkas lamaran untuk UI/UX Designer di PT Tokopedia telah berhasil diajukan.</p>
                                    <span class="text-[10px] text-slate-400 mt-1 block">10 menit yang lalu</span>
                                </div>
                            </a>
                            <!-- Item 2 -->
                            <div class="flex items-start gap-3 p-3.5 hover:bg-slate-50 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div class="flex-1 text-xs">
                                    <p class="font-semibold text-slate-900 leading-snug">Rekomendasi Kecocokan Aksesibilitas 95%</p>
                                    <p class="text-slate-500 mt-0.5">Ada lowongan baru di PT Bank Central Asia Tbk yang sesuai kebutuhan akomodasi Anda.</p>
                                    <span class="text-[10px] text-slate-400 mt-1 block">1 jam yang lalu</span>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 pt-2 border-t border-slate-100 text-center">
                            <a href="{{ route('kandidat.riwayat') }}" class="text-xs font-semibold text-blue-700 hover:text-blue-900">
                                Lihat Semua Riwayat Lamaran →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Profile Dropdown -->
                @auth
                    <div class="relative" @click.outside="profileOpen = false">
                        <button @click="profileOpen = !profileOpen; notifOpen = false"
                                class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-slate-100 transition-colors focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none"
                                :aria-expanded="profileOpen.toString()"
                                aria-haspopup="true"
                                aria-label="Menu profil pengguna {{ auth()->user()->name }}">
                            <!-- Avatar Circle with initial -->
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-900 to-blue-700 text-white flex items-center justify-center font-bold text-sm shadow-xs">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="hidden lg:flex flex-col text-left">
                                <span class="text-sm font-bold text-slate-900 leading-tight truncate max-w-[120px]">
                                    {{ auth()->user()->name }}
                                </span>
                                <span class="text-[11px] font-medium text-slate-500 capitalize">
                                    {{ auth()->user()->accessibility_category ? 'Talenta ' . ucfirst(auth()->user()->accessibility_category) : 'Kandidat' }}
                                </span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Profile Dropdown Content -->
                        <div x-show="profileOpen"
                             x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-150 py-2 z-50"
                             role="menu"
                             aria-label="Menu akun pengguna">
                            
                            <div class="px-4 py-3 border-b border-slate-100">
                                <p class="text-xs text-slate-400 font-medium">Masuk sebagai:</p>
                                <p class="text-sm font-bold text-slate-900 truncate">{{ auth()->user()->full_name ?? auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                                @if(auth()->user()->accessibility_category)
                                    <div class="mt-2 inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        <span>♿</span> Preferensi: {{ ucfirst(auth()->user()->accessibility_category) }}
                                    </div>
                                @endif
                            </div>

                            <div class="py-1" role="none">
                                <a href="{{ route('kandidat.profil') }}" 
                                   class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-900 transition-colors"
                                   role="menuitem">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Profil &amp; Preferensi Aksesibilitas
                                </a>
                                <a href="{{ route('kandidat.riwayat') }}" 
                                   class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-900 transition-colors"
                                   role="menuitem">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Riwayat Lamaran Saya
                                </a>
                                <a href="{{ route('kandidat.home') }}?bookmark=true" 
                                   class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-900 transition-colors"
                                   role="menuitem">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                    </svg>
                                    Lowongan Disimpan
                                </a>
                            </div>

                            <div class="border-t border-slate-100 pt-1 mt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors font-medium"
                                            role="menuitem">
                                        <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Keluar Akun
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" 
                           class="text-xs sm:text-sm font-bold text-blue-900 hover:text-blue-800 px-3 py-2 rounded-lg hover:bg-blue-50 transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" 
                           class="text-xs sm:text-sm font-bold bg-blue-900 text-white hover:bg-blue-800 px-3.5 py-2 rounded-xl shadow-xs transition-colors">
                            Daftar Kandidat
                        </a>
                    </div>
                @endauth

                <!-- Mobile Menu Hamburger Button -->
                <button class="md:hidden p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none"
                        @click="mobileOpen = !mobileOpen"
                        :aria-expanded="mobileOpen.toString()"
                        aria-label="Buka menu navigasi mobile">
                    <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

            </div>
        </div>
    </div>

    <!-- Mobile Drawer Menu -->
    <div x-show="mobileOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden border-t border-slate-200/80 bg-white px-4 py-4 space-y-2 shadow-lg"
         role="menu"
         aria-label="Navigasi Menu Mobile">
        <a href="{{ route('kandidat.home') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('kandidat.home') ? 'bg-blue-50 text-blue-900' : 'text-slate-700 hover:bg-slate-50' }}"
           role="menuitem"
           @click="mobileOpen = false">
            <svg class="w-5 h-5 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            Cari Kerja
        </a>
        <a href="{{ route('kandidat.profil') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('kandidat.profil') ? 'bg-blue-50 text-blue-900' : 'text-slate-700 hover:bg-slate-50' }}"
           role="menuitem"
           @click="mobileOpen = false">
            <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Profil Saya
        </a>
        <a href="{{ route('kandidat.lamaran') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold {{ request()->routeIs('kandidat.lamaran') || request()->routeIs('kandidat.riwayat') ? 'bg-blue-50 text-blue-900' : 'text-slate-700 hover:bg-slate-50' }}"
           role="menuitem"
           @click="mobileOpen = false">
            <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Riwayat Lamaran
        </a>

        @auth
            <div class="pt-3 border-t border-slate-100">
                <div class="px-3 py-2">
                    <p class="text-xs text-slate-400">Masuk sebagai</p>
                    <p class="text-sm font-bold text-slate-900">{{ auth()->user()->name }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-bold text-red-600 bg-red-50 hover:bg-red-100 transition-colors">
                        Keluar Akun
                    </button>
                </form>
            </div>
        @else
            <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                <a href="{{ route('login') }}" class="w-full text-center py-2.5 rounded-xl text-sm font-bold border border-blue-900 text-blue-900">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="w-full text-center py-2.5 rounded-xl text-sm font-bold bg-blue-900 text-white">
                    Daftar Kandidat
                </a>
            </div>
        @endauth
    </div>
</nav>
