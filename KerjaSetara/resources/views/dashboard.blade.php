@extends('layouts.app')

@section('title', 'Dashboard — KerjaSetara')

@section('content')
<section class="py-10 bg-slate-50 min-h-[calc(100vh-4rem)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-blue-900 to-blue-800 rounded-3xl p-8 sm:p-10 text-white shadow-xl mb-8 relative overflow-hidden">
            <div class="relative z-10 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-700/60 text-blue-100 text-xs font-semibold mb-4">
                    <span>✨</span> Portal Karir Inklusif
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold mb-3">
                    Halo, {{ $user->name }}! 👋
                </h1>
                <p class="text-blue-100 text-base leading-relaxed mb-6">
                    Selamat datang di panel kendali KerjaSetara. Akun Anda terdaftar sebagai
                    <strong class="text-white capitalize">{{ $user->role === 'hrd' ? 'Perusahaan / HRD' : ($user->role === 'kandidat' ? 'Pencari Kerja (Kandidat)' : 'Administrator') }}</strong>.
                </p>

                <div class="flex flex-wrap gap-3">
                    @if($user->role === 'kandidat')
                        <a href="{{ route('home') }}#lowongan" class="bg-white text-blue-900 font-bold px-5 py-2.5 rounded-xl hover:bg-blue-50 text-sm shadow transition-all">
                            Jelajahi Lowongan Kerja
                        </a>
                    @else
                        <a href="#" class="bg-white text-blue-900 font-bold px-5 py-2.5 rounded-xl hover:bg-blue-50 text-sm shadow transition-all">
                            + Pasang Lowongan Baru
                        </a>
                    @endif
                    <a href="{{ route('home') }}#aksesibilitas" class="border border-white/40 text-white font-medium px-5 py-2.5 rounded-xl hover:bg-white/10 text-sm transition-all">
                        Pengaturan Aksesibilitas
                    </a>
                </div>
            </div>

            <!-- Decorative backdrop -->
            <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-blue-700/30 rounded-full blur-3xl pointer-events-none"></div>
        </div>

        <!-- Dashboard Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Profile Summary Card -->
            <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                <h2 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">Profil Anda</h2>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-xs text-slate-400 block font-medium">Nama:</span>
                        <span class="font-semibold text-slate-800">{{ $user->full_name ?? $user->name }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 block font-medium">Email:</span>
                        <span class="text-slate-800">{{ $user->email }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 block font-medium">Peran:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 uppercase">
                            {{ $user->role }}
                        </span>
                    </div>
                    @if($user->accessibility_category)
                        <div>
                            <span class="text-xs text-slate-400 block font-medium">Preferensi Aksesibilitas:</span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 capitalize">
                                ✓ {{ $user->accessibility_category }}
                            </span>
                        </div>
                    @endif
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-center py-2.5 px-4 text-xs font-semibold text-red-600 hover:text-red-700 hover:bg-red-50 rounded-xl transition-colors border border-red-200">
                            Keluar dari Akun
                        </button>
                    </form>
                </div>
            </div>

            <!-- Quick Action / Status Cards -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Stats summary row -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                        <span class="text-xs text-slate-500 font-medium block">Lamaran Aktif</span>
                        <span class="text-2xl font-bold text-blue-900 mt-1 block">0</span>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                        <span class="text-xs text-slate-500 font-medium block">Panggilan Interview</span>
                        <span class="text-2xl font-bold text-green-600 mt-1 block">0</span>
                    </div>
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm col-span-2 sm:col-span-1">
                        <span class="text-xs text-slate-500 font-medium block">Kecocokan Aksesibilitas</span>
                        <span class="text-2xl font-bold text-indigo-600 mt-1 block">100%</span>
                    </div>
                </div>

                <!-- Guidance card -->
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                    <h3 class="text-base font-bold text-slate-900 mb-2">Panduan Pengguna Baru</h3>
                    <p class="text-sm text-slate-600 leading-relaxed mb-4">
                        Platform KerjaSetara dirancang dengan standar WCAG 2.1 AA agar ramah pembaca layar (screen reader), navigasi keyboard penuh, dan kontras tinggi. Anda dapat langsung mencari lowongan yang sesuai dengan preferensi aksesibilitas Anda di beranda.
                    </p>
                    <a href="{{ route('home') }}" class="btn-primary text-xs py-2 px-4">
                        Kembali ke Beranda
                    </a>
                </div>

            </div>

        </div>

    </div>
</section>
@endsection
