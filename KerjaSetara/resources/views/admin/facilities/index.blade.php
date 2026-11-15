@extends('admin.layouts.app')

@section('title', 'Manajemen Master Data Fasilitas Aksesibilitas')
@section('page_title', 'Kelola Master Data Fasilitas Aksesibilitas')

@section('content')
<div x-data="facilityManagement()" class="space-y-6">

    <!-- ══════════════════════════════════════════════════════════════════════
        HEADER & ACTION
    ══════════════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h2 class="text-lg font-bold text-slate-900">Manajemen Master Data Fasilitas Aksesibilitas</h2>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200/60">
                    WCAG 2.1 Compliant
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Kelola taksonomi fasilitas ramah disabilitas, validasi keamanan aset SVG, dan pemantauan relasi lowongan kerja.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button"
                    @click="openCreateModal()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#193a7c] hover:bg-[#132e63] text-white text-xs font-bold transition-all shadow-sm hover:shadow-md cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Fasilitas</span>
            </button>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════
        FLASH NOTIFICATIONS
    ══════════════════════════════════════════════════════════════════════ -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 flex items-start gap-3 shadow-xs">
            <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="flex-1">
                <div class="text-xs font-bold text-emerald-900">Berhasil</div>
                <div class="text-xs text-emerald-700 mt-0.5">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200/80 flex items-start gap-3 shadow-xs">
            <div class="w-7 h-7 rounded-lg bg-red-100 text-red-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <div class="flex-1">
                <div class="text-xs font-bold text-red-900">Perhatian / Aksi Ditolak</div>
                <div class="text-xs text-red-700 mt-0.5">{{ session('error') }}</div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200/80 flex items-start gap-3 shadow-xs">
            <div class="w-7 h-7 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="flex-1">
                <div class="text-xs font-bold text-amber-900">Terdapat kesalahan validasi formulir:</div>
                <ul class="list-disc list-inside text-xs text-amber-800 mt-1 space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- ══════════════════════════════════════════════════════════════════════
        RINGKASAN METRIK MASTER DATA (DATABASE SUMMARY)
    ══════════════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Total Fasilitas -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Total Fasilitas</span>
                <span class="text-xl font-bold text-slate-900">{{ $summary['total'] }}</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">Standar taksonomi</span>
            </div>
        </div>

        <!-- 2. Fasilitas Aktif -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Fasilitas Aktif</span>
                <span class="text-xl font-bold text-emerald-600">{{ $summary['active'] }}</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">Tersedia di lowongan baru</span>
            </div>
        </div>

        <!-- 3. Fasilitas Nonaktif -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
            <div>
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Fasilitas Nonaktif</span>
                <span class="text-xl font-bold text-slate-600">{{ $summary['inactive'] }}</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">Terkunci / diarsipkan</span>
            </div>
        </div>

        <!-- 4. Total Digunakan di Lowongan -->
        <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Terpasang di Lowongan</span>
                <span class="text-xl font-bold text-purple-700">{{ $summary['total_placements'] }}</span>
                <span class="text-[10px] text-slate-400 block mt-0.5">{{ $summary['total_in_use'] }} master digunakan</span>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════
        FILTER & SEARCH TOOLBAR
    ══════════════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-3xl p-4 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
        <!-- Status Filter Tabs -->
        <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-2xl">
            <a href="{{ route('admin.facilities.index', ['status' => 'all', 'search' => $search]) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ $statusFilter === 'all' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-900' }}">
                Semua ({{ $summary['total'] }})
            </a>
            <a href="{{ route('admin.facilities.index', ['status' => 'active', 'search' => $search]) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ $statusFilter === 'active' ? 'bg-white text-emerald-800 shadow-xs' : 'text-slate-500 hover:text-emerald-700' }}">
                Aktif ({{ $summary['active'] }})
            </a>
            <a href="{{ route('admin.facilities.index', ['status' => 'inactive', 'search' => $search]) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all {{ $statusFilter === 'inactive' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500 hover:text-slate-900' }}">
                Nonaktif ({{ $summary['inactive'] }})
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.facilities.index') }}" class="flex items-center gap-2 flex-1 sm:max-w-md">
            <input type="hidden" name="status" value="{{ $statusFilter }}">
            <div class="relative w-full">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari nama fasilitas, deskripsi, atau ID (FAS-001)..."
                       class="w-full pl-10 pr-10 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition-all">
                @if($search)
                    <a href="{{ route('admin.facilities.index', ['status' => $statusFilter]) }}"
                       title="Hapus pencarian"
                       class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                @endif
            </div>
            <button type="submit"
                    class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition-colors">
                Cari
            </button>
        </form>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════
        TABEL DATA FASILITAS
    ══════════════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-3.5">ID</th>
                        <th class="px-3 py-3.5">Ikon SVG</th>
                        <th class="px-5 py-3.5">Nama Fasilitas</th>
                        <th class="px-5 py-3.5">Deskripsi Singkat</th>
                        <th class="px-5 py-3.5 text-center">Penggunaan Lowongan</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($facilities as $facility)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- 1. ID -->
                            <td class="px-5 py-4 font-mono font-bold text-slate-700 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-800 border border-slate-200/70 text-[11px]">
                                    {{ $facility->identifier }}
                                </span>
                            </td>

                            <!-- 2. Ikon SVG -->
                            <td class="px-3 py-4 whitespace-nowrap">
                                <div class="w-10 h-10 rounded-xl bg-blue-50/80 border border-blue-100 flex items-center justify-center text-blue-700 shadow-2xs overflow-hidden p-2"
                                     title="Ikon SVG: {{ $facility->name }}">
                                    {!! $facility->svg_content !!}
                                </div>
                            </td>

                            <!-- 3. Nama Fasilitas -->
                            <td class="px-5 py-4 font-bold text-slate-900 whitespace-nowrap">
                                <span>{{ $facility->name }}</span>
                            </td>

                            <!-- 4. Deskripsi Singkat -->
                            <td class="px-5 py-4 text-slate-600 max-w-sm">
                                <p class="line-clamp-2 leading-relaxed" title="{{ $facility->description }}">
                                    {{ $facility->description }}
                                </p>
                            </td>

                            <!-- 5. Jumlah Lowongan yang Menggunakan Fasilitas -->
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                @if($facility->job_vacancies_count > 0)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-800 border border-blue-200/80">
                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $facility->job_vacancies_count }} lowongan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-500 border border-slate-200/60">
                                        0 lowongan
                                    </span>
                                @endif
                            </td>

                            <!-- 6. Status Aktif/Nonaktif -->
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.facilities.toggle-status', $facility) }}" class="inline-flex items-center">
                                    @csrf
                                    <button type="submit"
                                            title="Klik untuk {{ $facility->is_active ? 'menonaktifkan' : 'mengaktifkan' }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border transition-all cursor-pointer {{ $facility->is_active ? 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border-slate-300 hover:bg-slate-200' }}">
                                        <span class="w-2 h-2 rounded-full {{ $facility->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        <span>{{ $facility->status_label }}</span>
                                    </button>
                                </form>
                            </td>

                            <!-- 7. Tindakan Edit & Hapus -->
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <!-- Edit Button -->
                                    <button type="button"
                                            @click="openEditModal({{ $facility->id }})"
                                            title="Edit Fasilitas"
                                            class="p-2 rounded-xl text-slate-600 hover:text-blue-800 hover:bg-blue-50 transition-colors cursor-pointer border border-slate-200 hover:border-blue-200">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <!-- Delete Button -->
                                    @if($facility->job_vacancies_count > 0)
                                        <!-- Still In Use: Shows Blocked Notice Modal directing Admin to deactivate -->
                                        <button type="button"
                                                @click="showBlockedNotice({{ $facility->id }}, '{{ addslashes($facility->name) }}', '{{ $facility->identifier }}', {{ $facility->job_vacancies_count }})"
                                                title="Fasilitas masih digunakan oleh {{ $facility->job_vacancies_count }} lowongan (Tidak dapat dihapus)"
                                                class="p-2 rounded-xl text-slate-400 hover:text-amber-700 hover:bg-amber-50 transition-colors cursor-pointer border border-slate-200 hover:border-amber-200">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @else
                                        <!-- Zero Usage: Allowed to delete with confirmation -->
                                        <button type="button"
                                                @click="confirmDelete({{ $facility->id }}, '{{ addslashes($facility->name) }}', '{{ $facility->identifier }}')"
                                                title="Hapus Fasilitas (Penggunaan nol)"
                                                class="p-2 rounded-xl text-slate-600 hover:text-red-700 hover:bg-red-50 transition-colors cursor-pointer border border-slate-200 hover:border-red-200">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                                <div class="max-w-xs mx-auto space-y-2">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto text-slate-400">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="font-bold text-slate-700 text-sm">Tidak Ada Fasilitas Ditemukan</div>
                                    <p class="text-xs text-slate-400">
                                        @if($search)
                                            Tidak ditemukan fasilitas dengan kata kunci "{{ $search }}".
                                        @else
                                            Belum ada data fasilitas aksesibilitas yang terdaftar.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($facilities->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <span class="text-xs text-slate-500">
                    Menampilkan {{ $facilities->firstItem() }} – {{ $facilities->lastItem() }} dari {{ $facilities->total() }} fasilitas
                </span>
                <div>
                    {{ $facilities->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════
        MODAL 1: TAMBAH FASILITAS BARU
    ══════════════════════════════════════════════════════════════════════ -->
    <div x-show="showCreateModal"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <!-- Backdrop -->
            <div x-show="showCreateModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="showCreateModal = false"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

            <!-- Modal Panel -->
            <div x-show="showCreateModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full border border-slate-100">

                <form method="POST" action="{{ route('admin.facilities.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Modal Header -->
                    <div class="px-6 pt-6 pb-4 flex items-center justify-between border-b border-slate-100">
                        <div>
                            <h3 class="text-base font-bold text-slate-900" id="modal-title">Tambah Fasilitas Baru</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Tambah master fasilitas aksesibilitas baru ke sistem</p>
                        </div>
                        <button type="button"
                                @click="showCreateModal = false"
                                class="w-8 h-8 rounded-xl border border-slate-200 text-slate-400 hover:text-slate-600 hover:bg-slate-50 flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-5 space-y-4">
                        <!-- 1. Nama Fasilitas -->
                        <div>
                            <label class="block text-xs font-bold text-slate-900 mb-1.5">
                                Nama Fasilitas <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   required
                                   placeholder="Contoh: Akses Kursi Roda"
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        </div>

                        <!-- 2. Upload SVG Ikon -->
                        <div>
                            <label class="block text-xs font-bold text-slate-900 mb-1.5">
                                Upload SVG Ikon <span class="text-red-500">*</span>
                            </label>

                            <!-- Drag-and-drop / Preview Box -->
                            <div class="border border-dashed border-slate-300 rounded-2xl p-4 bg-slate-50/50 transition-all hover:border-blue-400"
                                 @dragover.prevent="$el.classList.add('border-blue-500', 'bg-blue-50/30')"
                                 @dragleave.prevent="$el.classList.remove('border-blue-500', 'bg-blue-50/30')"
                                 @drop.prevent="handleCreateFileDrop($event)">

                                <input type="file"
                                       name="icon"
                                       id="create_svg_input"
                                       accept=".svg,image/svg+xml"
                                       required
                                       @change="handleCreateFileSelect($event)"
                                       class="sr-only">

                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <!-- SVG Preview Box -->
                                        <div class="w-14 h-14 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-700 flex-shrink-0 p-2.5 overflow-hidden shadow-2xs">
                                            <template x-if="createSvgPreview">
                                                <div class="w-full h-full flex items-center justify-center" x-html="createSvgPreview"></div>
                                            </template>
                                            <template x-if="!createSvgPreview">
                                                <svg class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </template>
                                        </div>

                                        <!-- Filename & Meta Info -->
                                        <div>
                                            <span class="text-xs font-bold text-slate-800 block truncate max-w-[170px]"
                                                  x-text="createFileName || 'Pilih atau seret berkas SVG'"></span>
                                            <span class="text-[11px] text-slate-400 block mt-0.5"
                                                  x-text="createFileMeta || 'SVG · Maks. 100 KB'"></span>
                                            <template x-if="createFileValid">
                                                <span class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1 mt-0.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    Ikon siap digunakan
                                                </span>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Upload Button -->
                                    <button type="button"
                                            @click="document.getElementById('create_svg_input').click()"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-slate-200 hover:border-slate-300 text-blue-900 rounded-xl text-xs font-bold shadow-2xs transition-all cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                        </svg>
                                        <span>Unggah SVG</span>
                                    </button>
                                </div>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1.5">
                                Tarik file ke area ini atau pilih file. SVG saja, maks. 100 KB; tanpa skrip.
                            </p>
                        </div>

                        <!-- 3. Deskripsi -->
                        <div>
                            <label class="block text-xs font-bold text-slate-900 mb-1.5">
                                Deskripsi <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description"
                                      rows="3"
                                      required
                                      placeholder="Ramp, lift, dan jalur yang dapat diakses kursi roda."
                                      class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"></textarea>
                            <p class="text-[10px] text-slate-400 mt-1">
                                Gunakan deskripsi singkat dan jelas untuk employer dan kandidat.
                            </p>
                        </div>

                        <!-- 4. Status Fasilitas -->
                        <div class="flex items-center justify-between pt-1">
                            <div>
                                <span class="text-xs font-bold text-slate-900 block">Status fasilitas</span>
                                <span class="text-[10px] text-slate-400">Tersedia untuk dipilih di lowongan baru.</span>
                            </div>

                            <label class="relative inline-flex items-center cursor-pointer gap-2">
                                <input type="checkbox"
                                       name="is_active"
                                       value="1"
                                       x-model="createIsActive"
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                <span class="text-xs font-semibold"
                                      :class="createIsActive ? 'text-emerald-700' : 'text-slate-400'"
                                      x-text="createIsActive ? 'Aktif' : 'Nonaktif'"></span>
                            </label>
                        </div>

                        <!-- Info Banner -->
                        <div class="bg-blue-50/80 border border-blue-100 rounded-2xl p-3 flex items-start gap-2.5 text-blue-900 text-[11px] leading-relaxed">
                            <svg class="w-4 h-4 text-blue-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>
                                Master data fasilitas baru akan langsung tersedia pada formulir posting lowongan perusahaan.
                            </span>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">* Wajib diisi</span>
                        <div class="flex items-center gap-2.5">
                            <button type="button"
                                    @click="showCreateModal = false"
                                    class="px-4 py-2 border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 rounded-xl text-xs font-bold transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                    class="px-5 py-2 bg-[#193a7c] hover:bg-[#132e63] text-white rounded-xl text-xs font-bold shadow-xs transition-all">
                                Simpan Fasilitas
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════
        MODAL 2: EDIT FASILITAS (EXACT MATCH USER DESIGN MOCKUP)
    ══════════════════════════════════════════════════════════════════════ -->
    <div x-show="showEditModal"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="edit-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <!-- Backdrop -->
            <div x-show="showEditModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="showEditModal = false"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

            <!-- Modal Panel -->
            <div x-show="showEditModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full border border-slate-100">

                <form method="POST" :action="'{{ url('admin/facilities') }}/' + editData.id" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Modal Header -->
                    <div class="px-6 pt-6 pb-4 flex items-center justify-between border-b border-slate-100">
                        <div>
                            <h3 class="text-base font-bold text-slate-900" id="edit-modal-title">Edit Fasilitas</h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Perbarui master fasilitas · <span class="font-mono font-bold text-slate-700" x-text="editData.identifier">FAS-001</span>
                            </p>
                        </div>
                        <button type="button"
                                @click="showEditModal = false"
                                class="w-8 h-8 rounded-xl border border-slate-200 text-slate-400 hover:text-slate-600 hover:bg-slate-50 flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-5 space-y-4">
                        <!-- 1. Nama Fasilitas * -->
                        <div>
                            <label class="block text-xs font-bold text-slate-900 mb-1.5">
                                Nama Fasilitas <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   x-model="editData.name"
                                   required
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                        </div>

                        <!-- 2. Upload SVG Ikon * -->
                        <div>
                            <label class="block text-xs font-bold text-slate-900 mb-1.5">
                                Upload SVG Ikon <span class="text-red-500">*</span>
                            </label>

                            <!-- Dotted SVG Box Container -->
                            <div class="border border-dashed border-slate-300 rounded-2xl p-4 bg-slate-50/50 transition-all hover:border-blue-400"
                                 @dragover.prevent="$el.classList.add('border-blue-500', 'bg-blue-50/30')"
                                 @dragleave.prevent="$el.classList.remove('border-blue-500', 'bg-blue-50/30')"
                                 @drop.prevent="handleEditFileDrop($event)">

                                <input type="file"
                                       name="icon"
                                       id="edit_svg_input"
                                       accept=".svg,image/svg+xml"
                                       @change="handleEditFileSelect($event)"
                                       class="sr-only">

                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <!-- SVG Preview Box (Light Blue) -->
                                        <div class="w-14 h-14 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-700 flex-shrink-0 p-2.5 overflow-hidden shadow-2xs">
                                            <div class="w-full h-full flex items-center justify-center" x-html="editSvgPreview || editData.svg_content"></div>
                                        </div>

                                        <!-- Filename & Metadata -->
                                        <div>
                                            <span class="text-xs font-bold text-slate-800 block truncate max-w-[170px]"
                                                  x-text="editFileName || editData.icon_filename"></span>
                                            <span class="text-[11px] text-slate-400 block mt-0.5"
                                                  x-text="'SVG · ' + (editFileSize || editData.icon_size) + ' · ' + editData.dimensions"></span>
                                            <span class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1 mt-0.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Ikon siap digunakan
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Ganti SVG Button -->
                                    <button type="button"
                                            @click="document.getElementById('edit_svg_input').click()"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-slate-200 hover:border-slate-300 text-blue-900 rounded-xl text-xs font-bold shadow-2xs transition-all cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                        </svg>
                                        <span>Ganti SVG</span>
                                    </button>
                                </div>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1.5">
                                Tarik file ke area ini atau pilih file. SVG saja, maks. 100 KB; tanpa skrip.
                            </p>
                        </div>

                        <!-- 3. Deskripsi * -->
                        <div>
                            <label class="block text-xs font-bold text-slate-900 mb-1.5">
                                Deskripsi <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description"
                                      rows="3"
                                      x-model="editData.description"
                                      required
                                      class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"></textarea>
                            <p class="text-[10px] text-slate-400 mt-1">
                                Gunakan deskripsi singkat dan jelas untuk employer dan kandidat.
                            </p>
                        </div>

                        <!-- 4. Status fasilitas (Toggle Switch) -->
                        <div class="flex items-center justify-between pt-1">
                            <div>
                                <span class="text-xs font-bold text-slate-900 block">Status fasilitas</span>
                                <span class="text-[10px] text-slate-400">Tersedia untuk dipilih di lowongan baru.</span>
                            </div>

                            <label class="relative inline-flex items-center cursor-pointer gap-2">
                                <input type="checkbox"
                                       name="is_active"
                                       value="1"
                                       x-model="editData.is_active"
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                <span class="text-xs font-semibold"
                                      :class="editData.is_active ? 'text-emerald-700' : 'text-slate-400'"
                                      x-text="editData.is_active ? 'Aktif' : 'Nonaktif'"></span>
                            </label>
                        </div>

                        <!-- 5. Info Banner (Digunakan di X lowongan) -->
                        <div class="bg-blue-50/80 border border-blue-100 rounded-2xl p-3.5 flex items-start gap-2.5 text-blue-900 text-xs leading-relaxed">
                            <svg class="w-4 h-4 text-blue-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <template x-if="editData.usage_count > 0">
                                    <span>
                                        Digunakan di <strong x-text="editData.usage_count"></strong> lowongan. Perubahan nama dan deskripsi berlaku pada seluruh lowongan terkait.
                                    </span>
                                </template>
                                <template x-if="editData.usage_count === 0">
                                    <span>
                                        Belum digunakan di lowongan manapun. Fasilitas ini aman untuk diubah atau dihapus.
                                    </span>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">* Wajib diisi</span>
                        <div class="flex items-center gap-2.5">
                            <button type="button"
                                    @click="showEditModal = false"
                                    class="px-5 py-2.5 border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 rounded-xl text-xs font-bold transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                    class="px-6 py-2.5 bg-[#193a7c] hover:bg-[#132e63] text-white rounded-xl text-xs font-bold shadow-xs transition-all">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════
        MODAL 3: PERINGATAN FASILITAS MASIH DIGUNAKAN (DELETE BLOCKED)
    ══════════════════════════════════════════════════════════════════════ -->
    <div x-show="showBlockedModal"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <!-- Backdrop -->
            <div x-show="showBlockedModal"
                 @click="showBlockedModal = false"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

            <!-- Modal Panel -->
            <div class="relative bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-md sm:w-full border border-slate-100 p-6 space-y-4">
                <div class="flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 border border-amber-200/80 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Fasilitas Masih Digunakan</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            Fasilitas <strong class="text-slate-900" x-text="blockedData.name"></strong> (<span class="font-mono text-slate-700" x-text="blockedData.identifier"></span>) saat ini masih aktif digunakan oleh <strong class="text-blue-900" x-text="blockedData.usage_count + ' lowongan kerja'"></strong>.
                        </p>
                    </div>
                </div>

                <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200/60 text-xs text-amber-900 leading-relaxed">
                    <strong>Aturan Sistem:</strong> Fasilitas yang masih terhubung pada lowongan tidak boleh dihapus demi menjaga integritas data lamaran dan kesesuaian profil kandidat.
                    <br><br>
                    Silakan <strong>nonaktifkan fasilitas</strong> ini jika ingin membatasi agar tidak dapat dipilih lagi pada lowongan baru.
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button type="button"
                            @click="showBlockedModal = false"
                            class="px-4 py-2 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-bold transition-colors">
                        Tutup
                    </button>
                    <form method="POST" :action="'{{ url('admin/facilities') }}/' + blockedData.id + '/toggle-status'">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 bg-amber-700 hover:bg-amber-800 text-white rounded-xl text-xs font-bold transition-colors">
                            Nonaktifkan Fasilitas
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════
        MODAL 4: KONFIRMASI HAPUS (PENGGUNAAN NOL)
    ══════════════════════════════════════════════════════════════════════ -->
    <div x-show="showDeleteModal"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <!-- Backdrop -->
            <div x-show="showDeleteModal"
                 @click="showDeleteModal = false"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

            <!-- Modal Panel -->
            <div class="relative bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-md sm:w-full border border-slate-100 p-6 space-y-4">
                <div class="flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-2xl bg-red-50 text-red-600 border border-red-200 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Hapus Master Fasilitas?</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            Apakah Anda yakin ingin menghapus fasilitas <strong class="text-slate-900" x-text="deleteData.name"></strong> (<span class="font-mono text-slate-700" x-text="deleteData.identifier"></span>)?
                        </p>
                    </div>
                </div>

                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 leading-relaxed">
                    Fasilitas ini memiliki jumlah penggunaan <strong>0 lowongan</strong> sehingga aman dihapus dari master data. Tindakan ini bersifat permanen dan akan dicatat di Log Aktivitas.
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button type="button"
                            @click="showDeleteModal = false"
                            class="px-4 py-2 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-bold transition-colors">
                        Batal
                    </button>
                    <form method="POST" :action="'{{ url('admin/facilities') }}/' + deleteData.id">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-xs font-bold shadow-xs transition-colors">
                            Ya, Hapus Fasilitas
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- ══════════════════════════════════════════════════════════════════════════
    ALPINE.JS CONTROLLER SCRIPT
══════════════════════════════════════════════════════════════════════════ -->
<script>
function facilityManagement() {
    return {
        // Modal States
        showCreateModal: false,
        showEditModal: false,
        showDeleteModal: false,
        showBlockedModal: false,

        // Create Form State
        createIsActive: true,
        createFileName: '',
        createFileMeta: '',
        createSvgPreview: '',
        createFileValid: false,

        // Edit Form State
        editData: {
            id: null,
            identifier: 'FAS-000',
            name: '',
            description: '',
            icon_filename: 'default.svg',
            icon_size: '2.4 KB',
            dimensions: '24 × 24 px',
            svg_content: '',
            is_active: true,
            usage_count: 0
        },
        editFileName: '',
        editFileSize: '',
        editSvgPreview: '',

        // Delete Modal State
        deleteData: {
            id: null,
            name: '',
            identifier: ''
        },

        // Blocked Modal State
        blockedData: {
            id: null,
            name: '',
            identifier: '',
            usage_count: 0
        },

        // Open Create Modal
        openCreateModal() {
            this.createIsActive = true;
            this.createFileName = '';
            this.createFileMeta = '';
            this.createSvgPreview = '';
            this.createFileValid = false;
            this.showCreateModal = true;
        },

        // Open Edit Modal & Fetch JSON data
        async openEditModal(facilityId) {
            this.editFileName = '';
            this.editFileSize = '';
            this.editSvgPreview = '';

            try {
                const response = await fetch(`{{ url('admin/facilities') }}/${facilityId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                if (!response.ok) throw new Error('Gagal mengambil data fasilitas');
                const data = await response.json();
                this.editData = data;
                this.showEditModal = true;
            } catch (err) {
                alert('Terjadi kesalahan saat memuat data fasilitas: ' + err.message);
            }
        },

        // Show Blocked Notice
        showBlockedNotice(id, name, identifier, usageCount) {
            this.blockedData = { id, name, identifier, usage_count: usageCount };
            this.showBlockedModal = true;
        },

        // Confirm Delete
        confirmDelete(id, name, identifier) {
            this.deleteData = { id, name, identifier };
            this.showDeleteModal = true;
        },

        // Handle File Selection for Create Modal
        handleCreateFileSelect(event) {
            const file = event.target.files[0];
            if (file) this.processCreateSvgFile(file);
        },

        handleCreateFileDrop(event) {
            event.currentTarget.classList.remove('border-blue-500', 'bg-blue-50/30');
            const file = event.dataTransfer.files[0];
            if (file) {
                const fileInput = document.getElementById('create_svg_input');
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;
                this.processCreateSvgFile(file);
            }
        },

        processCreateSvgFile(file) {
            if (!file.name.endsWith('.svg') && file.type !== 'image/svg+xml') {
                alert('Peringatan: Berkas harus berupa format file SVG (.svg).');
                return;
            }
            if (file.size > 102400) {
                alert('Peringatan: Ukuran file melebihi batas 100 KB (' + Math.round(file.size / 1024) + ' KB).');
                return;
            }

            this.createFileName = file.name;
            const sizeKb = (file.size / 1024).toFixed(1);

            const reader = new FileReader();
            reader.onload = (e) => {
                const content = e.target.result;
                // Quick basic client sanitization check for script tags
                if (/<script\b/i.test(content) || /\bon[a-z0-9_-]+\s*=/i.test(content)) {
                    alert('File SVG ditolak: Terdeteksi skrip atau event handler berbahaya dalam berkas SVG.');
                    this.createSvgPreview = '';
                    this.createFileValid = false;
                    document.getElementById('create_svg_input').value = '';
                    return;
                }
                this.createSvgPreview = content;
                this.createFileMeta = `SVG · ${sizeKb} KB · 24 × 24 px`;
                this.createFileValid = true;
            };
            reader.readAsText(file);
        },

        // Handle File Selection for Edit Modal
        handleEditFileSelect(event) {
            const file = event.target.files[0];
            if (file) this.processEditSvgFile(file);
        },

        handleEditFileDrop(event) {
            event.currentTarget.classList.remove('border-blue-500', 'bg-blue-50/30');
            const file = event.dataTransfer.files[0];
            if (file) {
                const fileInput = document.getElementById('edit_svg_input');
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;
                this.processEditSvgFile(file);
            }
        },

        processEditSvgFile(file) {
            if (!file.name.endsWith('.svg') && file.type !== 'image/svg+xml') {
                alert('Peringatan: Berkas harus berupa format file SVG (.svg).');
                return;
            }
            if (file.size > 102400) {
                alert('Peringatan: Ukuran file melebihi batas 100 KB (' + Math.round(file.size / 1024) + ' KB).');
                return;
            }

            this.editFileName = file.name;
            const sizeKb = (file.size / 1024).toFixed(1);
            this.editFileSize = `${sizeKb} KB`;

            const reader = new FileReader();
            reader.onload = (e) => {
                const content = e.target.result;
                if (/<script\b/i.test(content) || /\bon[a-z0-9_-]+\s*=/i.test(content)) {
                    alert('File SVG ditolak: Terdeteksi skrip atau event handler berbahaya dalam berkas SVG.');
                    this.editSvgPreview = '';
                    document.getElementById('edit_svg_input').value = '';
                    return;
                }
                this.editSvgPreview = content;
            };
            reader.readAsText(file);
        }
    };
}
</script>
@endsection
