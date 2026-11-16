<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') — KerjaSetara Management</title>
    <meta name="robots" content="noindex, nofollow">

    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-100" x-data="{ sidebarOpen: false }">

    <div class="min-h-full flex">

        <!-- Mobile Sidebar Backdrop -->
        <div x-show="sidebarOpen"
             x-cloak
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-end="opacity-0">
        </div>

        <!-- ══════════════════════════════════════════════════════════════════════
            SIDEBAR (Desktop & Mobile Drawer)
        ══════════════════════════════════════════════════════════════════════ -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 text-slate-200 transition-transform duration-300 ease-in-out flex flex-col justify-between border-r border-slate-800 shadow-2xl lg:static lg:shadow-none">

            <!-- Sidebar Top Section -->
            <div>
                <!-- Brand Header -->
                <div class="h-20 px-6 flex items-center justify-between border-b border-slate-800">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-blue-700 flex items-center justify-center text-white shadow-lg shadow-blue-900/50 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-base font-bold text-white tracking-tight block">KerjaSetara</span>
                            <span class="text-[10px] font-semibold uppercase tracking-wider px-1.5 py-0.5 rounded bg-blue-900/80 text-blue-300 border border-blue-700/50">Admin Panel</span>
                        </div>
                    </a>

                    <!-- Close button for mobile -->
                    <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1 rounded-lg">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Navigation Section -->
                <nav class="p-4 space-y-1.5" aria-label="Menu Admin">
                    <div class="px-3 pt-3 pb-1 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        WORKSPACE ADMIN
                    </div>

                    <!-- 1. Dashboard -->
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-blue-800 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- 2. Verifikasi Perusahaan -->
                    <a href="{{ route('admin.companies.verification') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.companies*') ? 'bg-blue-800 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.companies*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>Verifikasi Perusahaan</span>
                    </a>

                    <!-- 3. Kelola Data Fasilitas -->
                    <a href="{{ route('admin.facilities.index') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.facilities*') ? 'bg-blue-800 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.facilities*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span>Kelola Data Fasilitas</span>
                    </a>

                    <!-- 4. Kelola User -->
                    <a href="{{ route('admin.users') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.users*') ? 'bg-blue-800 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.users*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Kelola User</span>
                    </a>

                    <!-- 5. Log Aktivitas -->
                    <a href="{{ route('admin.logs.index') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.logs*') ? 'bg-blue-800 text-white shadow-md' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.logs*') ? 'text-white' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <span>Log Aktivitas</span>
                    </a>
                </nav>
            </div>

            <!-- Sidebar Bottom Info Card & Admin Identity -->
            <div class="p-4 space-y-3 border-t border-slate-800">
                <!-- Trust & Legal Card -->
                <div class="rounded-2xl bg-slate-800/60 border border-slate-700/60 p-3.5 text-left">
                    <div class="flex items-start gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-blue-900/60 border border-blue-700/50 flex items-center justify-center flex-shrink-0 text-blue-300">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-white">Kepercayaan dimulai di sini</div>
                            <div class="text-[10px] text-slate-400 leading-tight mt-0.5">Jaga legalitas perusahaan dan kualitas fasilitas inklusif.</div>
                        </div>
                    </div>
                </div>

                <!-- Admin Identity / Role -->
                <div class="bg-slate-800/90 rounded-2xl p-3 flex items-center justify-between border border-slate-700/40">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-xl bg-slate-700 text-slate-300 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div class="truncate">
                            <span class="text-xs font-bold text-white block truncate">Administrator</span>
                            <span class="text-[10px] text-slate-400 truncate block">Akses pengelolaan penuh</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit"
                                title="Keluar Sesi Administrator"
                                class="p-1.5 text-slate-400 hover:text-red-400 hover:bg-slate-900 rounded-lg transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

        </aside>

        <!-- ══════════════════════════════════════════════════════════════════════
            MAIN CONTENT WRAPPER
        ══════════════════════════════════════════════════════════════════════ -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">

            <!-- Topbar Header -->
            <header class="h-20 bg-white border-b border-slate-200/80 px-4 sm:px-6 lg:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs">
                <div class="flex items-center gap-4 flex-1">
                    <!-- Mobile Hamburger -->
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Page Title -->
                    <div class="mr-2">
                        <h1 class="text-xs sm:text-sm font-bold text-slate-900 tracking-tight">@yield('page_title', 'Admin Dashboard')</h1>
                    </div>

                    <!-- Global Top Search Input -->
                    <div class="relative w-full max-w-xs md:max-w-md hidden sm:block">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <circle cx="11" cy="11" r="8" stroke-width="2"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35"/>
                            </svg>
                        </span>
                        <input type="text"
                               placeholder="Cari Perusahaan/Admin..."
                               class="w-full bg-slate-50 border border-slate-200/80 focus:border-blue-600 focus:bg-white text-slate-800 text-xs rounded-xl pl-9 pr-3 py-2 outline-none transition-colors">
                    </div>
                </div>

                <!-- Right Tools: Notifications & Admin Profile -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Bell Icon -->
                    <button type="button" class="p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors relative">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-blue-600"></span>
                    </button>

                    <!-- Admin Profile Card -->
                    <a href="{{ route('admin.profile') }}"
                       title="Buka Profil Administrator"
                       class="flex items-center gap-2.5 pl-2 border-l border-slate-200 hover:opacity-85 transition-opacity group cursor-pointer {{ request()->routeIs('admin.profile*') ? 'opacity-100' : '' }}">
                        @if(auth()->user() && auth()->user()->avatar_url)
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover border border-slate-200">
                        @else
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-900 font-bold flex items-center justify-center text-xs border border-blue-200/60">
                                {{ auth()->user()?->initials ?? 'AD' }}
                            </div>
                        @endif
                        <div class="hidden md:block text-left">
                            <div class="text-[11px] font-bold text-slate-900 leading-tight group-hover:text-blue-700 transition-colors">Admin Profile</div>
                            <div class="text-[10px] text-slate-500 leading-tight truncate max-w-[120px]">{{ auth()->user()->name ?? 'Administrator' }}</div>
                        </div>
                        <svg class="w-3.5 h-3.5 text-slate-400 hidden md:block group-hover:text-slate-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </a>
                </div>
            </header>

            <!-- Alerts & Flash Messages -->
            <div class="px-4 sm:px-6 lg:px-8 pt-6">
                @if (session('success'))
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center justify-between shadow-xs mb-4">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-medium flex items-center justify-between shadow-xs mb-4">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Page Specific Content -->
            <main class="flex-1 px-4 sm:px-6 lg:px-8 pb-12">
                @yield('content')
            </main>

        </div>

    </div>

</body>
</html>
