@extends('layouts.kandidat')

@section('title', 'Cari Lowongan Kerja Inklusif — KerjaSetara')
@section('meta_description', 'Jelajahi puluhan lowongan kerja inklusif yang ramah disabilitas fisik, sensorik, digital, dan neurodiverse dengan standar aksesibilitas terverifikasi.')

@section('content')
<div class="bg-slate-50 min-h-screen">

    <!-- ─── 1. HERO SECTION & PENCARIAN ────────────────────────────────────── -->
    <section class="relative bg-gradient-to-b from-blue-900 via-blue-900 to-indigo-950 text-white pt-12 pb-20 px-4 sm:px-6 lg:px-8 overflow-hidden"
             aria-labelledby="hero-heading">
        <!-- Subtle background shapes for modern aesthetic -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute top-0 right-1/4 w-96 h-96 bg-blue-400 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-indigo-400 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-5xl mx-auto text-center">
            <!-- Accessibility Badge Pill -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-800/80 border border-blue-400/30 text-blue-200 text-xs sm:text-sm font-semibold mb-6 shadow-xs">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Bursa Kerja Inklusif Bersertifikasi WCAG 2.1 Level AA</span>
            </div>

            <!-- Welcoming Headline -->
            <h1 id="hero-heading" class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight text-white leading-tight mb-4">
                @auth
                    Halo, <span class="text-blue-300">{{ auth()->user()->name }}</span>! 👋<br>
                    Temukan Karir Impian Tanpa Hambatan
                @else
                    Peluang Karir Setara & Inklusif<br>
                    <span class="text-blue-300">Untuk Setiap Potensi Terbaik Anda</span>
                @endauth
            </h1>

            <p class="text-base sm:text-lg text-blue-150 max-w-2xl mx-auto mb-8 font-normal leading-relaxed text-blue-100">
                Terhubung dengan puluhan perusahaan terkemuka di Indonesia yang telah terverifikasi menyediakan fasilitas aksesibilitas ramah disabilitas.
            </p>

            <!-- Search Form Card -->
            <div class="bg-white rounded-3xl p-3 sm:p-4 shadow-2xl border border-slate-100 text-slate-800 text-left max-w-4xl mx-auto">
                <form action="{{ route('kandidat.home') }}" method="GET" class="flex flex-col md:flex-row items-stretch gap-2.5">
                    
                    <!-- Keyword Input -->
                    <div class="flex-1 relative">
                        <label for="search-input" class="sr-only">Kata Kunci Posisi atau Perusahaan</label>
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text"
                               name="q"
                               id="search-input"
                               value="{{ $search }}"
                               placeholder="Cari judul posisi, keahlian, atau nama perusahaan..."
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 rounded-2xl border border-slate-200 text-sm font-medium text-slate-900 placeholder-slate-400 focus:bg-white focus:border-blue-700 focus:ring-2 focus:ring-blue-600 focus:outline-none transition-all">
                    </div>

                    <!-- Location Filter -->
                    <div class="w-full md:w-56 relative">
                        <label for="location-select" class="sr-only">Filter Lokasi</label>
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <select name="location"
                                id="location-select"
                                class="w-full pl-10 pr-8 py-3 bg-slate-50 rounded-2xl border border-slate-200 text-sm font-semibold text-slate-800 focus:bg-white focus:border-blue-700 focus:ring-2 focus:ring-blue-600 focus:outline-none transition-all appearance-none cursor-pointer">
                            <option value="">Semua Lokasi</option>
                            @foreach($availableLocations as $loc)
                                <option value="{{ $loc }}" {{ $location === $loc ? 'selected' : '' }}>
                                    {{ $loc }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Preserve Category / Tag when searching -->
                    @if($category)
                        <input type="hidden" name="category" value="{{ $category }}">
                    @endif
                    @if($tag)
                        <input type="hidden" name="tag" value="{{ $tag }}">
                    @endif

                    <!-- Submit Search Button -->
                    <button type="submit"
                            class="w-full md:w-auto px-7 py-3 bg-blue-900 hover:bg-blue-800 active:bg-blue-950 text-white font-bold text-sm rounded-2xl shadow-md transition-all flex items-center justify-center gap-2 focus-visible:ring-3 focus-visible:ring-blue-500 focus-visible:outline-none flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>Cari Lowongan</span>
                    </button>
                </form>
            </div>

            <!-- Quick Accessibility Facility Filters Bar -->
            <div class="mt-8 text-left max-w-4xl mx-auto" role="region" aria-label="Filter Cepat Aksesibilitas">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs uppercase font-bold tracking-wider text-blue-200 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter Cepat Fasilitas Aksesibilitas:
                    </span>
                    @if($category || $tag || $search || $location)
                        <a href="{{ route('kandidat.home') }}" 
                           class="text-xs text-blue-200 hover:text-white underline underline-offset-2 font-medium">
                            Reset Semua Filter ✕
                        </a>
                    @endif
                </div>

                <div class="flex flex-wrap gap-2.5">
                    @foreach($filterPresets as $preset)
                        @php
                            $isActive = false;
                            if ($preset['id'] === 'all') {
                                $isActive = empty($category) && empty($tag);
                            } else {
                                $isActive = ($category === $preset['category']) || ($tag === ($preset['tag'] ?? null));
                            }
                            $count = $preset['category'] ? ($categoryCounts[$preset['category']] ?? 0) : $categoryCounts['all'];
                        @endphp
                        <a href="{{ route('kandidat.home', array_merge(request()->query(), [
                                'category' => $preset['category'],
                                'tag' => $preset['tag'] ?? null,
                                'page' => 1
                           ])) }}"
                           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all border focus-visible:ring-2 focus-visible:ring-blue-400 focus-visible:outline-none {{ $isActive ? 'bg-white text-blue-900 border-white shadow-md font-bold' : 'bg-blue-800/60 text-blue-100 hover:bg-blue-800 border-blue-700/60 hover:text-white' }}"
                           aria-pressed="{{ $isActive ? 'true' : 'false' }}">
                            
                            <!-- Icon -->
                            @if($preset['icon'] === 'wheelchair')
                                <span class="text-base" aria-hidden="true">♿</span>
                            @elseif($preset['icon'] === 'hands')
                                <span class="text-base" aria-hidden="true">🧏</span>
                            @elseif($preset['icon'] === 'screen-reader')
                                <span class="text-base" aria-hidden="true">🔊</span>
                            @elseif($preset['icon'] === 'sun')
                                <span class="text-base" aria-hidden="true">🧠</span>
                            @else
                                <span class="text-base" aria-hidden="true">✨</span>
                            @endif

                            <span>{{ $preset['label'] }}</span>

                            <!-- Dynamic Count Badge -->
                            <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold {{ $isActive ? 'bg-blue-100 text-blue-900' : 'bg-blue-900/80 text-blue-200' }}">
                                {{ $count }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

        </div>
    </section>

    <!-- ─── 2. GRID LOWONGAN ("Peluang yang sesuai untuk Anda") ──────────────── -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" aria-labelledby="vacancies-heading">
        
        <!-- Section Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 pb-4 border-b border-slate-200">
            <div>
                <h2 id="vacancies-heading" class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Peluang yang Sesuai untuk Anda
                </h2>
                <p class="text-slate-500 text-sm mt-1">
                    Menampilkan <strong class="text-slate-800">{{ $vacancies->total() }}</strong> lowongan kerja dengan fasilitas aksesibilitas terjamin.
                </p>
            </div>

            <!-- Job Type Quick Selector Pills -->
            <div class="flex items-center gap-1.5 bg-white p-1 rounded-xl border border-slate-200 self-start sm:self-auto shadow-2xs">
                <a href="{{ route('kandidat.home', array_merge(request()->query(), ['job_type' => null, 'page' => 1])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ empty($jobType) ? 'bg-blue-900 text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Semua Tipe
                </a>
                <a href="{{ route('kandidat.home', array_merge(request()->query(), ['job_type' => 'hybrid', 'page' => 1])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $jobType === 'hybrid' ? 'bg-blue-900 text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Hybrid
                </a>
                <a href="{{ route('kandidat.home', array_merge(request()->query(), ['job_type' => 'remote', 'page' => 1])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $jobType === 'remote' ? 'bg-blue-900 text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Remote / WFH
                </a>
                <a href="{{ route('kandidat.home', array_merge(request()->query(), ['job_type' => 'full-time', 'page' => 1])) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $jobType === 'full-time' ? 'bg-blue-900 text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Penuh Waktu
                </a>
            </div>
        </div>

        <!-- Job Cards Grid -->
        @if($vacancies->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($vacancies as $job)
                    @php
                        $isSaved = in_array($job->id, $savedJobIds);
                        $hasApplied = in_array($job->id, $appliedJobIds);
                        $facilities = $job->facility_details;
                    @endphp
                    
                    <article class="bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:border-blue-300 transition-all duration-300 flex flex-col justify-between overflow-hidden group focus-within:ring-2 focus-within:ring-blue-600"
                             aria-label="Lowongan {{ $job->title }} di {{ $job->company->company_name ?? 'Perusahaan' }}">
                        
                        <!-- Top Card Header -->
                        <div class="p-6 pb-4">
                            
                            <!-- Company Row & Bookmark Button -->
                            <div class="flex items-start justify-between gap-3 mb-4">
                                <div class="flex items-center gap-3">
                                    <!-- Company Avatar / Logo -->
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-900 to-indigo-700 text-white font-extrabold text-base flex items-center justify-center shadow-xs flex-shrink-0 group-hover:scale-105 transition-transform">
                                        {{ strtoupper(substr($job->company->company_name ?? 'KS', 3, 2)) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider line-clamp-1">
                                            {{ $job->company->company_name ?? 'Mitra KerjaSetara' }}
                                        </h3>
                                        <div class="flex items-center gap-1 text-[11px] font-semibold text-emerald-700 mt-0.5">
                                            <svg class="w-3.5 h-3.5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            <span>Mitra Inklusif</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bookmark Button Form -->
                                <form action="{{ route('kandidat.lowongan.bookmark', $job->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none {{ $isSaved ? 'bg-rose-50 text-rose-600 hover:bg-rose-100' : 'bg-slate-50 text-slate-400 hover:bg-slate-100 hover:text-slate-600' }}"
                                            aria-label="{{ $isSaved ? 'Hapus bookmark lowongan ' . $job->title : 'Simpan lowongan ' . $job->title }}">
                                        <svg class="w-5 h-5 {{ $isSaved ? 'fill-current' : 'stroke-current fill-none' }}" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>

                            <!-- Position Title -->
                            <h4 class="text-lg font-bold text-slate-900 group-hover:text-blue-900 transition-colors line-clamp-1 mb-2">
                                <a href="{{ route('kandidat.lowongan.show', $job->id) }}" class="focus-visible:outline-none focus-visible:underline">
                                    {{ $job->title }}
                                </a>
                            </h4>

                            <!-- Job Type & Location Meta -->
                            <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500 font-medium mb-3">
                                <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-700 px-2.5 py-1 rounded-lg">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    {{ $job->location }}
                                </span>
                                <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-800 px-2.5 py-1 rounded-lg font-semibold">
                                    {{ $job->job_type_label }}
                                </span>
                                @if($hasApplied)
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-800 px-2 py-1 rounded-lg font-bold text-[11px] border border-emerald-200">
                                        ✓ Sudah Dilamar
                                    </span>
                                @endif
                            </div>

                            <!-- Salary Range -->
                            <div class="mb-4">
                                <span class="text-xs text-slate-400 block font-medium">Estimasi Kompensasi:</span>
                                <span class="text-sm font-extrabold text-slate-900">
                                    {{ $job->formatted_salary }}
                                    <span class="text-xs font-normal text-slate-500">/ bulan</span>
                                </span>
                            </div>

                            <!-- Accessibility Badges List -->
                            <div class="pt-3 border-t border-slate-100">
                                <span class="text-[11px] uppercase tracking-wider font-bold text-slate-400 block mb-2">
                                    Fasilitas Inklusif Tersedia:
                                </span>
                                <div class="flex flex-wrap gap-1.5">
                                    @php
                                        $displayFacilities = array_slice($facilities, 0, 3, true);
                                        $remainingCount = count($facilities) - count($displayFacilities);
                                    @endphp
                                    @foreach($displayFacilities as $tagKey => $facility)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-900 border border-indigo-150/70"
                                              title="{{ $facility['desc'] ?? '' }}">
                                            @if(($facility['icon'] ?? '') === 'wheelchair')
                                                <span aria-hidden="true">♿</span>
                                            @elseif(($facility['icon'] ?? '') === 'hands')
                                                <span aria-hidden="true">🧏</span>
                                            @elseif(($facility['icon'] ?? '') === 'screen-reader')
                                                <span aria-hidden="true">🔊</span>
                                            @elseif(($facility['icon'] ?? '') === 'sun')
                                                <span aria-hidden="true">🧠</span>
                                            @else
                                                <span aria-hidden="true">✓</span>
                                            @endif
                                            <span>{{ $facility['badge'] ?? $facility['label'] }}</span>
                                        </span>
                                    @endforeach

                                    @if($remainingCount > 0)
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-600">
                                            +{{ $remainingCount }} Fasilitas
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <!-- Card Footer / Actions -->
                        <div class="px-6 py-4 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between gap-3">
                            <span class="text-xs font-medium text-slate-400">
                                Ditutup: {{ $job->deadline ? $job->deadline->diffForHumans() : 'Segera' }}
                            </span>

                            <a href="{{ route('kandidat.lowongan.show', $job->id) }}"
                               class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white font-bold text-xs rounded-xl shadow-xs transition-all focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:outline-none">
                                <span>Lihat Detail</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>

                    </article>
                @endforeach
            </div>

            <!-- Accessible Pagination -->
            <div class="mt-12 flex justify-center" role="navigation" aria-label="Navigasi Halaman Lowongan">
                {{ $vacancies->links() }}
            </div>

        @else
            <!-- Empty State -->
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 max-w-lg mx-auto shadow-sm">
                <div class="w-16 h-16 bg-blue-50 text-blue-800 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl">
                    🔍
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Tidak Ditemukan Lowongan yang Sesuai</h3>
                <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                    Kami belum menemukan lowongan dengan kriteria kata kunci atau filter fasilitas yang Anda pilih. Coba sesuaikan kata kunci atau bersihkan filter.
                </p>
                <a href="{{ route('kandidat.home') }}" 
                   class="btn-primary text-sm py-2.5 px-5">
                    Lihat Semua Lowongan
                </a>
            </div>
        @endif

    </section>

    <!-- ─── 3. SECTION MITRA PERUSAHAAN INKLUSIF ────────────────────────────── -->
    <section class="bg-white border-t border-slate-200 py-16 px-4 sm:px-6 lg:px-8" aria-labelledby="partners-heading">
        <div class="max-w-7xl mx-auto">
            
            <div class="text-center max-w-3xl mx-auto mb-12">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-xs font-bold border border-emerald-200 mb-3">
                    <span>🏢</span> Jaringan Mitra Berkomitmen
                </div>
                <h2 id="partners-heading" class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Mitra Perusahaan Inklusif Terverifikasi
                </h2>
                <p class="text-slate-500 text-sm mt-2 leading-relaxed">
                    Perusahaan pelopor di Indonesia yang telah memenuhi standardisasi tempat kerja ramah disabilitas dan aktif menyerap talenta inklusif.
                </p>
            </div>

            <!-- Partners Cards Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($partnerCompanies as $company)
                    <div class="p-5 rounded-2xl border border-slate-150 bg-slate-50/50 hover:bg-white hover:border-blue-300 hover:shadow-lg transition-all duration-200 flex flex-col justify-between group">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <!-- Company Initials Badge -->
                                <div class="w-10 h-10 rounded-xl bg-blue-900 text-white font-extrabold text-sm flex items-center justify-center shadow-xs group-hover:bg-blue-800 transition-colors">
                                    {{ strtoupper(substr($company->company_name, 3, 2)) }}
                                </div>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                    ✓ Audit Inklusif
                                </span>
                            </div>

                            <h3 class="font-bold text-sm text-slate-900 line-clamp-1 mb-1 group-hover:text-blue-900 transition-colors">
                                {{ $company->company_name }}
                            </h3>
                            <p class="text-xs text-slate-500 line-clamp-1 mb-3">
                                {{ $company->industry ?? 'Teknologi & Bisnis' }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-200/60 flex items-center justify-between text-xs">
                            <span class="text-slate-400 font-medium">Lowongan Aktif:</span>
                            <span class="font-extrabold text-blue-900 bg-blue-50 px-2 py-0.5 rounded-md">
                                {{ $company->active_vacancies_count }} Posisi
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Call to Action Banner for Candidate Profile -->
            <div class="mt-14 bg-gradient-to-r from-blue-900 to-indigo-900 rounded-3xl p-8 sm:p-10 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl">
                <div class="max-w-xl">
                    <span class="text-xs font-bold text-blue-300 uppercase tracking-wider block mb-1">Fitur Unggulan KerjaSetara</span>
                    <h3 class="text-xl sm:text-2xl font-black mb-2">
                        Ingin Tahu Tingkat Kecocokan Aksesibilitas Anda?
                    </h3>
                    <p class="text-blue-150 text-sm leading-relaxed text-blue-100">
                        Buka halaman detail lowongan untuk melihat perhitungan otomatis <em>Matching Engine</em> yang memetakan fasilitas perusahaan terhadap kebutuhan akomodasi Anda.
                    </p>
                </div>
                <a href="#hero-heading"
                   class="px-6 py-3 bg-white text-blue-900 hover:bg-blue-50 font-bold text-sm rounded-xl shadow-md transition-all flex items-center gap-2 flex-shrink-0">
                    <span>Mulai Jelajahi Posisi</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                </a>
            </div>

        </div>
    </section>

</div>
@endsection
