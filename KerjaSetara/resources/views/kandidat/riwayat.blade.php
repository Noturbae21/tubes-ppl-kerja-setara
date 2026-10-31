@extends('layouts.kandidat')

@section('title', 'Riwayat Lamaran Saya - KerjaSetara')
@section('meta_description', 'Pantau status lamaran pekerjaan dan tahapan seleksi rekrutmen inklusif Anda di KerjaSetara.')

@section('content')
<div class="bg-[#f8fafc] min-h-screen py-8 sm:py-10 text-slate-900"
     x-data="{
        activeTab: '{{ $activeStatus ?? 'all' }}',
        activeModal: null,
        selectedApp: null,

        openModal(type, appData) {
            this.activeModal = type;
            this.selectedApp = appData;
            document.body.classList.add('overflow-hidden');
        },
        closeModal() {
            this.activeModal = null;
            this.selectedApp = null;
            document.body.classList.remove('overflow-hidden');
        }
     }"
     @keydown.escape.window="closeModal()">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header: Judul & Subteks Deskripsi -->
        <div class="mb-6">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Riwayat Lamaran Saya
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Pantau status lamaran pekerjaan dan tahapan seleksi rekrutmen Anda di sini.
            </p>
        </div>

        <!-- Tab Filter Status Lamaran -->
        <div class="bg-white border border-slate-200/90 rounded-2xl p-1.5 inline-flex flex-wrap items-center gap-1 shadow-xs mb-6 w-full sm:w-auto"
             role="tablist"
             aria-label="Filter Status Lamaran Pekerjaan">
            
            <!-- Tab: Semua Lamaran -->
            <button type="button"
                    @click="activeTab = 'all'"
                    :class="activeTab === 'all' 
                        ? 'bg-[#193a7c] text-white shadow-xs font-semibold' 
                        : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium'"
                    class="px-4 py-2 rounded-xl text-sm transition-all focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none"
                    role="tab"
                    :aria-selected="(activeTab === 'all').toString()">
                Semua Lamaran ({{ $statusCounts['all'] ?? 4 }})
            </button>

            <!-- Tab: Menunggu Review -->
            <button type="button"
                    @click="activeTab = 'pending'"
                    :class="activeTab === 'pending' 
                        ? 'bg-[#193a7c] text-white shadow-xs font-semibold' 
                        : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium'"
                    class="px-4 py-2 rounded-xl text-sm transition-all focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none"
                    role="tab"
                    :aria-selected="(activeTab === 'pending').toString()">
                Menunggu Review ({{ $statusCounts['pending'] ?? 1 }})
            </button>

            <!-- Tab: Wawancara -->
            <button type="button"
                    @click="activeTab = 'interview'"
                    :class="activeTab === 'interview' 
                        ? 'bg-[#193a7c] text-white shadow-xs font-semibold' 
                        : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium'"
                    class="px-4 py-2 rounded-xl text-sm transition-all focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none"
                    role="tab"
                    :aria-selected="(activeTab === 'interview').toString()">
                Wawancara ({{ $statusCounts['interview'] ?? 1 }})
            </button>

            <!-- Tab: Diterima -->
            <button type="button"
                    @click="activeTab = 'accepted'"
                    :class="activeTab === 'accepted' 
                        ? 'bg-[#193a7c] text-white shadow-xs font-semibold' 
                        : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium'"
                    class="px-4 py-2 rounded-xl text-sm transition-all focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none"
                    role="tab"
                    :aria-selected="(activeTab === 'accepted').toString()">
                Diterima ({{ $statusCounts['accepted'] ?? 1 }})
            </button>

            <!-- Tab: Ditolak -->
            <button type="button"
                    @click="activeTab = 'rejected'"
                    :class="activeTab === 'rejected' 
                        ? 'bg-[#193a7c] text-white shadow-xs font-semibold' 
                        : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium'"
                    class="px-4 py-2 rounded-xl text-sm transition-all focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none"
                    role="tab"
                    :aria-selected="(activeTab === 'rejected').toString()">
                Ditolak ({{ $statusCounts['rejected'] ?? 1 }})
            </button>
        </div>

        <!-- Daftar Kartu Riwayat Lamaran -->
        @if($applications->count() > 0)
            <div class="space-y-3.5" role="list" aria-label="Daftar Riwayat Lamaran Pekerjaan">
                @foreach($applications as $app)
                    @php
                        $job = $app->jobVacancy;
                        $company = $job?->company;
                        $initials = $app->company_initials;
                        $bgClass = $app->initials_bg_class;
                        $filterCategory = $app->filter_category;
                    @endphp

                    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-xs hover:border-slate-300 transition-all duration-200"
                         x-show="activeTab === 'all' || activeTab === '{{ $filterCategory }}'"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         role="listitem">
                        
                        <!-- Kolom 1: Logo Perusahaan (Inisial) & Nama Posisi & Perusahaan -->
                        <div class="flex items-center gap-4 min-w-[280px] lg:min-w-[340px]">
                            <!-- Circle Avatar Inisial Perusahaan -->
                            <div class="w-12 h-12 rounded-full {{ $bgClass }} text-white font-bold text-base flex items-center justify-center flex-shrink-0 shadow-xs tracking-tight"
                                 aria-hidden="true">
                                {{ $initials }}
                            </div>

                            <div class="flex flex-col">
                                <h2 class="text-base font-bold text-slate-900 leading-snug">
                                    <a href="{{ route('kandidat.lowongan.show', $job->id ?? 1) }}" 
                                       class="hover:text-blue-900 hover:underline transition-colors focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none rounded">
                                        {{ $job->title ?? 'Posisi Pekerjaan' }}
                                    </a>
                                </h2>
                                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                                    {{ $company->company_name ?? 'Perusahaan Mitra' }} - {{ $job->location ?? 'Indonesia' }}
                                </p>
                            </div>
                        </div>

                        <!-- Kolom 2: Tanggal Lamar -->
                        <div class="text-xs sm:text-sm text-slate-500 min-w-[150px]">
                            <span>{{ $app->formatted_applied_date }}</span>
                        </div>

                        <!-- Kolom 3: Status Lamaran -->
                        <div class="min-w-[170px]">
                            @if($app->status === 'interview')
                                <span class="inline-flex items-center px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-[#fef3c7] text-[#92400e]">
                                    Tahap Wawancara
                                </span>
                            @elseif(in_array($app->status, ['pending', 'reviewed', 'shortlisted']))
                                <span class="inline-flex items-center px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-[#fef9c3] text-[#854d0e] border border-amber-200/60">
                                    Menunggu Review HR
                                </span>
                            @elseif($app->status === 'accepted')
                                <span class="inline-flex items-center gap-1 px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-[#ecfdf5] text-[#065f46] border border-emerald-200/60">
                                    <span>Tawaran Kerja Diterima</span>
                                    <span aria-hidden="true">🎉</span>
                                </span>
                            @elseif($app->status === 'rejected')
                                <span class="inline-flex items-center px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-[#fef2f2] text-[#991b1b] border border-rose-200/60">
                                    Tidak Dilanjutkan
                                </span>
                            @elseif($app->status === 'cancelled')
                                <span class="inline-flex items-center px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                    Dibatalkan
                                </span>
                            @else
                                <span class="inline-flex items-center px-3.5 py-1.5 rounded-lg text-xs font-semibold {{ $app->status_badge_class }}">
                                    {{ $app->status_label }}
                                </span>
                            @endif
                        </div>

                        <!-- Kolom 4: Aksi Berdasarkan Status -->
                        <div class="flex items-center justify-end min-w-[180px]">
                            @if($app->status === 'interview')
                                <!-- Tombol: Lihat Detail Jadwal -->
                                <button type="button"
                                        @click="openModal('interview', {{ json_encode([
                                            'id' => $app->id,
                                            'title' => $job->title ?? 'UI/UX Designer',
                                            'company' => $company->company_name ?? 'PT Teknologi Inovatif',
                                            'interview_date' => $app->formatted_interview_date,
                                            'interview_method' => $app->interview_method ?? 'Online via Zoom Meeting (Tautan otomatis aktif 10 menit sebelum jadwal)',
                                            'interview_link' => $app->interview_link ?? 'https://zoom.us/j/9876543210',
                                            'assistive_facilities' => $app->interview_assistive_facilities ?? 'Auto-Caption / Subtitle aktif & Penerjemah Isyarat (BISINDO).'
                                        ]) }})"
                                        class="w-full sm:w-auto bg-[#193a7c] hover:bg-blue-800 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-xs transition-colors focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none text-center">
                                    Lihat Detail Jadwal
                                </button>

                            @elseif(in_array($app->status, ['pending', 'reviewed', 'shortlisted']))
                                <!-- Tombol: Batalkan Lamaran -->
                                <button type="button"
                                        @click="openModal('cancel', {{ json_encode([
                                            'id' => $app->id,
                                            'title' => $job->title ?? 'Frontend Developer',
                                            'company' => $company->company_name ?? 'Nusa Digital',
                                            'applied_date' => $app->formatted_applied_date
                                        ]) }})"
                                        class="w-full sm:w-auto border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:outline-none text-center">
                                    Batalkan Lamaran
                                </button>

                            @elseif($app->status === 'accepted')
                                <!-- Tombol: Lihat Penawaran (Hijau) -->
                                <button type="button"
                                        @click="openModal('offer', {{ json_encode([
                                            'id' => $app->id,
                                            'title' => $job->title ?? 'Customer Support',
                                            'company' => $company->company_name ?? 'PT Solusi Bersama',
                                            'salary' => $app->offer_salary ?? 'Rp 6.500.000 / bulan + Tunjangan Makan & Transport',
                                            'benefits' => $app->offer_benefits ?? 'Asuransi Kesehatan BPJS Lengkap, Tunjangan Komunikasi & Tunjangan Hari Raya (THR)',
                                            'start_date' => $app->formatted_offer_start_date,
                                            'facilities' => $app->offer_facilities ?? 'Jalur landai/ramp kursi roda, lift aksesibel, meja kerja ergonomis (adjustable desk), toilet disabilitas, serta opsi kerja kombinasi hybrid (2 hari WFH).',
                                            'offer_status' => $app->offer_status ?? 'offered'
                                        ]) }})"
                                        class="w-full sm:w-auto bg-[#059669] hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-xs transition-colors focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:outline-none text-center">
                                    Lihat Penawaran
                                </button>

                            @elseif($app->status === 'rejected')
                                <!-- Tombol: Lihat Catatan Evaluasi -->
                                <button type="button"
                                        @click="openModal('feedback', {{ json_encode([
                                            'id' => $app->id,
                                            'title' => $job->title ?? 'Data Analyst',
                                            'company' => $company->company_name ?? 'Arunika Finance',
                                            'date' => $app->formatted_rejection_date,
                                            'feedback' => $app->rejection_feedback ?? 'Terima kasih atas dedikasi dan waktu Anda dalam mengikuti proses seleksi di Arunika Finance. Tim kami sangat mengapresiasi keahlian analisis data dan portofolio dashboard yang Anda sertakan. Namun, saat ini kami memilih kandidat dengan pengalaman mendalam dalam pemodelan prediktif lanjutan (Time Series Forecasting). Portofolio Anda sangat berpotensi, dan profil Anda telah kami simpan di Talent Pool Inklusif kami untuk lowongan spesialis data berikutnya yang relevan.',
                                            'recommendation' => $app->rejection_recommendation ?? 'Saran pengembangan: Perbanyak studi kasus implementasi Time Series lanjutan, automated pipeline ETL data, dan machine learning terapan.'
                                        ]) }})"
                                        class="w-full sm:w-auto border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:outline-none text-center">
                                    Lihat Catatan
                                </button>

                            @else
                                <span class="text-xs text-slate-400 italic">Tidak ada aksi aktif</span>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Bagian Pagination di Bawah -->
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                <!-- Counter Data -->
                <div class="text-xs sm:text-sm text-slate-500">
                    Menampilkan 1–{{ $applications->count() }} dari {{ $applications->total() }} lamaran
                </div>

                <!-- Navigasi Halaman -->
                <nav class="inline-flex items-center gap-1.5" aria-label="Navigasi Halaman Lamaran">
                    <!-- Tombol Sebelumnya (<) -->
                    <a href="{{ $applications->previousPageUrl() ?: '#' }}"
                       class="w-9 h-9 rounded-lg border border-slate-200 bg-white text-slate-500 flex items-center justify-center hover:bg-slate-50 transition-colors {{ $applications->onFirstPage() ? 'opacity-40 cursor-not-allowed pointer-events-none' : '' }}"
                       aria-label="Halaman Sebelumnya"
                       aria-disabled="{{ $applications->onFirstPage() ? 'true' : 'false' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>

                    <!-- Tombol Nomor Halaman 1 (Aktif) -->
                    <span class="w-9 h-9 rounded-lg bg-[#193a7c] text-white font-bold text-sm flex items-center justify-center shadow-xs"
                          aria-current="page">
                        {{ $applications->currentPage() }}
                    </span>

                    <!-- Tombol Berikutnya (>) -->
                    <a href="{{ $applications->nextPageUrl() ?: '#' }}"
                       class="w-9 h-9 rounded-lg border border-slate-200 bg-white text-slate-500 flex items-center justify-center hover:bg-slate-50 transition-colors {{ !$applications->hasMorePages() ? 'opacity-40 cursor-not-allowed pointer-events-none' : '' }}"
                       aria-label="Halaman Berikutnya"
                       aria-disabled="{{ !$applications->hasMorePages() ? 'true' : 'false' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </nav>
            </div>

        @else
            <!-- State Kosong jika belum ada lamaran -->
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-xs">
                <div class="w-16 h-16 bg-blue-50 text-blue-900 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900 mb-1">Belum Ada Riwayat Lamaran</h2>
                <p class="text-sm text-slate-500 max-w-md mx-auto mb-6">
                    Anda belum mengirimkan lamaran ke lowongan inklusif manapun. Temukan posisi impian yang sesuai akomodasi Anda sekarang.
                </p>
                <a href="{{ route('kandidat.home') }}" class="btn-primary text-sm py-2.5 px-6 inline-flex items-center gap-2">
                    Jelajahi Lowongan Inklusif
                </a>
            </div>
        @endif

    </div>


    <!-- ========================================================================= -->
    <!-- KOMPONEN MODAL / POPUP INTERAKTIF (WCAG 2.1 Level AA)                    -->
    <!-- ========================================================================= -->

    <!-- 1. MODAL DETAIL JADWAL WAWANCARA -->
    <div x-show="activeModal === 'interview'"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-interview-title"
         role="dialog"
         aria-modal="true">
        
        <!-- Backdrop Dim with Blur -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
             x-show="activeModal === 'interview'"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-end="opacity-0"
             @click="closeModal()"></div>

        <!-- Dialog Box -->
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white p-6 sm:p-7 text-left shadow-2xl transition-all sm:my-8 w-full max-w-lg border border-slate-100"
                 x-show="activeModal === 'interview'"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <!-- Close Button (X) -->
                <button type="button"
                        @click="closeModal()"
                        class="absolute top-5 right-5 p-1 rounded-lg border border-slate-200 text-slate-400 hover:text-slate-600 hover:bg-slate-50 focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none transition-colors"
                        aria-label="Tutup jendela detail jadwal wawancara">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Modal Header -->
                <div class="pr-8">
                    <h3 id="modal-interview-title" class="text-lg font-bold text-slate-900 tracking-tight">
                        Detail Jadwal Wawancara
                    </h3>
                    <p class="text-sm font-semibold text-slate-800 mt-2">
                        <span x-text="selectedApp?.title"></span> di <span x-text="selectedApp?.company"></span>
                    </p>
                </div>

                <!-- Info Rows -->
                <div class="mt-5 space-y-4">
                    <!-- Row: Tanggal & Waktu -->
                    <div class="flex items-start gap-3.5">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 mt-0.5" aria-hidden="true">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs text-slate-500 font-medium block">Tanggal & waktu</span>
                            <span class="text-sm font-bold text-slate-900" x-text="selectedApp?.interview_date"></span>
                        </div>
                    </div>

                    <!-- Row: Lokasi / Metode Wawancara -->
                    <div class="flex items-start gap-3.5">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 mt-0.5" aria-hidden="true">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs text-slate-500 font-medium block">Lokasi / metode wawancara</span>
                            <span class="text-sm text-slate-800 leading-snug" x-text="selectedApp?.interview_method"></span>
                        </div>
                    </div>

                    <!-- Box Fasilitas Asistif Inklusif -->
                    <div class="bg-blue-50/80 rounded-xl p-3.5 border border-blue-100 flex items-start gap-3 mt-4">
                        <div class="text-blue-700 flex-shrink-0 mt-0.5" aria-hidden="true">
                            <!-- Accessibility Icon -->
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <p class="text-xs sm:text-sm text-blue-900 font-medium leading-relaxed">
                            <span class="font-bold">Fasilitas asistif yang disiapkan perusahaan:</span>
                            <span x-text="selectedApp?.assistive_facilities"></span>
                        </p>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button"
                            @click="closeModal()"
                            class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-semibold hover:bg-slate-50 focus-visible:ring-2 focus-visible:ring-slate-300 focus-visible:outline-none transition-colors">
                        Tutup
                    </button>
                    <a :href="selectedApp?.interview_link || '#'"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="px-5 py-2.5 rounded-xl bg-[#193a7c] text-white text-sm font-semibold hover:bg-blue-800 shadow-xs focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none transition-colors inline-flex items-center gap-2">
                        <span>Buka Link Meeting Zoom</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>

            </div>
        </div>
    </div>


    <!-- 2. MODAL DETAIL TAWARAN KERJA DITERIMA -->
    <div x-show="activeModal === 'offer'"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-offer-title"
         role="dialog"
         aria-modal="true">
        
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
             x-show="activeModal === 'offer'"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-end="opacity-0"
             @click="closeModal()"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white p-6 sm:p-7 text-left shadow-2xl transition-all sm:my-8 w-full max-w-lg border border-slate-100"
                 x-show="activeModal === 'offer'"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <!-- Close Button (X) -->
                <button type="button"
                        @click="closeModal()"
                        class="absolute top-5 right-5 p-1 rounded-lg border border-slate-200 text-slate-400 hover:text-slate-600 hover:bg-slate-50 focus-visible:ring-2 focus-visible:ring-emerald-600 focus-visible:outline-none transition-colors"
                        aria-label="Tutup jendela penawaran kerja">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Header -->
                <div class="pr-8">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 mb-2">
                        <span>Penawaran Resmi KerjaSetara</span>
                        <span aria-hidden="true">🎉</span>
                    </div>
                    <h3 id="modal-offer-title" class="text-xl font-extrabold text-slate-900 tracking-tight">
                        Tawaran Kerja Diterima
                    </h3>
                    <p class="text-sm font-semibold text-slate-800 mt-1">
                        <span x-text="selectedApp?.title"></span> di <span x-text="selectedApp?.company"></span>
                    </p>
                </div>

                <!-- Offering Details -->
                <div class="mt-5 space-y-4">
                    <!-- Gaji & Kompensasi -->
                    <div class="flex items-start gap-3.5">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 mt-0.5" aria-hidden="true">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs text-slate-500 font-medium block">Gaji Pokok & Tunjangan</span>
                            <span class="text-sm font-bold text-slate-900" x-text="selectedApp?.salary"></span>
                            <span class="text-xs text-slate-500 block mt-0.5" x-text="selectedApp?.benefits"></span>
                        </div>
                    </div>

                    <!-- Tanggal Mulai Bekerja -->
                    <div class="flex items-start gap-3.5">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 mt-0.5" aria-hidden="true">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs text-slate-500 font-medium block">Tanggal Mulai Bekerja</span>
                            <span class="text-sm font-bold text-slate-900" x-text="selectedApp?.start_date"></span>
                        </div>
                    </div>

                    <!-- Fasilitas Ramah Disabilitas Terkonfirmasi -->
                    <div class="bg-emerald-50/80 rounded-xl p-3.5 border border-emerald-200/70 flex items-start gap-3">
                        <div class="text-emerald-700 flex-shrink-0 mt-0.5" aria-hidden="true">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-emerald-900 block">Fasilitas Ramah Disabilitas Terkonfirmasi:</span>
                            <p class="text-xs sm:text-sm text-emerald-900 mt-0.5 leading-relaxed" x-text="selectedApp?.facilities"></p>
                        </div>
                    </div>
                </div>

                <!-- Footer: Tolak & Terima Penawaran -->
                <div class="mt-6 pt-5 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                    
                    <!-- Form Tolak Tawaran -->
                    <form method="POST" :action="'/kandidat/lamaran/' + selectedApp?.id + '/tolak-tawaran'" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Apakah Anda yakin ingin menolak penawaran kerja ini?')"
                                class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 hover:text-rose-700 hover:bg-rose-50 text-sm font-semibold transition-colors focus-visible:ring-2 focus-visible:ring-rose-400 focus-visible:outline-none">
                            Tolak Tawaran
                        </button>
                    </form>

                    <!-- Form Terima Tawaran -->
                    <form method="POST" :action="'/kandidat/lamaran/' + selectedApp?.id + '/terima-tawaran'" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                                class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-[#059669] hover:bg-emerald-700 text-white text-sm font-semibold shadow-xs flex items-center justify-center gap-2 focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:outline-none transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Terima Penawaran Kerja</span>
                        </button>
                    </form>

                </div>

            </div>
        </div>
    </div>


    <!-- 3. MODAL KONFIRMASI BATALKAN LAMARAN -->
    <div x-show="activeModal === 'cancel'"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-cancel-title"
         role="dialog"
         aria-modal="true">
        
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
             x-show="activeModal === 'cancel'"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-end="opacity-0"
             @click="closeModal()"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white p-6 sm:p-7 text-left shadow-2xl transition-all sm:my-8 w-full max-w-md border border-slate-100"
                 x-show="activeModal === 'cancel'"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <!-- Close Button (X) -->
                <button type="button"
                        @click="closeModal()"
                        class="absolute top-5 right-5 p-1 rounded-lg border border-slate-200 text-slate-400 hover:text-slate-600 hover:bg-slate-50 focus-visible:ring-2 focus-visible:ring-rose-600 focus-visible:outline-none transition-colors"
                        aria-label="Tutup jendela batalkan lamaran">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Icon Peringatan -->
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mb-4" aria-hidden="true">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <!-- Header -->
                <h3 id="modal-cancel-title" class="text-lg font-bold text-slate-900 tracking-tight">
                    Batalkan Lamaran Pekerjaan?
                </h3>
                <p class="text-sm font-semibold text-slate-800 mt-1">
                    <span x-text="selectedApp?.title"></span> di <span x-text="selectedApp?.company"></span>
                </p>

                <!-- Deskripsi Peringatan -->
                <p class="text-xs sm:text-sm text-slate-600 mt-3 leading-relaxed">
                    Apakah Anda yakin ingin membatalkan lamaran pekerjaan ini? Tindakan ini akan menarik seluruh berkas yang Anda kirimkan dari proses seleksi tim HRD. Tindakan ini tidak dapat dibatalkan.
                </p>

                <!-- Form Action Submit -->
                <form method="POST" :action="'/kandidat/lamaran/' + selectedApp?.id + '/batal'" class="mt-6">
                    @csrf
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button"
                                @click="closeModal()"
                                class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-semibold hover:bg-slate-50 focus-visible:ring-2 focus-visible:ring-slate-300 focus-visible:outline-none transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold shadow-xs flex items-center gap-2 focus-visible:ring-2 focus-visible:ring-rose-500 focus-visible:outline-none transition-colors">
                            <span>Ya, Batalkan Lamaran</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>


    <!-- 4. MODAL CATATAN EVALUASI REKRUTMEN (FEEDBACK HRD) -->
    <div x-show="activeModal === 'feedback'"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-feedback-title"
         role="dialog"
         aria-modal="true">
        
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
             x-show="activeModal === 'feedback'"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-end="opacity-0"
             @click="closeModal()"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white p-6 sm:p-7 text-left shadow-2xl transition-all sm:my-8 w-full max-w-lg border border-slate-100"
                 x-show="activeModal === 'feedback'"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <!-- Close Button (X) -->
                <button type="button"
                        @click="closeModal()"
                        class="absolute top-5 right-5 p-1 rounded-lg border border-slate-200 text-slate-400 hover:text-slate-600 hover:bg-slate-50 focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none transition-colors"
                        aria-label="Tutup jendela catatan evaluasi">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Header -->
                <div class="pr-8">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 mb-2">
                        <span>Evaluasi Rekrutmen Inklusif</span>
                    </div>
                    <h3 id="modal-feedback-title" class="text-lg font-bold text-slate-900 tracking-tight">
                        Catatan Evaluasi Rekrutmen
                    </h3>
                    <p class="text-sm font-semibold text-slate-800 mt-1">
                        <span x-text="selectedApp?.title"></span> di <span x-text="selectedApp?.company"></span>
                    </p>
                    <span class="text-xs text-slate-400 block mt-0.5">
                        Ditinjau oleh Tim HRD pada <span x-text="selectedApp?.date"></span>
                    </span>
                </div>

                <!-- Feedback Content -->
                <div class="mt-5 space-y-3.5">
                    
                    <!-- Umpan Balik Utama HRD -->
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-200/90 text-xs sm:text-sm text-slate-700 leading-relaxed space-y-2.5">
                        <div class="flex items-center gap-2 text-slate-900 font-bold text-xs uppercase tracking-wider">
                            <svg class="w-4 h-4 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            <span>Umpan Balik Tim Rekruter</span>
                        </div>
                        <p x-text="selectedApp?.feedback"></p>
                    </div>

                    <!-- Rekomendasi Peningkatan Skill Inklusif -->
                    <div class="bg-blue-50/70 rounded-xl p-3.5 border border-blue-100 flex items-start gap-3">
                        <div class="text-blue-700 flex-shrink-0 mt-0.5" aria-hidden="true">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="text-xs sm:text-sm text-blue-900 leading-relaxed">
                            <span class="font-bold block">Rekomendasi Pengembangan:</span>
                            <span x-text="selectedApp?.recommendation"></span>
                        </div>
                    </div>

                </div>

                <!-- Footer Actions -->
                <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button"
                            @click="closeModal()"
                            class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-semibold hover:bg-slate-50 focus-visible:ring-2 focus-visible:ring-slate-300 focus-visible:outline-none transition-colors">
                        Tutup
                    </button>
                    <a href="{{ route('kandidat.home') }}"
                       class="px-5 py-2.5 rounded-xl bg-[#193a7c] text-white text-sm font-semibold hover:bg-blue-800 shadow-xs focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none transition-colors inline-flex items-center gap-2">
                        <span>Jelajahi Lowongan Serupa</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection
