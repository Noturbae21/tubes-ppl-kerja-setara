@extends('admin.layouts.app')

@section('title', 'Log Aktivitas Sistem & Keamanan')
@section('page_title', 'Log Aktivitas')

@section('content')
<div class="py-6 space-y-6">

    <!-- ══════════════════════════════════════════════════════════════════════════
        1. BREADCRUMB & PAGE HEADER
    ══════════════════════════════════════════════════════════════════════════ -->
    <div>
        <nav class="flex items-center text-xs text-slate-400 font-medium space-x-2 mb-2" aria-label="Breadcrumb">
            <span>Admin</span>
            <span>/</span>
            <span class="text-slate-700 font-semibold">Log Aktivitas</span>
        </nav>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Log Aktivitas Sistem & Keamanan</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Rekam jejak aktivitas user dan sistem untuk keperluan audit keamanan.</p>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
        2. FILTER BAR (Date Range, Level, Search)
    ══════════════════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-5 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <form method="GET" action="{{ route('admin.logs.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <!-- Date Filter Dropdown -->
            <div class="relative">
                <div class="flex items-center gap-2 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <input type="text"
                           name="date_range"
                           value="{{ $dateRange }}"
                           placeholder="Rentang Tanggal (YYYY-MM-DD)"
                           class="bg-transparent border-0 outline-none text-xs text-slate-800 w-44 placeholder-slate-400">
                </div>
            </div>

            <!-- Level Dropdown -->
            <div class="relative">
                <select name="level"
                        onchange="this.form.submit()"
                        class="bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl px-3 py-2 pr-8 outline-none focus:border-blue-600 focus:bg-white cursor-pointer">
                    <option value="all" {{ $levelFilter === 'all' ? 'selected' : '' }}>Semua Level</option>
                    <option value="info" {{ $levelFilter === 'info' ? 'selected' : '' }}>Level: Info</option>
                    <option value="warning" {{ $levelFilter === 'warning' ? 'selected' : '' }}>Level: Warning</option>
                    <option value="error" {{ $levelFilter === 'error' ? 'selected' : '' }}>Level: Error</option>
                </select>
            </div>

            <!-- Role Dropdown -->
            <div class="relative">
                <select name="role"
                        onchange="this.form.submit()"
                        class="bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl px-3 py-2 pr-8 outline-none focus:border-blue-600 focus:bg-white cursor-pointer">
                    <option value="all" {{ $roleFilter === 'all' ? 'selected' : '' }}>Semua Role</option>
                    <option value="admin" {{ $roleFilter === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="perusahaan" {{ $roleFilter === 'perusahaan' ? 'selected' : '' }}>Perusahaan / HRD</option>
                    <option value="kandidat" {{ $roleFilter === 'kandidat' ? 'selected' : '' }}>Kandidat</option>
                    <option value="sistem" {{ $roleFilter === 'sistem' ? 'selected' : '' }}>Sistem</option>
                    <option value="tamu" {{ $roleFilter === 'tamu' ? 'selected' : '' }}>Tamu</option>
                </select>
            </div>

            <!-- Jenis Aktivitas Dropdown -->
            <div class="relative">
                <select name="action"
                        onchange="this.form.submit()"
                        class="bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl px-3 py-2 pr-8 outline-none focus:border-blue-600 focus:bg-white cursor-pointer">
                    <option value="all" {{ $actionFilter === 'all' ? 'selected' : '' }}>Semua Aksi</option>
                    <option value="create" {{ $actionFilter === 'create' ? 'selected' : '' }}>Create (Tambah)</option>
                    <option value="update" {{ $actionFilter === 'update' ? 'selected' : '' }}>Update (Edit)</option>
                    <option value="delete" {{ $actionFilter === 'delete' ? 'selected' : '' }}>Delete (Hapus)</option>
                    <option value="verify" {{ $actionFilter === 'verify' ? 'selected' : '' }}>Verifikasi</option>
                    <option value="reject" {{ $actionFilter === 'reject' ? 'selected' : '' }}>Tolak</option>
                    <option value="revision" {{ $actionFilter === 'revision' ? 'selected' : '' }}>Minta Revisi</option>
                    <option value="login" {{ $actionFilter === 'login' ? 'selected' : '' }}>Login</option>
                    <option value="logout" {{ $actionFilter === 'logout' ? 'selected' : '' }}>Logout</option>
                    <option value="access_denied" {{ $actionFilter === 'access_denied' ? 'selected' : '' }}>Akses Ditolak</option>
                </select>
            </div>

            <!-- Search input -->
            <div class="relative flex-1 min-w-[200px]">
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari aktor, aksi, atau deskripsi..."
                       class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs rounded-xl pl-8 pr-3 py-2 outline-none focus:border-blue-600 focus:bg-white">
                <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                    </svg>
                </span>
            </div>

            <button type="submit"
                    class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-2xs transition-colors cursor-pointer">
                Filter
            </button>

            @if($search || $levelFilter !== 'all' || $roleFilter !== 'all' || $dateRange)
                <a href="{{ route('admin.logs.index') }}"
                   class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition-colors">
                    Reset
                </a>
            @endif
        </form>

        <!-- Level Indicators Pill -->
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 pl-2">
            <span class="text-slate-400">Level:</span>
            <a href="{{ route('admin.logs.index', ['level' => 'info']) }}"
               class="px-2 py-0.5 rounded-md text-[11px] font-bold {{ $levelFilter === 'info' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                Info
            </a>
            <a href="{{ route('admin.logs.index', ['level' => 'warning']) }}"
               class="px-2 py-0.5 rounded-md text-[11px] font-bold {{ $levelFilter === 'warning' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-800 hover:bg-amber-100' }}">
                Warning
            </a>
            <a href="{{ route('admin.logs.index', ['level' => 'error']) }}"
               class="px-2 py-0.5 rounded-md text-[11px] font-bold {{ $levelFilter === 'error' ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}">
                Error
            </a>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
        3. ACTIVITY LOGS TABLE (Matching Screenshot 1)
    ══════════════════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600" aria-label="Tabel Log Aktivitas">
                <thead class="bg-slate-50/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-5 py-3.5">Timestamp / Waktu</th>
                        <th scope="col" class="px-5 py-3.5">User / Aktor</th>
                        <th scope="col" class="px-5 py-3.5">Role</th>
                        <th scope="col" class="px-5 py-3.5">Aktivitas / Aksi yang Dilakukan</th>
                        <th scope="col" class="px-5 py-3.5 text-right">IP Address / Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/50 transition-colors {{ $log->level === 'error' ? 'bg-rose-50/20' : '' }}">
                            <!-- Timestamp -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="font-semibold text-slate-900">
                                    {{ $log->formatted_date }}
                                </div>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    {{ $log->formatted_time }}
                                </div>
                            </td>

                            <!-- User / Aktor -->
                            <td class="px-5 py-4">
                                <div class="font-bold text-slate-900">
                                    {{ $log->actor_name }}
                                </div>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    {{ $log->actor_identifier }}
                                </div>
                            </td>

                            <!-- Role -->
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="text-xs font-semibold text-slate-700">
                                    {{ $log->role_label }}
                                </span>
                            </td>

                            <!-- Aktivitas / Aksi yang Dilakukan -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2 mb-1">
                                    <!-- Action badge -->
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold tracking-tight
                                        {{ strtolower($log->action_type) === 'verify' ? 'bg-emerald-50 text-emerald-700' :
                                           (strtolower($log->action_type) === 'reject' || strtolower($log->action_type) === 'access_denied' ? 'bg-rose-50 text-rose-700' :
                                           (strtolower($log->action_type) === 'login' ? 'bg-emerald-50 text-emerald-700' :
                                           (strtolower($log->action_type) === 'create' ? 'bg-blue-50 text-blue-700' : 'bg-slate-100 text-slate-700'))) }}">
                                        {{ $log->action_label }}
                                    </span>

                                    <!-- Level badge -->
                                    <span class="px-1.5 py-0.2 rounded text-[10px] font-semibold
                                        {{ $log->level === 'error' ? 'text-rose-600 bg-rose-50' : ($log->level === 'warning' ? 'text-amber-700 bg-amber-50' : 'text-slate-400 bg-slate-50') }}">
                                        {{ ucfirst($log->level) }}
                                    </span>
                                </div>
                                <div class="text-xs text-slate-700 font-medium">
                                    {{ $log->description }}
                                </div>
                            </td>

                            <!-- IP Address / Status -->
                            <td class="px-5 py-4 whitespace-nowrap text-right">
                                <div class="font-mono text-[11px] text-slate-600">
                                    {{ $log->ip_address ?? '127.0.0.1' }}
                                </div>
                                <div class="text-[11px] font-bold mt-0.5
                                    {{ $log->result_label === 'Berhasil' ? 'text-emerald-700' : ($log->result_label === 'Ditolak' ? 'text-rose-600' : 'text-rose-600') }}">
                                    {{ $log->result_label }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <div class="max-w-sm mx-auto space-y-2">
                                    <svg class="w-10 h-10 mx-auto text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-xs font-bold text-slate-700">Belum ada riwayat aktivitas yang tercatat.</p>
                                    <p class="text-[11px] text-slate-400">Seluruh aksi pengguna dan sistem akan terekam secara otomatis di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- ══════════════════════════════════════════════════════════════════════
            PAGINATION & FOOTER
        ══════════════════════════════════════════════════════════════════════ -->
        <div class="px-5 py-4 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4 bg-slate-50/50 text-xs text-slate-500">
            <div>
                Menampilkan
                <span class="font-bold text-slate-700">{{ $logs->firstItem() ?? 0 }}</span>
                -
                <span class="font-bold text-slate-700">{{ $logs->lastItem() ?? 0 }}</span>
                dari
                <span class="font-bold text-slate-700">{{ $logs->total() }}</span>
                aktivitas
            </div>

            <div>
                {{ $logs->links() }}
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
        4. BOTTOM AUDIT NOTE
    ══════════════════════════════════════════════════════════════════════════ -->
    <div class="flex items-center gap-2 text-[11px] text-slate-400">
        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
        </svg>
        <span>Data audit terlindungi · Alamat IP dicatat sesuai konfigurasi proksi tepercaya · Semua waktu dalam WIB (UTC+7).</span>
    </div>

</div>
@endsection
