@extends('layouts.app')

@section('title', 'Masuk — KerjaSetara')
@section('meta_description', 'Masuk ke akun KerjaSetara Anda untuk melanjutkan pencarian karir inklusif.')

@section('content')
<section class="min-h-[calc(100vh-4rem)] flex flex-col justify-center py-8 lg:py-12 bg-slate-50" aria-label="Halaman Masuk">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100 grid lg:grid-cols-12 min-h-[620px]">

            {{-- ════════════════════════════════════════════════════════════════════
                LEFT PANEL: Visual & Branding
            ════════════════════════════════════════════════════════════════════ --}}
            <div class="lg:col-span-5 auth-left-panel p-8 sm:p-10 lg:p-12 flex flex-col justify-between relative overflow-hidden">
                <!-- Background decoration elements -->
                <div class="absolute -top-16 -left-16 w-48 h-48 bg-blue-200/50 rounded-full blur-2xl pointer-events-none" aria-hidden="true"></div>
                <div class="absolute -bottom-16 -right-16 w-56 h-56 bg-indigo-200/40 rounded-full blur-2xl pointer-events-none" aria-hidden="true"></div>

                <!-- Top Branding -->
                <div class="relative z-10">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 group mb-8" aria-label="Kembali ke Beranda KerjaSetara">
                        <div class="w-9 h-9 bg-blue-900 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-blue-900 tracking-tight">KerjaSetara</span>
                    </a>

                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-100/80 text-blue-800 text-xs font-semibold mb-4">
                        <span>✨</span> Ruang untuk setiap potensi
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-snug tracking-tight mb-4">
                        Setiap Karir Berhak Atas Kesempatan yang Setara.
                    </h1>

                    <p class="text-sm text-slate-600 leading-relaxed max-w-sm">
                        Kami menjembatani impian profesional Anda dengan ruang kerja yang ramah, menghargai, dan siap mendukung setiap kebutuhan Anda.
                    </p>
                </div>

                <!-- Mid: Illustration / Image -->
                <div class="relative z-10 my-6">
                    <div class="relative rounded-2xl overflow-hidden shadow-lg border border-white/60">
                        <img src="{{ asset('images/hero-illustration.png') }}"
                             alt="Suasana kantor yang hangat dan inklusif"
                             class="w-full h-44 object-cover object-center"
                             loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-blue-950/30 to-transparent"></div>
                    </div>
                </div>

                <!-- Bottom Quote Card -->
                <div class="relative z-10 bg-white/90 backdrop-blur-md rounded-2xl p-4 shadow-sm border border-white/80">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-900">Potensi tanpa batas.</p>
                            <p class="text-xs text-slate-600">Tempat kerja yang memahami dan merangkul Anda.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ════════════════════════════════════════════════════════════════════
                RIGHT PANEL: Login Form
            ════════════════════════════════════════════════════════════════════ --}}
            <div class="lg:col-span-7 p-8 sm:p-10 lg:p-14 flex flex-col justify-center">
                <div class="max-w-md w-full mx-auto">

                    <!-- Form Header -->
                    <div class="mb-8">
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight mb-2">
                            Selamat Datang Kembali!
                        </h2>
                        <p class="text-sm text-slate-500">
                            Masuk untuk melanjutkan pencarian karir inklusif dan terhubung dengan peluang terbaik Anda.
                        </p>
                    </div>

                    <!-- Error Alert -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm flex items-start gap-3" role="alert" aria-live="assertive">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ showPassword: false }">
                        @csrf

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                                Email / Nama Pengguna
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                    </svg>
                                </span>
                                <input id="email"
                                       name="email"
                                       type="email"
                                       autocomplete="email"
                                       required
                                       value="{{ old('email') }}"
                                       placeholder="nama@email.com"
                                       class="input-field pl-10 @error('email') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror"
                                       aria-describedby="email-error">
                            </div>
                            @error('email')
                                <p id="email-error" class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                                    Kata Sandi
                                </label>
                                <a href="#" class="text-xs font-medium text-blue-700 hover:text-blue-900 transition-colors">
                                    Lupa kata sandi?
                                </a>
                            </div>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </span>
                                <input id="password"
                                       name="password"
                                       :type="showPassword ? 'text' : 'password'"
                                       autocomplete="current-password"
                                       required
                                       placeholder="••••••••"
                                       class="input-field pl-10 pr-10 @error('password') border-red-500 focus:border-red-500 focus:ring-red-200 @enderror">
                                <button type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                                        :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                                    <!-- Eye open -->
                                    <svg x-show="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <!-- Eye slash -->
                                    <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center">
                            <input id="remember"
                                   name="remember"
                                   type="checkbox"
                                   class="h-4 w-4 rounded border-slate-300 text-blue-900 focus:ring-blue-600 transition-colors">
                            <label for="remember" class="ml-2.5 block text-xs font-medium text-slate-700 select-none">
                                Ingat saya di perangkat ini
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                                class="w-full btn-primary py-3 justify-center text-sm font-semibold rounded-xl shadow-md hover:shadow-lg transition-all">
                            Masuk
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="my-6 divider-text">
                        <span>Atau masuk dengan</span>
                    </div>

                    <!-- Social Login (Google) -->
                    <button type="button"
                            class="w-full flex items-center justify-center gap-3 py-2.5 px-4 border border-slate-200 rounded-xl bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-sm transition-all hover:border-slate-300">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                        Masuk dengan Google
                    </button>

                    <!-- Quick Demo Accounts -->
                    <div class="mt-6 p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                        <p class="font-bold text-slate-700 mb-1.5">Akun Demo Cepat (Password: <code>password</code>):</p>
                        <div class="grid grid-cols-2 gap-2 text-[11px] text-slate-600">
                            <div>• HRD: <span class="font-mono text-blue-800">hrd@example.com</span></div>
                            <div>• Kandidat: <span class="font-mono text-blue-800">kandidat@example.com</span></div>
                            <div>• Admin: <span class="font-mono text-blue-800">admin@kerjasetara.id</span></div>
                            <div>• Kandidat 2: <span class="font-mono text-blue-800">mario@example.com</span></div>
                        </div>
                    </div>

                    <!-- Register Link -->
                    <p class="text-center text-xs text-slate-600 mt-6">
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
