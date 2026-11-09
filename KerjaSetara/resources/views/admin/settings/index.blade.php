@extends('admin.layouts.app')

@section('title', 'Kategori Aksesibilitas & Pengaturan')
@section('page_title', 'Taksonomi Aksesibilitas & Konfigurasi Sistem')

@section('content')
<div class="space-y-6">

    <!-- Header Description -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-slate-900">Standar Taksonomi Aksesibilitas KerjaSetara</h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Kategori ini menjadi parameter utama algoritma Smart Matching dan verifikasi audit fisik perusahaan inklusif.
            </p>
        </div>
        <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold">
            <span>✓</span> Kepatuhan Standar WCAG 2.1 & UU Penyandang Disabilitas
        </div>
    </div>

    <!-- 4 Accessibility Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($categories as $key => $category)
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs hover:shadow-md transition-all">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-2xl shadow-xs">
                            {{ $category['icon'] }}
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">{{ $category['name'] }}</h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-blue-50 text-blue-800 border border-blue-200/60 mt-1">
                                Kode: {{ $key }}
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-slate-900">{{ $category['total_jobs'] }}</span>
                        <span class="text-[10px] text-slate-400 block">Lowongan Aktif</span>
                    </div>
                </div>

                <p class="text-xs text-slate-600 leading-relaxed mb-4">
                    {{ $category['description'] }}
                </p>

                <!-- Tag Items Included -->
                <div class="pt-4 border-t border-slate-100">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Fitur Terdaftar:</span>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($category['tags'] as $tag)
                            <span class="px-2.5 py-1 rounded-xl bg-slate-100 text-slate-700 text-xs font-medium">
                                {{ ucwords(str_replace('_', ' ', $tag)) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- System & Security Environment Card -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-bold text-slate-900">Status Lingkungan Sistem & Keamanan</h3>
                <p class="text-xs text-slate-500">Konfigurasi runtime backend dan protokol otentikasi multi-role</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-800 border border-blue-200 text-xs font-bold">
                Platform Normal & Aman
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] text-slate-400 font-semibold block uppercase">Laravel Version</span>
                <span class="text-xs font-bold text-slate-900 mt-0.5 block font-mono">v{{ $systemInfo['laravel_version'] }}</span>
            </div>
            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] text-slate-400 font-semibold block uppercase">PHP Version</span>
                <span class="text-xs font-bold text-slate-900 mt-0.5 block font-mono">PHP {{ $systemInfo['php_version'] }}</span>
            </div>
            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] text-slate-400 font-semibold block uppercase">Database Driver</span>
                <span class="text-xs font-bold text-slate-900 mt-0.5 block capitalize">{{ $systemInfo['database_driver'] }} (MySQL)</span>
            </div>
            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] text-slate-400 font-semibold block uppercase">Environment</span>
                <span class="text-xs font-bold text-slate-900 mt-0.5 block capitalize">{{ $systemInfo['app_environment'] }}</span>
            </div>
            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] text-slate-400 font-semibold block uppercase">Admin Throttle</span>
                <span class="text-xs font-bold text-emerald-700 mt-0.5 block">5 attempts / 60s</span>
            </div>
            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="text-[10px] text-slate-400 font-semibold block uppercase">Zona Waktu</span>
                <span class="text-xs font-bold text-slate-900 mt-0.5 block font-mono">{{ $systemInfo['timezone'] }}</span>
            </div>
        </div>
    </div>

</div>
@endsection
