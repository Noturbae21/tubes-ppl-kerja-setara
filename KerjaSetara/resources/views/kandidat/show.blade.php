@extends('layouts.kandidat')

@section('title', $vacancy->title . ' — ' . ($vacancy->company->company_name ?? 'KerjaSetara'))
@section('meta_description', 'Detail lowongan kerja inklusif ' . $vacancy->title . ' di ' . ($vacancy->company->company_name ?? 'KerjaSetara') . ' dengan fasilitas aksesibilitas ramah disabilitas.')

@section('content')
<div class="bg-slate-50 min-h-screen py-8"
     x-data="{ 
         applyModalOpen: false, 
         selectedResume: '{{ $candidateResumes[0] }}',
         showUploadNew: false 
     }">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- ─── Back Link Breadcrumb ──────────────────────────────────────── -->
        <nav class="mb-6" aria-label="Breadcrumb">
            <a href="{{ route('kandidat.home') }}"
               class="inline-flex items-center gap-2 text-sm font-bold text-blue-900 hover:text-blue-700 bg-white px-3.5 py-2 rounded-xl border border-slate-200 shadow-2xs transition-colors focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali ke Cari Kerja</span>
            </a>
        </nav>

        <!-- ─── Header Lowongan Card ───────────────────────────────────────── -->
        <header class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm mb-8"
                aria-label="Informasi Utama Posisi">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">

                <!-- Left Info: Logo, Title, Badges -->
                <div class="flex items-start gap-4 sm:gap-5">
                    <!-- Company Logo/Avatar -->
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-tr from-blue-900 to-indigo-800 text-white font-black text-xl sm:text-2xl flex items-center justify-center shadow-md flex-shrink-0">
                        {{ strtoupper(substr($vacancy->company->company_name ?? 'KS', 3, 2)) }}
                    </div>

                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            <span class="text-sm font-extrabold text-blue-900">
                                {{ $vacancy->company->company_name ?? 'Perusahaan Mitra' }}
                            </span>
                            <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Mitra Terverifikasi Inklusif
                            </span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mb-3">
                            {{ $vacancy->title }}
                        </h1>

                        <!-- Meta Pills -->
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs sm:text-sm font-semibold text-slate-600">
                            <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-800 px-3 py-1 rounded-xl">
                                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                {{ $vacancy->location }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-900 px-3 py-1 rounded-xl">
                                <svg class="w-4 h-4 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ $vacancy->job_type_label }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 bg-indigo-50 text-indigo-900 px-3 py-1 rounded-xl">
                                ♿ {{ $vacancy->category_label }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-700 px-3 py-1 rounded-xl font-bold">
                                💰 {{ $vacancy->formatted_salary }} / bln
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right Action Buttons -->
                <div class="flex items-center gap-3 lg:flex-col lg:items-end flex-shrink-0">
                    <div class="flex items-center gap-2 w-full lg:w-auto">
                        <!-- Bookmark Toggle Button Form -->
                        <form action="{{ route('kandidat.lowongan.bookmark', $vacancy->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="p-3.5 rounded-2xl border transition-all focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none {{ $isSaved ? 'bg-rose-50 text-rose-600 border-rose-200 hover:bg-rose-100' : 'bg-white text-slate-500 border-slate-200 hover:bg-slate-50 hover:text-slate-700' }}"
                                    title="{{ $isSaved ? 'Hapus bookmark lowongan' : 'Simpan lowongan ke bookmark' }}"
                                    aria-label="{{ $isSaved ? 'Hapus bookmark' : 'Simpan lowongan' }}">
                                <svg class="w-5 h-5 {{ $isSaved ? 'fill-current' : 'stroke-current fill-none' }}" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                </svg>
                            </button>
                        </form>

                        <!-- Primary CTA "Lamar Pekerjaan Sekarang" Button -->
                        @if($hasApplied)
                            <div class="flex-1 lg:flex-initial px-5 py-3.5 rounded-2xl bg-emerald-50 text-emerald-800 border border-emerald-300 font-bold text-sm flex items-center justify-center gap-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Lamaran Sudah Terkirim</span>
                            </div>
                        @else
                            <button type="button"
                                    @click="applyModalOpen = true"
                                    class="flex-1 lg:flex-initial px-7 py-3.5 bg-blue-900 hover:bg-blue-800 active:bg-blue-950 text-white font-extrabold text-sm rounded-2xl shadow-lg transition-all flex items-center justify-center gap-2.5 focus-visible:ring-3 focus-visible:ring-blue-500 focus-visible:outline-none">
                                <span>Lamar Pekerjaan Sekarang</span>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        @endif
                    </div>

                    <span class="text-xs text-slate-400 font-medium hidden lg:block">
                        Ditutup: {{ $vacancy->deadline ? $vacancy->deadline->isoFormat('D MMMM Y') : '14 hari lagi' }}
                    </span>
                </div>

            </div>
        </header>

        <!-- ─── Two-Column Content Layout ──────────────────────────────────── -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- ─── Kolom Kiri: Detail Informasi Pekerjaan (col-span-8) ──────── -->
            <div class="lg:col-span-8 space-y-8">

                <!-- 1. Deskripsi Pekerjaan -->
                <section class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm"
                         aria-labelledby="desc-heading">
                    <h2 id="desc-heading" class="text-xl font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
                        <span class="text-blue-900">📄</span> Deskripsi Pekerjaan
                    </h2>
                    <div class="text-slate-700 text-sm sm:text-base leading-relaxed space-y-3 font-normal">
                        <p>{{ $vacancy->description }}</p>
                        <p>
                            Posisi ini dirancang dalam ekosistem kerja inklusif yang menghargai keberagaman. Seluruh proses penugasan didukung sarana akomodasi yang memadai, fleksibilitas jam kerja terukur, serta pendampingan mentor untuk memastikan kenyamanan dan produktivitas Anda.
                        </p>
                    </div>
                </section>

                <!-- 2. Tanggung Jawab Utama -->
                <section class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm"
                         aria-labelledby="resp-heading">
                    <h2 id="resp-heading" class="text-xl font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
                        <span class="text-blue-900">🎯</span> Tanggung Jawab Utama
                    </h2>
                    <ul class="space-y-3" role="list">
                        @foreach($vacancy->responsibilities_list as $duty)
                            <li class="flex items-start gap-3 text-sm sm:text-base text-slate-700">
                                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-800 flex items-center justify-center flex-shrink-0 text-xs font-bold mt-0.5">
                                    ✓
                                </span>
                                <span class="leading-relaxed">{{ $duty }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <!-- 3. Kualifikasi & Persyaratan -->
                <section class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm"
                         aria-labelledby="qual-heading">
                    <h2 id="qual-heading" class="text-xl font-bold text-slate-900 mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
                        <span class="text-blue-900">📋</span> Kualifikasi & Persyaratan
                    </h2>
                    <ul class="space-y-3" role="list">
                        @foreach($vacancy->qualifications_list as $requirement)
                            <li class="flex items-start gap-3 text-sm sm:text-base text-slate-700">
                                <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-800 flex items-center justify-center flex-shrink-0 text-xs font-bold mt-0.5">
                                    •
                                </span>
                                <span class="leading-relaxed">{{ $requirement }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <!-- 4. Fasilitas Aksesibilitas di Lokasi Kerja -->
                <section class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm"
                         aria-labelledby="facilities-heading">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6 pb-3 border-b border-slate-100">
                        <h2 id="facilities-heading" class="text-xl font-bold text-slate-900 flex items-center gap-2">
                            <span class="text-blue-900">🏢</span> Fasilitas Aksesibilitas di Lokasi Kerja
                        </h2>
                        <span class="text-xs font-bold text-blue-900 bg-blue-50 px-3 py-1 rounded-full border border-blue-200 self-start sm:self-auto">
                            Terverifikasi Tim KerjaSetara
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($vacancy->facility_details as $fKey => $fDetail)
                            <div class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/60 hover:bg-white hover:border-blue-300 hover:shadow-md transition-all flex items-start gap-3.5">
                                <!-- Big Accessible Icon -->
                                <div class="w-11 h-11 rounded-xl bg-blue-900 text-white flex items-center justify-center text-lg flex-shrink-0 shadow-xs">
                                    @if(($fDetail['icon'] ?? '') === 'wheelchair')
                                        <span aria-hidden="true">♿</span>
                                    @elseif(($fDetail['icon'] ?? '') === 'elevator')
                                        <span aria-hidden="true">🛗</span>
                                    @elseif(($fDetail['icon'] ?? '') === 'toilet')
                                        <span aria-hidden="true">🚻</span>
                                    @elseif(($fDetail['icon'] ?? '') === 'parking')
                                        <span aria-hidden="true">🅿️</span>
                                    @elseif(($fDetail['icon'] ?? '') === 'hands')
                                        <span aria-hidden="true">🧏</span>
                                    @elseif(($fDetail['icon'] ?? '') === 'braille')
                                        <span aria-hidden="true">⠃</span>
                                    @elseif(($fDetail['icon'] ?? '') === 'screen-reader')
                                        <span aria-hidden="true">🔊</span>
                                    @elseif(($fDetail['icon'] ?? '') === 'keyboard')
                                        <span aria-hidden="true">⌨️</span>
                                    @elseif(($fDetail['icon'] ?? '') === 'sun')
                                        <span aria-hidden="true">🧠</span>
                                    @elseif(($fDetail['icon'] ?? '') === 'home')
                                        <span aria-hidden="true">🏠</span>
                                    @else
                                        <span aria-hidden="true">✨</span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-bold text-slate-900 mb-1">
                                        {{ $fDetail['label'] }}
                                    </h3>
                                    <p class="text-xs text-slate-600 leading-relaxed font-normal">
                                        {{ $fDetail['desc'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <!-- 5. Profil Singkat Perusahaan Mitra -->
                <section class="bg-gradient-to-br from-slate-900 to-blue-950 rounded-3xl p-6 sm:p-8 text-white shadow-lg"
                         aria-labelledby="company-heading">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-700 text-white font-extrabold flex items-center justify-center text-base">
                            {{ strtoupper(substr($vacancy->company->company_name ?? 'KS', 3, 2)) }}
                        </div>
                        <div>
                            <h2 id="company-heading" class="text-lg font-bold">
                                {{ $vacancy->company->company_name ?? 'Perusahaan Mitra' }}
                            </h2>
                            <p class="text-xs text-blue-200">
                                {{ $vacancy->company->industry ?? 'Perusahaan Teknologi & Jasa' }} • {{ $vacancy->company->location }}
                            </p>
                        </div>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed mb-4">
                        {{ $vacancy->company->description ?? 'Perusahaan berkomitmen pada penerapan inklusivitas tempat kerja dan memberikan peluang setara bagi penyandang disabilitas.' }}
                    </p>
                    @if($vacancy->company->website)
                        <a href="{{ $vacancy->company->website }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-300 hover:text-white underline underline-offset-2">
                            <span>Kunjungi Website Perusahaan</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    @endif
                </section>

            </div>

            <!-- ─── Kolom Kanan: Matching Engine Box (col-span-4) ───────────── -->
            <div class="lg:col-span-4">
                <div class="sticky top-20 space-y-6">

                    <!-- MATCHING ENGINE BOX CARD -->
                    <div class="bg-white rounded-3xl p-6 border-2 border-blue-900/10 shadow-xl relative overflow-hidden"
                         aria-labelledby="matching-heading">
                        
                        <!-- Top Header & Calibrated Category -->
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <h2 id="matching-heading" class="text-sm font-extrabold uppercase tracking-wider text-slate-700">
                                    Matching Engine
                                </h2>
                            </div>
                            <span class="text-[11px] font-bold text-blue-800 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200">
                                Sistem Cerdas
                            </span>
                        </div>

                        <!-- Candidate Profile Calibrator -->
                        <div class="mb-5 bg-slate-50 p-3 rounded-2xl border border-slate-200">
                            <label for="cat-selector" class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-1">
                                Dikalibrasi Berdasarkan Profil:
                            </label>
                            <form action="{{ route('kandidat.lowongan.show', $vacancy->id) }}" method="GET" id="cat-form">
                                <select name="cat"
                                        id="cat-selector"
                                        onchange="document.getElementById('cat-form').submit()"
                                        class="w-full py-1.5 px-2.5 bg-white rounded-xl border border-slate-200 text-xs font-bold text-blue-900 focus:ring-2 focus:ring-blue-600 focus:outline-none cursor-pointer">
                                    <option value="fisik" {{ $matching['candidate_category'] === 'fisik' ? 'selected' : '' }}>
                                        ♿ Disabilitas Fisik (Kursi Roda / Mobilitas)
                                    </option>
                                    <option value="komunikasi" {{ $matching['candidate_category'] === 'komunikasi' ? 'selected' : '' }}>
                                        🧏 Sensorik (Tunarungu & Wicara / BISINDO)
                                    </option>
                                    <option value="digital" {{ $matching['candidate_category'] === 'digital' ? 'selected' : '' }}>
                                        🔊 Penglihatan (Screen Reader / Low Vision)
                                    </option>
                                    <option value="neurodiverse" {{ $matching['candidate_category'] === 'neurodiverse' ? 'selected' : '' }}>
                                        🧠 Neurodiverse (Sensori / Quiet Room)
                                    </option>
                                </select>
                            </form>
                        </div>

                        <!-- Circular Percentage Indicator (Gauge Donut) -->
                        <div class="text-center py-4 flex flex-col items-center">
                            @php
                                $pct = $matching['percentage'];
                                $radius = 54;
                                $circumference = 2 * pi() * $radius;
                                $dashOffset = $circumference - ($pct / 100) * $circumference;
                            @endphp

                            <div class="relative w-36 h-36 flex items-center justify-center mb-3">
                                <svg class="w-36 h-36 transform -rotate-90" viewBox="0 0 120 120">
                                    <!-- Background Track -->
                                    <circle cx="60" cy="60" r="{{ $radius }}"
                                            stroke="#E2E8F0"
                                            stroke-width="10"
                                            fill="transparent" />
                                    <!-- Animated Progress Ring -->
                                    <circle cx="60" cy="60" r="{{ $radius }}"
                                            stroke="{{ $matching['ring_color'] }}"
                                            stroke-width="10"
                                            stroke-dasharray="{{ $circumference }}"
                                            stroke-dashoffset="{{ $dashOffset }}"
                                            stroke-linecap="round"
                                            fill="transparent"
                                            class="transition-all duration-1000 ease-out" />
                                </svg>
                                
                                <!-- Center Text -->
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                                    <span class="text-3xl font-black text-slate-900 leading-none">
                                        {{ $pct }}%
                                    </span>
                                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mt-1">
                                        Kecocokan
                                    </span>
                                </div>
                            </div>

                            <!-- Match Status Badge -->
                            <div class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-extrabold border {{ $matching['badge_color'] }} mb-2">
                                <span>★</span>
                                <span>{{ $matching['badge'] }}</span>
                            </div>

                            <p class="text-xs text-slate-600 leading-relaxed max-w-xs font-normal">
                                {{ $matching['summary_text'] }}
                            </p>
                        </div>

                        <!-- Breakdown Rincian Fasilitas -->
                        <div class="mt-4 pt-4 border-t border-slate-100 space-y-4">
                            
                            <!-- Matched Primary Facilities -->
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-800 flex items-center gap-1.5 mb-2">
                                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Fasilitas Utama Cocok Sempurna ({{ count($matching['matched_primary']) }}):
                                </span>
                                <ul class="space-y-1.5">
                                    @foreach($matching['matched_primary'] as $mp)
                                        <li class="flex items-center gap-2 text-xs font-semibold text-slate-700 bg-emerald-50/70 px-2.5 py-1.5 rounded-xl border border-emerald-150">
                                            <span class="text-emerald-700 font-bold">✓</span>
                                            <span>{{ $mp['label'] }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Additional Facilities -->
                            @if(count($matching['extra_facilities']) > 0)
                                <div>
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-blue-800 flex items-center gap-1.5 mb-2">
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                        </svg>
                                        Fasilitas Ekstra Perusahaan:
                                    </span>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach(array_slice($matching['extra_facilities'], 0, 4) as $extra)
                                            <span class="text-[11px] font-medium text-slate-700 bg-slate-100 px-2 py-1 rounded-lg">
                                                + {{ $extra['label'] }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        </div>

                        <!-- CTA button inside Matching Card -->
                        <div class="mt-6 pt-4 border-t border-slate-100">
                            @if($hasApplied)
                                <div class="w-full py-3 bg-emerald-50 text-emerald-800 font-bold text-xs rounded-xl border border-emerald-200 text-center">
                                    Lamaran Sudah Terkirim
                                </div>
                            @else
                                <button type="button"
                                        @click="applyModalOpen = true"
                                        class="w-full py-3.5 bg-blue-900 hover:bg-blue-800 text-white font-extrabold text-sm rounded-xl shadow-md transition-all flex items-center justify-center gap-2 focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none">
                                    <span>Lamar Pekerjaan Ini</span>
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </button>
                            @endif
                        </div>

                    </div>

                    <!-- Equality Guarantee Badge Box -->
                    <div class="bg-blue-50/70 rounded-2xl p-4 border border-blue-200/80 text-xs text-blue-950 flex items-start gap-3">
                        <span class="text-xl flex-shrink-0" aria-hidden="true">🛡️</span>
                        <div>
                            <h3 class="font-bold mb-0.5">Jaminan Kesetaraan KerjaSetara</h3>
                            <p class="text-slate-600 leading-relaxed font-normal">
                                Data kebutuhan aksesibilitas Anda dilindungi dan hanya digunakan secara proporsional untuk penyediaan akomodasi wajar selama seleksi.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

    <!-- ─── 3. MODAL / POP-UP LAMAR PEKERJAAN ──────────────────────────────── -->
    <div x-show="applyModalOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog"
         aria-modal="true"
         aria-labelledby="modal-title"
         @keydown.escape.window="applyModalOpen = false">
        
        <!-- Backdrop Blur -->
        <div x-show="applyModalOpen"
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
             @click="applyModalOpen = false"></div>

        <!-- Modal Dialog Content Box -->
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="applyModalOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-xl border border-slate-200"
                 @click.outside="applyModalOpen = false">

                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-900 to-indigo-900 px-6 py-5 text-white flex items-start justify-between">
                    <div>
                        <span class="inline-block text-[11px] font-bold text-blue-200 uppercase tracking-wider mb-1">
                            Formulir Lamaran Inklusif
                        </span>
                        <h2 id="modal-title" class="text-xl font-black text-white leading-tight">
                            Lamar Posisi {{ $vacancy->title }}
                        </h2>
                        <p class="text-xs text-blue-150 mt-0.5 text-blue-100">
                            {{ $vacancy->company->company_name ?? 'Perusahaan Mitra' }} • {{ $vacancy->location }}
                        </p>
                    </div>

                    <!-- Close Button -->
                    <button type="button"
                            @click="applyModalOpen = false"
                            class="rounded-xl p-1.5 text-white/80 hover:text-white hover:bg-white/10 transition-colors focus-visible:ring-2 focus-visible:ring-white focus-visible:outline-none"
                            aria-label="Tutup jendela lamaran">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Form Body -->
                <form action="{{ route('kandidat.lowongan.apply', $vacancy->id) }}" method="POST" class="p-6 space-y-5">
                    @csrf

                    <!-- 1. Pilihan Dropdown CV / Resume Tersimpan -->
                    <div>
                        <label for="resume-select" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Pilih CV / Resume Tersimpan <span class="text-red-600">*</span>
                        </label>
                        <select name="resume_name"
                                id="resume-select"
                                required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold text-slate-800 bg-slate-50 focus:bg-white focus:border-blue-700 focus:ring-2 focus:ring-blue-600 focus:outline-none transition-all">
                            @foreach($candidateResumes as $resume)
                                <option value="{{ $resume }}">{{ $resume }}</option>
                            @endforeach
                            <option value="Unggah Dokumen CV Baru (.pdf)">+ Gunakan / Unggah Dokumen CV Baru</option>
                        </select>
                        <span class="text-[11px] text-slate-500 mt-1 block">
                            Perekrut dapat mengakses riwayat pendidikan, keahlian, dan portofolio Anda melalui dokumen ini.
                        </span>
                    </div>

                    <!-- 2. Input Link Portofolio (Opsional) -->
                    <div>
                        <label for="portfolio-input" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Tautan Portofolio / LinkedIn / GitHub <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                            </div>
                            <input type="url"
                                   name="portfolio_url"
                                   id="portfolio-input"
                                   placeholder="https://linkedin.com/in/... atau https://github.com/..."
                                   class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-blue-700 focus:ring-2 focus:ring-blue-600 focus:outline-none transition-all">
                        </div>
                    </div>

                    <!-- 3. Teks Area Catatan / Pesan Aksesibilitas Khusus -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="accessibility-notes" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Catatan Kebutuhan Aksesibilitas Khusus <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                            </label>
                            <span class="text-[10px] font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md">
                                Ramah Akomodasi
                            </span>
                        </div>
                        <textarea name="accessibility_notes"
                                  id="accessibility-notes"
                                  rows="3"
                                  placeholder="Contoh: Saya memerlukan juru bahasa isyarat (BISINDO) saat sesi wawancara, tes teknis dengan software pembaca layar (NVDA), atau penyesuaian jadwal ramah sensori."
                                  class="w-full p-3 rounded-xl border border-slate-300 text-sm text-slate-800 placeholder-slate-400 bg-slate-50 focus:bg-white focus:border-blue-700 focus:ring-2 focus:ring-blue-600 focus:outline-none transition-all"></textarea>
                        <span class="text-[11px] text-slate-500 mt-1 block leading-normal">
                            Tim rekrutmen akan menyiapkan akomodasi yang Anda cantumkan sebelum sesi seleksi berlangsung.
                        </span>
                    </div>

                    <!-- 4. Kotak Centang Persetujuan Data -->
                    <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox"
                                   name="consent_agreement"
                                   id="consent-check"
                                   value="1"
                                   required
                                   class="w-4 h-4 mt-0.5 rounded text-blue-900 border-slate-300 focus:ring-blue-600 focus:outline-none cursor-pointer">
                            <span class="text-xs text-slate-700 leading-relaxed font-medium">
                                Saya menyetujui bahwa data profil dan rincian kebutuhan aksesibilitas saya akan dibagikan kepada tim HRD perekrut demi penyesuaian proses seleksi yang setara, adil, dan inklusif. <span class="text-red-600 font-bold">*</span>
                            </span>
                        </label>
                    </div>

                    <!-- Modal Actions Buttons -->
                    <div class="pt-2 flex items-center justify-end gap-3 border-t border-slate-100">
                        <button type="button"
                                @click="applyModalOpen = false"
                                class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-100 font-semibold text-sm transition-colors focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:outline-none">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-6 py-2.5 bg-blue-900 hover:bg-blue-800 text-white font-extrabold text-sm rounded-xl shadow-md transition-all flex items-center gap-2 focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            <span>Kirim Lamaran</span>
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

</div>
@endsection
