@extends('admin.layouts.app')

@section('title', 'Verifikasi Legalitas Perusahaan')
@section('page_title', 'Verifikasi Perusahaan')

@section('content')
<div class="py-6 space-y-6"
     x-data="companyVerificationManager()"
     @keydown.escape.window="closeAllModals()">

    <!-- ══════════════════════════════════════════════════════════════════════════
        1. BREADCRUMB & PAGE HEADER (View 1)
    ══════════════════════════════════════════════════════════════════════════ -->
    <div>
        <nav class="flex items-center text-xs text-slate-400 font-medium space-x-2 mb-2" aria-label="Breadcrumb">
            <span>Admin</span>
            <span>/</span>
            <span class="text-slate-700 font-semibold">Verifikasi Perusahaan</span>
        </nav>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Verifikasi Legalitas Perusahaan.</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Tinjau dan setujui pendaftaran perusahaan baru.</p>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
        2. SUMMARY ALERT CARD (View 1)
    ══════════════════════════════════════════════════════════════════════════ -->
    <div class="bg-blue-50/70 border border-blue-200/80 rounded-2xl p-4 sm:p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-3 shadow-xs">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-blue-100/90 text-blue-700 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <span class="text-xs sm:text-sm font-bold text-slate-900 block">
                    {{ $counts['pending'] }} perusahaan menunggu pemeriksaan dokumen
                </span>
                <span class="text-[11px] text-slate-500 block md:hidden mt-0.5">
                    Lakukan verifikasi berkas NPWP & NIB untuk mengaktifkan akun.
                </span>
            </div>
        </div>

        <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 pl-11 md:pl-0">
            <span class="text-blue-700 font-bold">01</span>
            <span>Tinjau dokumen</span>
            <span class="text-slate-300">→</span>
            <span class="text-blue-700 font-bold">02</span>
            <span>Validasi</span>
            <span class="text-slate-300">→</span>
            <span class="text-blue-700 font-bold">03</span>
            <span>Putuskan</span>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
        3. FILTER TABS & SEARCH (View 1)
    ══════════════════════════════════════════════════════════════════════════ -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200 pb-2">
        <!-- Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0" role="tablist">
            <!-- All -->
            <a href="{{ route('admin.companies.verification', array_merge(request()->query(), ['status' => 'all', 'page' => 1])) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $statusFilter === 'all' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <span>All</span>
                <span class="px-1.5 py-0.5 text-[10px] rounded-full {{ $statusFilter === 'all' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/80 text-slate-600' }}">
                    {{ $counts['all'] }}
                </span>
            </a>

            <!-- Pending -->
            <a href="{{ route('admin.companies.verification', array_merge(request()->query(), ['status' => 'pending', 'page' => 1])) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $statusFilter === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'text-slate-600 hover:bg-amber-50 hover:text-amber-800' }}">
                <span>Pending</span>
                <span class="px-1.5 py-0.5 text-[10px] rounded-full {{ $statusFilter === 'pending' ? 'bg-amber-600 text-amber-100' : 'bg-amber-100 text-amber-800' }}">
                    {{ $counts['pending'] }}
                </span>
            </a>

            <!-- Terverifikasi -->
            <a href="{{ route('admin.companies.verification', array_merge(request()->query(), ['status' => 'verified', 'page' => 1])) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $statusFilter === 'verified' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-800' }}">
                <span>Terverifikasi</span>
                <span class="px-1.5 py-0.5 text-[10px] rounded-full {{ $statusFilter === 'verified' ? 'bg-emerald-700 text-emerald-100' : 'bg-emerald-100 text-emerald-800' }}">
                    {{ $counts['verified'] }}
                </span>
            </a>

            <!-- Ditolak -->
            <a href="{{ route('admin.companies.verification', array_merge(request()->query(), ['status' => 'rejected', 'page' => 1])) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $statusFilter === 'rejected' ? 'bg-rose-600 text-white shadow-sm' : 'text-slate-600 hover:bg-rose-50 hover:text-rose-800' }}">
                <span>Ditolak</span>
                <span class="px-1.5 py-0.5 text-[10px] rounded-full {{ $statusFilter === 'rejected' ? 'bg-rose-700 text-rose-100' : 'bg-rose-100 text-rose-800' }}">
                    {{ $counts['rejected'] }}
                </span>
            </a>

            <!-- Perlu Revisi -->
            <a href="{{ route('admin.companies.verification', array_merge(request()->query(), ['status' => 'needs_revision', 'page' => 1])) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $statusFilter === 'needs_revision' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-800' }}">
                <span>Perlu Revisi</span>
                <span class="px-1.5 py-0.5 text-[10px] rounded-full {{ $statusFilter === 'needs_revision' ? 'bg-indigo-700 text-indigo-100' : 'bg-indigo-100 text-indigo-800' }}">
                    {{ $counts['needs_revision'] }}
                </span>
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.companies.verification') }}" class="flex items-center gap-2">
            <input type="hidden" name="status" value="{{ $statusFilter }}">
            <input type="hidden" name="sort" value="{{ $sort }}">
            <div class="relative w-full sm:w-64">
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari perusahaan..."
                       class="w-full bg-white border border-slate-200 text-slate-800 text-xs rounded-xl pl-8 pr-3 py-2 outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                    </svg>
                </span>
            </div>
            @if($search)
                <a href="{{ route('admin.companies.verification', ['status' => $statusFilter]) }}"
                   title="Reset pencarian"
                   class="px-2.5 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
        4. SUB-BAR (Info + Sort)
    ══════════════════════════════════════════════════════════════════════════ -->
    <div class="flex items-center justify-between text-xs text-slate-500">
        <div>
            <span class="font-semibold text-slate-700">Semua perusahaan</span>
            <span class="text-slate-400"> · Data ilustratif</span>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-slate-400">Urutkan:</span>
            <a href="{{ route('admin.companies.verification', array_merge(request()->query(), ['sort' => $sort === 'latest' ? 'oldest' : 'latest'])) }}"
               class="inline-flex items-center gap-1.5 font-semibold text-slate-700 hover:text-blue-700 transition-colors">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                </svg>
                <span>{{ $sort === 'oldest' ? 'Terlama lebih dahulu' : 'Terbaru lebih dahulu' }}</span>
            </a>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
        5. COMPANY TABLE (View 1)
    ══════════════════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600" aria-label="Tabel Verifikasi Perusahaan">
                <thead class="bg-slate-50/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th scope="col" class="w-10 px-4 py-3.5 text-center">
                            <input type="checkbox"
                                   @click="toggleSelectAll()"
                                   :checked="selectAll"
                                   class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th scope="col" class="px-4 py-3.5">Nama Perusahaan</th>
                        <th scope="col" class="px-4 py-3.5">Tanggal Daftar</th>
                        <th scope="col" class="px-4 py-3.5">Alamat Pusat</th>
                        <th scope="col" class="px-4 py-3.5">NPWP/NIB Doc</th>
                        <th scope="col" class="px-4 py-3.5">Surat Izin Doc</th>
                        <th scope="col" class="px-4 py-3.5">Status</th>
                        <th scope="col" class="px-4 py-3.5 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($companies as $company)
                        <tr class="hover:bg-slate-50/60 transition-colors group">
                            <!-- Checkbox -->
                            <td class="px-4 py-4 text-center">
                                <input type="checkbox"
                                       value="{{ $company->id }}"
                                       x-model="selectedCompanies"
                                       class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            </td>

                            <!-- Nama Perusahaan -->
                            <td class="px-4 py-4">
                                <div class="font-bold text-slate-900 group-hover:text-blue-700 transition-colors">
                                    {{ $company->company_name }}
                                </div>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    <span>{{ $company->identifier }}</span>
                                    <span>·</span>
                                    <span>{{ $company->industry ?? 'Teknologi' }}</span>
                                </div>
                            </td>

                            <!-- Tanggal Daftar -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="font-semibold text-slate-800">
                                    {{ $company->formatted_registered_date }}
                                </div>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    {{ $company->formatted_registered_time }}
                                </div>
                            </td>

                            <!-- Alamat Pusat -->
                            <td class="px-4 py-4 max-w-xs">
                                <div class="font-medium text-slate-700 truncate" title="{{ $company->location ?? 'Indonesia' }}">
                                    {{ $company->location ?? 'Jl. Kantor Contoh No. 1' }}
                                </div>
                                <div class="text-[11px] text-slate-400">
                                    {{ $company->user?->city ?? 'Jakarta Selatan' }}
                                </div>
                            </td>

                            <!-- NPWP/NIB Doc Button -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <button type="button"
                                        @click="openViewer({{ $company->id }}, 'npwp')"
                                        title="Buka Dokumen NPWP/NIB"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-red-50/50 hover:border-red-200 text-slate-700 text-xs font-semibold shadow-2xs transition-colors cursor-pointer group/doc">
                                    <!-- Red PDF Badge Icon -->
                                    <svg class="w-3.5 h-3.5 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M7 2a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2V8l-6-6H7zm5 7V3.5L17.5 9H12z"/>
                                    </svg>
                                    <span class="text-[11px] font-bold text-slate-700 group-hover/doc:text-red-700">PDF</span>
                                    <svg class="w-3 h-3 text-slate-400 group-hover/doc:text-red-600 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </button>
                            </td>

                            <!-- Surat Izin Doc Button -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <button type="button"
                                        @click="openViewer({{ $company->id }}, 'nib')"
                                        title="Buka Dokumen Surat Izin Usaha / NIB"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-red-50/50 hover:border-red-200 text-slate-700 text-xs font-semibold shadow-2xs transition-colors cursor-pointer group/doc">
                                    <svg class="w-3.5 h-3.5 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M7 2a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2V8l-6-6H7zm5 7V3.5L17.5 9H12z"/>
                                    </svg>
                                    <span class="text-[11px] font-bold text-slate-700 group-hover/doc:text-red-700">PDF</span>
                                    <svg class="w-3 h-3 text-slate-400 group-hover/doc:text-red-600 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </button>
                            </td>

                            <!-- Status Badge -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($company->isVerified())
                                    <div class="flex items-center gap-1.5 text-emerald-700 font-bold text-xs">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <span>Verified</span>
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                        {{ $company->status_subtitle }}
                                    </div>
                                @elseif($company->isRejected())
                                    <div class="flex items-center gap-1.5 text-rose-700 font-bold text-xs">
                                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                        <span>Ditolak</span>
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-0.5 max-w-[140px] truncate" title="{{ $company->rejection_reason }}">
                                        {{ $company->status_subtitle }}
                                    </div>
                                @elseif($company->needsRevision())
                                    <div class="flex items-center gap-1.5 text-indigo-700 font-bold text-xs">
                                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                        <span>Perlu Revisi</span>
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-0.5 max-w-[140px] truncate" title="{{ $company->revision_reason }}">
                                        {{ $company->status_subtitle }}
                                    </div>
                                @else
                                    <div class="flex items-center gap-1.5 text-amber-700 font-bold text-xs">
                                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                        <span>Pending</span>
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                        Menunggu tinjauan
                                    </div>
                                @endif
                            </td>

                            <!-- Tindakan (Review & Menu Tindakan) -->
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <div class="inline-flex items-center gap-1.5 relative" x-data="{ openMenu: false }">
                                    <!-- Tombol Review -->
                                    <button type="button"
                                            @click="openViewer({{ $company->id }}, 'nib')"
                                            class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white hover:bg-blue-50/50 hover:border-blue-300 text-blue-700 font-bold text-xs shadow-2xs transition-all cursor-pointer">
                                        Review
                                    </button>

                                    <!-- Tombol ... (State · Menu Tindakan) -->
                                    <div class="relative">
                                        <button type="button"
                                                @click="openMenu = !openMenu"
                                                @click.away="openMenu = false"
                                                title="Menu Tindakan Verifikasi"
                                                class="w-8 h-8 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 text-slate-600 flex items-center justify-center transition-colors cursor-pointer">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M6 12a2 2 0 11-4 0 2 2 0 014 0zm8 0a2 2 0 11-4 0 2 2 0 014 0zm8 0a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </button>

                                        <!-- Dropdown Menu Tindakan (Matching Figma State) -->
                                        <div x-show="openMenu"
                                             x-cloak
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="opacity-0 scale-95"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="opacity-100 scale-100"
                                             x-transition:leave-end="opacity-0 scale-95"
                                             class="absolute right-0 top-full mt-1.5 w-44 bg-white rounded-2xl border border-slate-200 shadow-xl py-2 z-50 text-left">

                                            <!-- 1. Setujui -->
                                            <button type="button"
                                                    @click="openMenu = false; openApproveModal({{ $company->id }}, '{{ addslashes($company->company_name) }}')"
                                                    class="w-full px-4 py-2.5 flex items-center gap-3 text-xs font-semibold text-emerald-700 hover:bg-emerald-50 transition-colors">
                                                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span>Setujui</span>
                                            </button>

                                            <!-- 2. Tolak -->
                                            <button type="button"
                                                    @click="openMenu = false; openRejectModal({{ $company->id }}, '{{ addslashes($company->company_name) }}')"
                                                    class="w-full px-4 py-2.5 flex items-center gap-3 text-xs font-semibold text-rose-700 hover:bg-rose-50 transition-colors">
                                                <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span>Tolak</span>
                                            </button>

                                            <!-- 3. Minta Revisi -->
                                            <button type="button"
                                                    @click="openMenu = false; openRevisionModal({{ $company->id }}, '{{ addslashes($company->company_name) }}')"
                                                    class="w-full px-4 py-2.5 flex items-center gap-3 text-xs font-semibold text-blue-800 hover:bg-blue-50 transition-colors">
                                                <svg class="w-4 h-4 text-blue-700 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                </svg>
                                                <span>Minta Revisi</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                <div class="max-w-sm mx-auto space-y-2">
                                    <svg class="w-10 h-10 mx-auto text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <p class="text-xs font-bold text-slate-700">Tidak ada perusahaan yang sesuai kriteria.</p>
                                    <p class="text-[11px] text-slate-400">Coba ubah filter status atau kata kunci pencarian Anda.</p>
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
        <div class="px-4 sm:px-6 py-4 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4 bg-slate-50/50 text-xs text-slate-500">
            <div>
                Menampilkan
                <span class="font-bold text-slate-700">{{ $companies->firstItem() ?? 0 }}</span>
                -
                <span class="font-bold text-slate-700">{{ $companies->lastItem() ?? 0 }}</span>
                dari
                <span class="font-bold text-slate-700">{{ $companies->total() }}</span>
                perusahaan
            </div>

            <div>
                {{ $companies->links() }}
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
        6. BOTTOM NOTICE
    ══════════════════════════════════════════════════════════════════════════ -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between text-[11px] text-slate-400 gap-2">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <span>Setiap keputusan tersimpan di Log Aktivitas. Semua data perusahaan adalah contoh.</span>
        </div>
        <div>
            Diperbarui {{ now()->translatedFormat('d M Y, H.i') }}
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
        7. STATE · DOCUMENT VIEWER MODAL (Matching Figma Screenshot 5)
    ══════════════════════════════════════════════════════════════════════════ -->
    <div x-show="viewerOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog"
         aria-modal="true"
         aria-labelledby="viewer-title">

        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-xs transition-opacity"
             @click="closeViewer()"></div>

        <div class="min-h-screen px-4 py-6 flex items-center justify-center">
            <div class="relative bg-white rounded-3xl shadow-2xl border border-slate-200 w-full max-w-5xl overflow-hidden z-10 transition-all flex flex-col"
                 style="max-height: 90vh;">

                <!-- Modal Header -->
                <div class="h-16 px-6 border-b border-slate-200 flex items-center justify-between bg-white flex-shrink-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-xl bg-red-50 text-red-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M7 2a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2V8l-6-6H7zm5 7V3.5L17.5 9H12z"/>
                            </svg>
                        </div>
                        <div class="truncate">
                            <h3 id="viewer-title" class="text-sm font-bold text-slate-900 truncate">
                                Dokumen <span x-text="currentDocType === 'nib' ? 'NIB' : 'NPWP'"></span> · <span x-text="activeCompany.company_name"></span>
                            </h3>
                            <p class="text-[11px] text-slate-400 truncate">
                                <span x-text="currentDocType === 'nib' ? activeCompany.nib_file : activeCompany.npwp_file"></span>
                                <span> · 248 KB · Diupload </span>
                                <span x-text="activeCompany.registered_date"></span>
                            </p>
                        </div>
                    </div>

                    <button type="button"
                            @click="closeViewer()"
                            class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer"
                            title="Tutup Modal (Esc)">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body (2-Column Layout) -->
                <div class="flex-1 overflow-hidden grid grid-cols-1 lg:grid-cols-12 divide-y lg:divide-y-0 lg:divide-x divide-slate-200 bg-slate-50/50">

                    <!-- Left: Document Preview Viewport (~65%) -->
                    <div class="lg:col-span-8 flex flex-col bg-slate-100 overflow-hidden">
                        <!-- Toolbar Controls -->
                        <div class="h-11 px-4 bg-white border-b border-slate-200 flex items-center justify-between text-xs text-slate-600 flex-shrink-0">
                            <!-- Page Indicator -->
                            <div class="flex items-center gap-2">
                                <button type="button" class="p-1 text-slate-400 hover:text-slate-700 rounded cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>
                                <span class="font-semibold text-[11px] text-slate-700">1 / 1</span>
                                <button type="button" class="p-1 text-slate-400 hover:text-slate-700 rounded cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Zoom & Fullscreen -->
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-1.5 bg-slate-100 px-2 py-0.5 rounded-lg border border-slate-200/60">
                                    <button type="button" @click="zoomOut()" class="text-slate-500 hover:text-slate-900 font-bold px-1">−</button>
                                    <span class="text-[11px] font-semibold text-slate-700" x-text="zoomLevel + '%'">100%</span>
                                    <button type="button" @click="zoomIn()" class="text-slate-500 hover:text-slate-900 font-bold px-1">+</button>
                                </div>

                                <a :href="currentDocUrl + '?download=1'"
                                   target="_blank"
                                   title="Unduh Berkas Asli"
                                   class="p-1 text-slate-500 hover:text-blue-700 rounded transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Iframe Preview Area -->
                        <div class="flex-1 p-4 overflow-y-auto flex items-center justify-center">
                            <div class="w-full h-full min-h-[420px] rounded-xl border border-slate-200 shadow-sm overflow-hidden bg-white"
                                 :style="'transform: scale(' + (zoomLevel / 100) + '); transform-origin: top center; transition: transform 0.15s ease-out;'">
                                <iframe :src="currentDocUrl"
                                        title="Pratinjau Dokumen Legalitas"
                                        class="w-full h-full min-h-[420px] border-0">
                                </iframe>
                            </div>
                        </div>

                        <!-- Sub-caption -->
                        <div class="px-4 py-2 bg-white border-t border-slate-200 text-[11px] text-slate-400">
                            Dokumen contoh untuk pemeriksaan administratif.
                        </div>
                    </div>

                    <!-- Right: Verification Context Sidebar (~35%) -->
                    <div class="lg:col-span-4 p-5 flex flex-col justify-between overflow-y-auto bg-white space-y-5">
                        <div class="space-y-5">
                            <!-- Context Header -->
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">
                                    KONTEKS VERIFIKASI
                                </span>
                                <h4 class="text-base font-extrabold text-slate-900" x-text="activeCompany.company_name"></h4>
                                <div class="flex items-center gap-2 text-xs text-slate-400 mt-0.5">
                                    <span x-text="activeCompany.identifier"></span>
                                    <span>·</span>
                                    <span>Terdaftar </span>
                                    <span x-text="activeCompany.registered_date"></span>
                                </div>
                                <div class="mt-2">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold"
                                          :class="activeCompany.status === 'verified' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : (activeCompany.status === 'rejected' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-800 border border-amber-200')">
                                        <span class="w-1.5 h-1.5 rounded-full"
                                              :class="activeCompany.status === 'verified' ? 'bg-emerald-500' : (activeCompany.status === 'rejected' ? 'bg-rose-500' : 'bg-amber-500')"></span>
                                        <span x-text="activeCompany.status_label"></span>
                                    </span>
                                </div>
                            </div>

                            <!-- Document Switcher -->
                            <div>
                                <span class="text-[11px] font-bold text-slate-700 block mb-2">Dokumen perusahaan</span>
                                <div class="space-y-2">
                                    <!-- NIB / NPWP Button 1 -->
                                    <button type="button"
                                            @click="switchDoc('npwp')"
                                            class="w-full p-2.5 rounded-xl border text-left text-xs font-semibold flex items-center justify-between transition-all cursor-pointer"
                                            :class="currentDocType === 'npwp' ? 'border-blue-600 bg-blue-50/70 text-blue-900 shadow-2xs' : 'border-slate-200 hover:bg-slate-50 text-slate-700'">
                                        <div class="flex items-center gap-2.5">
                                            <svg class="w-4 h-4 text-blue-700 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span>NPWP/NIB Doc</span>
                                        </div>
                                        <span class="text-[10px] font-bold"
                                              :class="currentDocType === 'npwp' ? 'text-blue-700' : 'text-slate-400'"
                                              x-text="currentDocType === 'npwp' ? 'sedang dilihat' : 'buka berkas'"></span>
                                    </button>

                                    <!-- NIB / Surat Izin Button 2 -->
                                    <button type="button"
                                            @click="switchDoc('nib')"
                                            class="w-full p-2.5 rounded-xl border text-left text-xs font-semibold flex items-center justify-between transition-all cursor-pointer"
                                            :class="currentDocType === 'nib' ? 'border-blue-600 bg-blue-50/70 text-blue-900 shadow-2xs' : 'border-slate-200 hover:bg-slate-50 text-slate-700'">
                                        <div class="flex items-center gap-2.5">
                                            <svg class="w-4 h-4 text-blue-700 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span>Surat Izin Doc</span>
                                        </div>
                                        <span class="text-[10px] font-bold"
                                              :class="currentDocType === 'nib' ? 'text-blue-700' : 'text-slate-400'"
                                              x-text="currentDocType === 'nib' ? 'sedang dilihat' : 'buka berkas'"></span>
                                    </button>
                                </div>
                            </div>

                            <!-- Checklist: Periksa sebelum memutuskan -->
                            <div>
                                <span class="text-[11px] font-bold text-slate-700 block mb-2">Periksa sebelum memutuskan</span>
                                <div class="space-y-2 text-xs text-slate-600">
                                    <label class="flex items-center gap-2.5 cursor-pointer">
                                        <input type="checkbox" x-model="checklist.nameMatched" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                                        <span>Nama usaha sesuai pendaftaran</span>
                                    </label>
                                    <label class="flex items-center gap-2.5 cursor-pointer">
                                        <input type="checkbox" x-model="checklist.addressConsistent" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                                        <span>Alamat pusat konsisten</span>
                                    </label>
                                    <label class="flex items-center gap-2.5 cursor-pointer">
                                        <input type="checkbox" x-model="checklist.docComplete" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                                        <span>Dokumen terbaca dan lengkap</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Catatan Peninjauan -->
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-[11px] font-bold text-slate-700">Catatan peninjauan</span>
                                    <button type="button"
                                            @click="saveNotes()"
                                            class="text-[10px] font-bold text-blue-700 hover:text-blue-900 transition-colors"
                                            x-text="notesSaving ? 'Menyimpan...' : 'Simpan Catatan'"></button>
                                </div>
                                <textarea x-model="reviewNotes"
                                          rows="3"
                                          placeholder="Tambahkan catatan atau alasan permintaan revisi..."
                                          class="w-full text-xs text-slate-800 bg-white border border-slate-200 rounded-xl p-3 outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 resize-none"></textarea>
                                <p class="text-[10px] text-slate-400 mt-1">Melihat dokumen tidak mengubah status perusahaan.</p>
                            </div>
                        </div>

                        <!-- Footer Selesai Meninjau -->
                        <div class="pt-3 border-t border-slate-200">
                            <button type="button"
                                    @click="closeViewer()"
                                    class="w-full py-2.5 bg-[#193a7c] hover:bg-[#132e63] text-white font-bold text-xs rounded-xl shadow-sm transition-all hover:shadow-md cursor-pointer">
                                Selesai meninjau
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
        8. CONFIRMATION MODAL · SETUJUI PERUSAHAAN
    ══════════════════════════════════════════════════════════════════════════ -->
    <div x-show="approveModalOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog"
         aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="approveModalOpen = false"></div>

        <div class="min-h-screen px-4 flex items-center justify-center">
            <div class="relative bg-white rounded-3xl shadow-2xl border border-slate-200 w-full max-w-md p-6 overflow-hidden z-10 transition-all space-y-5">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>

                <div>
                    <h3 class="text-base font-bold text-slate-900">Setujui Legalitas Perusahaan</h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Apakah Anda yakin menyetujui pendaftaran dan berkas legalitas untuk
                        <span class="font-bold text-slate-800" x-text="targetCompanyName"></span>?
                    </p>
                </div>

                <form :action="'/admin/companies/' + targetCompanyId + '/verify'"
                      method="POST"
                      @submit="submitting = true">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Tambahan (Opsional)</label>
                            <textarea name="notes"
                                      rows="2"
                                      placeholder="Tambahkan catatan verifikasi internal jika diperlukan..."
                                      class="w-full text-xs text-slate-800 border border-slate-200 rounded-xl p-2.5 outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 resize-none"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button type="button"
                                    @click="approveModalOpen = false"
                                    :disabled="submitting"
                                    class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer">
                                Batal
                            </button>
                            <button type="submit"
                                    :disabled="submitting"
                                    class="px-5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm transition-all hover:shadow-md inline-flex items-center gap-2 cursor-pointer">
                                <span x-show="submitting" class="w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                                <span x-text="submitting ? 'Memproses...' : 'Ya, Setujui Perusahaan'"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
        9. CONFIRMATION MODAL · TOLAK PERUSAHAAN (Wajib Alasan)
    ══════════════════════════════════════════════════════════════════════════ -->
    <div x-show="rejectModalOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog"
         aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="rejectModalOpen = false"></div>

        <div class="min-h-screen px-4 flex items-center justify-center">
            <div class="relative bg-white rounded-3xl shadow-2xl border border-slate-200 w-full max-w-md p-6 overflow-hidden z-10 transition-all space-y-5">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>

                <div>
                    <h3 class="text-base font-bold text-slate-900">Tolak Verifikasi Perusahaan</h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Keputusan penolakan akan dicatat pada Log Aktivitas dan diberitahukan kepada pihak
                        <span class="font-bold text-slate-800" x-text="targetCompanyName"></span>.
                    </p>
                </div>

                <form :action="'/admin/companies/' + targetCompanyId + '/reject'"
                      method="POST"
                      @submit="if(!rejectReason.trim()) { alert('Alasan penolakan wajib diisi!'); return false; } submitting = true;">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Alasan Penolakan <span class="text-rose-600">* Wajib</span>
                            </label>
                            <textarea name="alasan"
                                      x-model="rejectReason"
                                      rows="3"
                                      required
                                      placeholder="Jelaskan alasan penolakan berkas (misal: Surat izin kadaluarsa, NIB tidak terdaftar pada sistem OSS)..."
                                      class="w-full text-xs text-slate-800 border border-slate-200 rounded-xl p-2.5 outline-none focus:border-rose-600 focus:ring-1 focus:ring-rose-600 resize-none"></textarea>
                            <p class="text-[10px] text-slate-400 mt-1">Alasan akan tampil di area dashboard HRD perusahaan terkait.</p>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button type="button"
                                    @click="rejectModalOpen = false"
                                    :disabled="submitting"
                                    class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer">
                                Batal
                            </button>
                            <button type="submit"
                                    :disabled="submitting"
                                    class="px-5 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-sm transition-all hover:shadow-md inline-flex items-center gap-2 cursor-pointer">
                                <span x-show="submitting" class="w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                                <span x-text="submitting ? 'Menyimpan...' : 'Tolak Perusahaan'"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
        10. CONFIRMATION MODAL · MINTA REVISI (Wajib Alasan)
    ══════════════════════════════════════════════════════════════════════════ -->
    <div x-show="revisionModalOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog"
         aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="revisionModalOpen = false"></div>

        <div class="min-h-screen px-4 flex items-center justify-center">
            <div class="relative bg-white rounded-3xl shadow-2xl border border-slate-200 w-full max-w-md p-6 overflow-hidden z-10 transition-all space-y-5">
                <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-800 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>

                <div>
                    <h3 class="text-base font-bold text-slate-900">Minta Revisi Dokumen</h3>
                    <p class="text-xs text-slate-500 mt-1">
                        Berikan instruksi revisi yang jelas untuk
                        <span class="font-bold text-slate-800" x-text="targetCompanyName"></span> agar perusahaan dapat mengunggah berkas yang sesuai.
                    </p>
                </div>

                <form :action="'/admin/companies/' + targetCompanyId + '/revision'"
                      method="POST"
                      @submit="if(!revisionReason.trim()) { alert('Instruksi revisi wajib diisi!'); return false; } submitting = true;">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Instruksi / Alasan Revisi <span class="text-blue-700">* Wajib</span>
                            </label>
                            <textarea name="alasan"
                                      x-model="revisionReason"
                                      rows="3"
                                      required
                                      placeholder="Tuliskan bagian dokumen yang harus diperbaiki (misal: Lampirkan scan lembar kedua NIB dengan resolusi yang lebih jelas)..."
                                      class="w-full text-xs text-slate-800 border border-slate-200 rounded-xl p-2.5 outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 resize-none"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button type="button"
                                    @click="revisionModalOpen = false"
                                    :disabled="submitting"
                                    class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer">
                                Batal
                            </button>
                            <button type="submit"
                                    :disabled="submitting"
                                    class="px-5 py-2 text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 rounded-xl shadow-sm transition-all hover:shadow-md inline-flex items-center gap-2 cursor-pointer">
                                <span x-show="submitting" class="w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                                <span x-text="submitting ? 'Mengirim...' : 'Kirim Permintaan Revisi'"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<!-- ══════════════════════════════════════════════════════════════════════════
    ALPINE.JS CONTROLLER SCRIPT
══════════════════════════════════════════════════════════════════════════ -->
<script>
function companyVerificationManager() {
    return {
        selectAll: false,
        selectedCompanies: [],

        // Document Viewer Modal State
        viewerOpen: false,
        activeCompany: {},
        currentDocType: 'nib',
        currentDocUrl: '',
        zoomLevel: 100,

        // Review checklist
        checklist: {
            nameMatched: false,
            addressConsistent: false,
            docComplete: false
        },
        reviewNotes: '',
        notesSaving: false,

        // Action Modals State
        approveModalOpen: false,
        rejectModalOpen: false,
        revisionModalOpen: false,
        targetCompanyId: null,
        targetCompanyName: '',
        rejectReason: '',
        revisionReason: '',
        submitting: false,

        toggleSelectAll() {
            this.selectAll = !this.selectAll;
            if (this.selectAll) {
                this.selectedCompanies = @json($companies->pluck('id'));
            } else {
                this.selectedCompanies = [];
            }
        },

        openViewer(companyId, docType) {
            this.currentDocType = docType || 'nib';
            this.zoomLevel = 100;
            this.notesSaving = false;

            // Fetch metadata via show endpoint
            fetch('/admin/companies/' + companyId, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                this.activeCompany = data;
                this.reviewNotes = data.verification_notes || '';
                this.currentDocUrl = '/admin/companies/' + companyId + '/documents/' + this.currentDocType;
                this.viewerOpen = true;
            })
            .catch(err => {
                console.error('Failed to load company metadata:', err);
                this.currentDocUrl = '/admin/companies/' + companyId + '/documents/' + this.currentDocType;
                this.viewerOpen = true;
            });
        },

        closeViewer() {
            this.viewerOpen = false;
        },

        switchDoc(type) {
            this.currentDocType = type;
            if (this.activeCompany && this.activeCompany.id) {
                this.currentDocUrl = '/admin/companies/' + this.activeCompany.id + '/documents/' + type;
            }
        },

        zoomIn() {
            if (this.zoomLevel < 175) this.zoomLevel += 25;
        },

        zoomOut() {
            if (this.zoomLevel > 50) this.zoomLevel -= 25;
        },

        saveNotes() {
            if (!this.activeCompany || !this.activeCompany.id) return;
            this.notesSaving = true;

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                          '{{ csrf_token() }}';

            fetch('/admin/companies/' + this.activeCompany.id + '/notes', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ notes: this.reviewNotes })
            })
            .then(res => res.json())
            .then(data => {
                this.notesSaving = false;
            })
            .catch(err => {
                this.notesSaving = false;
                console.error('Failed to save notes:', err);
            });
        },

        openApproveModal(id, name) {
            this.targetCompanyId = id;
            this.targetCompanyName = name;
            this.submitting = false;
            this.approveModalOpen = true;
        },

        openRejectModal(id, name) {
            this.targetCompanyId = id;
            this.targetCompanyName = name;
            this.rejectReason = '';
            this.submitting = false;
            this.rejectModalOpen = true;
        },

        openRevisionModal(id, name) {
            this.targetCompanyId = id;
            this.targetCompanyName = name;
            this.revisionReason = '';
            this.submitting = false;
            this.revisionModalOpen = true;
        },

        closeAllModals() {
            this.viewerOpen = false;
            this.approveModalOpen = false;
            this.rejectModalOpen = false;
            this.revisionModalOpen = false;
        }
    };
}
</script>
@endsection
