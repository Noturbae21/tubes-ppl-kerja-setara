@extends('layouts.app')

@section('title', 'Masuk ke Akun — KerjaSetara')
@section('meta_description', 'Masuk ke portal KerjaSetara sebagai Talenta Inklusif, Perusahaan/HRD, atau Administrator.')

@section('content')
<section class="min-h-[calc(100vh-4rem)] flex flex-col justify-center py-8 lg:py-12 bg-slate-50"
         aria-label="Halaman Masuk Multi-Role"
         x-data="{
             activeRole: '{{ old('role', $defaultRole ?? request('role', 'user')) }}',
             email: '{{ old('email', '') }}',
             password: '',
             showPassword: false,

             selectRole(role) {
                 this.activeRole = role;
             },
             fillDemo(role) {
                 this.activeRole = role;
                 if (role === 'user') {
                     this.email = 'nadia@kerjasetara.id';
                     this.password = 'password';
                 } else if (role === 'hrd') {
                     this.email = 'hrd@kerjasetara.id';
                     this.password = 'password';
                 } else if (role === 'admin') {
                     this.email = 'admin@kerjasetara.id';
                     this.password = 'password';
                 }
             }
         }">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100 grid lg:grid-cols-12 min-h-[660px]">

            {{-- ════════════════════════════════════════════════════════════════════
                LEFT PANEL: Visual & Inclusive Branding
            ════════════════════════════════════════════════════════════════════ --}}
            <div class="lg:col-span-5 auth-left-panel p-8 sm:p-10 lg:p-12 flex flex-col justify-between relative overflow-hidden bg-gradient-to-br from-blue-900 via-blue-950 to-slate-900 text-white">
                <!-- Background decoration elements -->
                <div class="absolute -top-16 -left-16 w-48 h-48 bg-blue-500/20 rounded-full blur-2xl pointer-events-none" aria-hidden="true"></div>
                <div class="absolute -bottom-16 -right-16 w-56 h-56 bg-indigo-500/20 rounded-full blur-2xl pointer-events-none" aria-hidden="true"></div>

                <!-- Top Branding -->
                <div class="relative z-10">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 group mb-6" aria-label="Kembali ke Beranda KerjaSetara">
                        <div class="w-9 h-9 bg-white/10 rounded-xl flex items-center justify-center border border-white/20 group-hover:scale-105 transition-transform backdrop-blur-sm">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white tracking-tight">KerjaSetara</span>
                    </a>

                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 text-blue-200 text-xs font-semibold mb-4 border border-white/10 backdrop-blur-sm">
                        <span>✨</span> Platform Karir Inklusif No. 1 di Indonesia
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white leading-snug tracking-tight mb-3">
                        Setiap Karir Berhak Atas Kesempatan yang Setara.
                    </h1>

                    <p class="text-sm text-blue-200/90 leading-relaxed max-w-sm">
                        Satu pintu masuk untuk seluruh ekosistem: pencari kerja bertalenta, mitra perusahaan ramah disabilitas, dan pengelola platform.
                    </p>
                </div>

                <!-- Mid: Illustration / Highlights -->
                <div class="relative z-10 my-6">
                    <div class="relative rounded-2xl overflow-hidden shadow-lg border border-white/10">
                        <img src="{{ asset('images/hero-illustration.png') }}"
                             alt="Suasana kantor yang hangat dan inklusif"
                             class="w-full h-40 object-cover object-center"
                             onerror="this.src='https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=600&q=80'"
                             loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                    </div>
                </div>

                <!-- Bottom Quote Card -->
                <div class="relative z-10 bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/15">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-500/20 border border-blue-400/30 text-blue-300 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">Smart Accessibility Matching</p>
                            <p class="text-[11px] text-blue-200/80">Kalkulasi akomodasi kerja transparan dan ramah disabilitas.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ════════════════════════════════════════════════════════════════════
                RIGHT PANEL: Multi-Role Login Form
            ════════════════════════════════════════════════════════════════════ --}}
            <div class="lg:col-span-7 p-6 sm:p-10 lg:p-12 flex flex-col justify-center">
                <div class="max-w-md w-full mx-auto">

                    <!-- Role Switcher Tabs -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                            Pilih Peran Anda
                        </label>
                        <div class="grid grid-cols-3 gap-1.5 p-1 bg-slate-100 rounded-2xl border border-slate-200" role="tablist" aria-label="Pilihan Peran Pengguna">
                            
                            <!-- Tab: Talenta / Kandidat -->
                            <button type="button"
                                    @click="selectRole('user')"
                                    :class="activeRole === 'user'
                                        ? 'bg-[#1E3A8A] text-white shadow-sm font-semibold'
                                        : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60 font-medium'"
                                    class="flex items-center justify-center gap-1.5 py-2.5 px-2 rounded-xl text-xs transition-all cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600"
                                    role="tab"
                                    :aria-selected="(activeRole === 'user').toString()">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Talenta</span>
                            </button>

                            <!-- Tab: HRD / Perusahaan -->
                            <button type="button"
                                    @click="selectRole('hrd')"
                                    :class="activeRole === 'hrd'
                                        ? 'bg-[#1E3A8A] text-white shadow-sm font-semibold'
                                        : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60 font-medium'"
                                    class="flex items-center justify-center gap-1.5 py-2.5 px-2 rounded-xl text-xs transition-all cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600"
                                    role="tab"
                                    :aria-selected="(activeRole === 'hrd').toString()">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span>HRD / Mitra</span>
                            </button>

                            <!-- Tab: Administrator -->
                            <button type="button"
                                    @click="selectRole('admin')"
                                    :class="activeRole === 'admin'
                                        ? 'bg-[#1E3A8A] text-white shadow-sm font-semibold'
                                        : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60 font-medium'"
                                    class="flex items-center justify-center gap-1.5 py-2.5 px-2 rounded-xl text-xs transition-all cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600"
                                    role="tab"
                                    :aria-selected="(activeRole === 'admin').toString()">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span>Admin</span>
                            </button>

                        </div>
                    </div>

                    <!-- Role Dynamic Header -->
                    <div class="mb-6">
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold"
                                  :class="{
                                      'bg-blue-100 text-blue-900': activeRole === 'user',
                                      'bg-emerald-100 text-emerald-900': activeRole === 'hrd',
                                      'bg-purple-100 text-purple-900': activeRole === 'admin'
                                  }">
                                <span x-show="activeRole === 'user'">Portal Talenta Disabilitas</span>
                                <span x-show="activeRole === 'hrd'">Portal Perusahaan / Rekruter</span>
                                <span x-show="activeRole === 'admin'">Panel Pengawasan Administrator</span>
                            </span>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">
                            Selamat Datang Kembali!
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1" x-show="activeRole === 'user'">
                            Masuk untuk melanjutkan pencarian karir inklusif dan terhubung dengan lowongan kerja impian.
                        </p>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1" x-show="activeRole === 'hrd'">
                            Kelola lowongan ramah disabilitas, tinjau pelamar kerja, dan atur jadwal wawancara inklusif.
                        </p>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1" x-show="activeRole === 'admin'">
                            Masuk dengan kredensial administrator untuk mengelola pengguna, memoderasi lowongan, dan konfigurasi sistem.
                        </p>
                    </div>

                    <!-- Error Alert -->
                    @if ($errors->any())
                        <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs sm:text-sm flex items-start gap-2.5" role="alert" aria-live="assertive">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Flash Message -->
                    @if (session('error'))
                        <div class="mb-5 p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs sm:text-sm flex items-start gap-2.5" role="alert">
                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    <!-- Unified Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <!-- Hidden active role input -->
                        <input type="hidden" name="role" :value="activeRole">

                        <!-- Email Input -->
                        <div>
                            <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                Email / Nama Pengguna
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </div>
                                <input id="email"
                                       name="email"
                                       type="email"
                                       autocomplete="email"
                                       required
                                       x-model="email"
                                       :placeholder="activeRole === 'admin' ? 'admin@kerjasetara.id' : (activeRole === 'hrd' ? 'hrd@perusahaan.com' : 'nama@email.com')"
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-blue-900 transition-colors @error('email') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror">
                            </div>
                            @error('email')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Input with Toggle -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Kata Sandi
                                </label>
                                <a href="#" class="text-xs font-medium text-blue-900 hover:underline">
                                    Lupa sandi?
                                </a>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input id="password"
                                       name="password"
                                       :type="showPassword ? 'text' : 'password'"
                                       autocomplete="current-password"
                                       required
                                       x-model="password"
                                       placeholder="••••••••"
                                       class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-blue-900 transition-colors @error('password') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror">
                                <button type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                                        :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                                    <svg x-show="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input id="remember"
                                       name="remember"
                                       type="checkbox"
                                       class="h-4 w-4 rounded border-slate-300 text-blue-900 focus:ring-blue-800 transition-colors">
                                <span class="text-xs font-medium text-slate-700">Ingat saya di perangkat ini</span>
                            </label>
                        </div>

                        <!-- Dynamic Submit Button -->
                        <button type="submit"
                                class="w-full bg-[#1E3A8A] hover:bg-blue-950 text-white py-3 px-4 rounded-xl text-sm font-semibold shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <span x-show="activeRole === 'user'">Masuk sebagai Pencari Kerja</span>
                            <span x-show="activeRole === 'hrd'">Masuk sebagai HRD / Perusahaan</span>
                            <span x-show="activeRole === 'admin'">Masuk sebagai Administrator</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </form>

                    <!-- Quick Demo Accounts Switcher -->
                    <div class="mt-6 p-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-slate-800">Uji Coba Cepat (Akun Demo):</span>
                            <span class="text-[11px] text-slate-500 font-mono">pass: password</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button"
                                    @click="fillDemo('user')"
                                    class="p-2 rounded-xl border border-slate-200 bg-white hover:bg-blue-50 text-left transition-colors cursor-pointer group">
                                <span class="block text-[10px] font-bold text-blue-900 uppercase">Kandidat</span>
                                <span class="block text-[11px] text-slate-600 truncate font-mono">nadia@kerjasetara.id</span>
                            </button>
                            <button type="button"
                                    @click="fillDemo('hrd')"
                                    class="p-2 rounded-xl border border-slate-200 bg-white hover:bg-emerald-50 text-left transition-colors cursor-pointer group">
                                <span class="block text-[10px] font-bold text-emerald-800 uppercase">HRD</span>
                                <span class="block text-[11px] text-slate-600 truncate font-mono">hrd@kerjasetara.id <span class="text-[9px] text-slate-400 font-normal">(hrd@example.com)</span></span>
                            </button>
                            <button type="button"
                                    @click="fillDemo('admin')"
                                    class="p-2 rounded-xl border border-slate-200 bg-white hover:bg-purple-50 text-left transition-colors cursor-pointer group">
                                <span class="block text-[10px] font-bold text-purple-900 uppercase">Admin</span>
                                <span class="block text-[11px] text-slate-600 truncate font-mono">admin@kerjasetara.id</span>
                            </button>
                        </div>
                    </div>

                    <!-- Direct Dedicated Login Links -->
                    <div class="mt-4 flex items-center justify-between text-[11px] text-slate-500">
                        <span>Akses langsung portal spesifik:</span>
                        <div class="space-x-2">
                            <a href="{{ route('admin.login') }}" class="hover:text-blue-900 underline">Admin Login</a>
                            <span>•</span>
                            <a href="{{ route('hrd.login') }}" class="hover:text-blue-900 underline">HRD Login</a>
                        </div>
                    </div>

                    <!-- Register Link -->
                    <p class="text-center text-xs text-slate-600 mt-5 pt-4 border-t border-slate-100">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="font-bold text-blue-900 hover:text-blue-700 transition-colors underline decoration-blue-300 underline-offset-2">
                            Daftar Sekarang
                        </a>
                    </p>

                </div>
            </div>

        </div>
    </div>
</section>
@endsection
