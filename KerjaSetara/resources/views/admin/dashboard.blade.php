@extends('admin.layouts.app')

@section('title', 'Overview & Statistik Platform')
@section('page_title', 'Ringkasan & Statistik Platform')

@section('content')
<div class="space-y-6">

    <!-- Top Metric Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">

        <!-- 1. Total Talenta Terdaftar -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Talenta</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-slate-900">{{ number_format($stats['total_talenta']) }}</span>
                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Aktif Mencari</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2">Pencari kerja disabilitas terdaftar</p>
        </div>

        <!-- 2. Total Perusahaan Inklusif -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Perusahaan / HRD</span>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-slate-900">{{ number_format($stats['total_perusahaan']) }}</span>
                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">{{ $stats['mitra_inklusif'] }} Bersertifikat</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2">Penyedia lowongan ramah disabilitas</p>
        </div>

        <!-- 3. Lowongan Kerja Aktif & Moderasi -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Lowongan Aktif</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-slate-900">{{ number_format($stats['lowongan_aktif']) }}</span>
                @if($stats['lowongan_pending'] > 0)
                    <span class="text-xs font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full">{{ $stats['lowongan_pending'] }} Pending</span>
                @else
                    <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">Semua Terverifikasi</span>
                @endif
            </div>
            <p class="text-[11px] text-slate-400 mt-2">Telah diaudit standar aksesibilitas</p>
        </div>

        <!-- 4. Total Lamaran Masuk -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Lamaran & Interaksi</span>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-slate-900">{{ number_format($stats['total_lamaran']) }}</span>
                <span class="text-xs font-semibold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-full">Kandidat Terhubung</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-2">Total berkas lamaran yang diproses</p>
        </div>

    </div>

    <!-- Quick Action Banner -->
    <div class="bg-gradient-to-r from-blue-900 to-slate-900 rounded-3xl p-6 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-800/60 text-blue-200 text-xs font-semibold mb-2">
                <span>🛡️</span> Mode Pengawasan Administrator Aktif
            </div>
            <h2 class="text-xl font-bold">Pusat Kendali Ekosistem KerjaSetara</h2>
            <p class="text-xs text-slate-300 mt-1 max-w-xl">
                Pantau pendaftaran talenta, moderasi lowongan pekerjaan inklusif, dan pastikan kepatuhan fasilitas aksesibilitas tempat kerja.
            </p>
        </div>
        <div class="flex flex-wrap gap-2.5">
            <a href="{{ route('admin.users') }}" class="px-4 py-2 bg-white text-blue-950 font-bold rounded-xl text-xs hover:bg-slate-100 transition-colors shadow">
                Kelola Pengguna
            </a>
            <a href="{{ route('admin.lowongan') }}" class="px-4 py-2 bg-blue-700 hover:bg-blue-600 text-white font-semibold rounded-xl text-xs transition-colors border border-blue-500/40">
                Moderasi Lowongan
            </a>
            <a href="{{ route('admin.settings') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold rounded-xl text-xs transition-colors border border-slate-700">
                Kategori Aksesibilitas
            </a>
        </div>
    </div>

    <!-- Main Grid: Recent Vacancies & Recent Users -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Left Column: Moderasi Lowongan Terbaru (8 Cols) -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Moderasi Lowongan Kerja Terbaru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar lowongan yang memerlukan verifikasi kelayakan fasilitas aksesibilitas</p>
                </div>
                <a href="{{ route('admin.lowongan') }}" class="text-xs font-semibold text-blue-700 hover:text-blue-900">
                    Lihat Semua Lowongan →
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-semibold uppercase text-[10px]">
                            <th class="pb-3 pl-2">Posisi & Perusahaan</th>
                            <th class="pb-3">Kategori</th>
                            <th class="pb-3">Gaji</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3 text-right pr-2">Aksi Moderasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentVacancies as $vacancy)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3.5 pl-2">
                                    <div class="font-bold text-slate-900 text-sm leading-tight">{{ $vacancy->title }}</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">{{ $vacancy->company->company_name ?? 'Perusahaan' }} • {{ $vacancy->location }}</div>
                                </td>
                                <td class="py-3.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-200/60 capitalize">
                                        {{ $vacancy->category }}
                                    </span>
                                </td>
                                <td class="py-3.5 font-medium text-slate-700">
                                    {{ $vacancy->formatted_salary }}
                                </td>
                                <td class="py-3.5">
                                    @if($vacancy->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 text-right pr-2">
                                    <form method="POST" action="{{ route('admin.lowongan.toggle', $vacancy->id) }}" class="inline">
                                        @csrf
                                        @if($vacancy->is_active)
                                            <button type="submit"
                                                    title="Nonaktifkan Lowongan"
                                                    class="px-2.5 py-1 text-[11px] font-semibold rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200 transition-colors cursor-pointer">
                                                Tangguhkan
                                            </button>
                                        @else
                                            <button type="submit"
                                                    title="Setujui Lowongan"
                                                    class="px-2.5 py-1 text-[11px] font-semibold rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 shadow-xs transition-colors cursor-pointer">
                                                Setujui
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-slate-400">Belum ada lowongan terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Column: Recent Users & Accessibility Distribution (4 Cols) -->
        <div class="lg:col-span-4 space-y-6">

            <!-- Recent Users Widget -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Pengguna Baru</h3>
                        <p class="text-xs text-slate-500">Talenta & HRD mendaftar</p>
                    </div>
                    <a href="{{ route('admin.users') }}" class="text-xs font-semibold text-blue-700 hover:text-blue-900">
                        Lihat Semua →
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($recentUsers as $user)
                        <div class="flex items-center justify-between p-2.5 rounded-2xl bg-slate-50/80 border border-slate-100">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-800 text-xs font-bold flex items-center justify-center flex-shrink-0">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <div class="truncate">
                                    <span class="text-xs font-bold text-slate-900 block truncate">{{ $user->name }}</span>
                                    <span class="text-[10px] text-slate-500 block truncate">{{ $user->email }}</span>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : ($user->role === 'hrd' ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800') }}">
                                {{ $user->role }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-4 text-center">Belum ada pengguna.</p>
                    @endforelse
                </div>
            </div>

            <!-- Accessibility Category Breakdown -->
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
                <h3 class="text-base font-bold text-slate-900 mb-1">Distribusi Aksesibilitas</h3>
                <p class="text-xs text-slate-500 mb-4">Kebutuhan talenta terdaftar</p>

                <div class="space-y-3.5">
                    <div>
                        <div class="flex justify-between text-xs font-medium text-slate-700 mb-1">
                            <span class="flex items-center gap-1.5">♿ Mobilitas Fisik</span>
                            <span class="font-bold">{{ $categoryBreakdown['fisik'] }}</span>
                        </div>
                        <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-600 rounded-full" style="width: {{ $stats['total_talenta'] > 0 ? ($categoryBreakdown['fisik'] / max($stats['total_talenta'], 1)) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-medium text-slate-700 mb-1">
                            <span class="flex items-center gap-1.5">🗣️ Komunikasi Inklusif</span>
                            <span class="font-bold">{{ $categoryBreakdown['komunikasi'] }}</span>
                        </div>
                        <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $stats['total_talenta'] > 0 ? ($categoryBreakdown['komunikasi'] / max($stats['total_talenta'], 1)) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-medium text-slate-700 mb-1">
                            <span class="flex items-center gap-1.5">💻 Dukungan Digital</span>
                            <span class="font-bold">{{ $categoryBreakdown['digital'] }}</span>
                        </div>
                        <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-purple-500 rounded-full" style="width: {{ $stats['total_talenta'] > 0 ? ($categoryBreakdown['digital'] / max($stats['total_talenta'], 1)) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-medium text-slate-700 mb-1">
                            <span class="flex items-center gap-1.5">🧠 Neurodiverse</span>
                            <span class="font-bold">{{ $categoryBreakdown['neurodiverse'] }}</span>
                        </div>
                        <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-500 rounded-full" style="width: {{ $stats['total_talenta'] > 0 ? ($categoryBreakdown['neurodiverse'] / max($stats['total_talenta'], 1)) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
