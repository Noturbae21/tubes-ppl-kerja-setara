@extends('layouts.app')

@section('title', 'KerjaSetara — Platform Karir Inklusif No. 1 Indonesia')
@section('meta_description', 'KerjaSetara menghubungkan talenta penyandang disabilitas dengan perusahaan ramah aksesibilitas. Smart matching, 100% inklusif, tanpa diskriminasi.')

@section('content')

{{-- ═══════════════════════════════════════════════════════════════════════════
    HERO SECTION
═══════════════════════════════════════════════════════════════════════════ --}}
<section class="hero-bg py-16 lg:py-24 overflow-hidden" aria-label="Hero section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">

            <!-- Left: Content -->
            <div class="animate-fade-in-left">
                <!-- Badge -->
                <div class="badge-pill bg-blue-100 text-blue-800 mb-6 w-fit">
                    <svg class="w-3.5 h-3.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    Platform Karir Inklusif No. 1 di Indonesia
                </div>

                <!-- Heading -->
                <h1 class="text-4xl lg:text-5xl xl:text-6xl font-bold text-slate-900 leading-tight mb-5">
                    Setiap Karir Berhak Atas
                    <span class="gradient-text"> Kesempatan yang Setara.</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-slate-600 text-lg leading-relaxed mb-8 max-w-lg">
                    Menghubungkan talenta berbakati dengan perusahaan ramah disabilitas lewat teknologi <strong class="text-slate-800 font-semibold">smart matching</strong>.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 mb-8">
                    <a href="{{ route('register') }}" class="btn-primary" aria-label="Temukan lowongan kerja inklusif">
                        Temukan Lowongan
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('register') }}?role=hrd" class="btn-outline" aria-label="Pasang lowongan kerja untuk perusahaan">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Pasang Lowongan Kerja
                    </a>
                </div>

                <!-- Trust Indicators -->
                <div class="flex flex-wrap gap-4 text-sm text-slate-500">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $stats['companies'] ?? '500' }}+ Perusahaan Inklusif</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Tersertifikasi Aksesibilitas</span>
                    </div>
                </div>
            </div>

            <!-- Right: Illustration -->
            <div class="relative animate-fade-in-up animate-delay-200">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                    <img src="{{ asset('images/hero-illustration.png') }}"
                         alt="Ilustrasi tempat kerja inklusif: profesional pengguna kursi roda bekerja bersama kolega di kantor modern"
                         class="w-full h-auto object-cover"
                         loading="eager">
                </div>

                <!-- Floating card bottom-left -->
                <div class="absolute -bottom-4 -left-4 glass-card rounded-2xl p-4 shadow-xl animate-float hidden sm:flex items-center gap-3 max-w-52">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-900 leading-tight">Potensi tanpa batas.</p>
                        <p class="text-xs text-slate-500 leading-tight mt-0.5">Tempat kerja yang memahami Anda.</p>
                    </div>
                </div>

                <!-- Floating badge top-right -->
                <div class="absolute -top-3 -right-3 bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg hidden sm:block">
                    ✓ 100% Inklusif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════════════
    STATISTICS BAR
═══════════════════════════════════════════════════════════════════════════ --}}
<section id="stats-section" class="bg-white border-t border-b border-slate-100 py-12 shadow-sm" aria-label="Statistik KerjaSetara">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center reveal-on-scroll">
                <div class="text-3xl lg:text-4xl font-black text-blue-900 mb-1"
                     data-count="{{ max($stats['companies'] ?? 10, 500) }}"
                     data-suffix="+">
                    {{ $stats['companies'] ?? '500' }}+
                </div>
                <div class="text-sm text-slate-500 font-medium">Perusahaan Inklusif</div>
            </div>
            <div class="text-center reveal-on-scroll animate-delay-100">
                <div class="text-3xl lg:text-4xl font-black text-blue-900 mb-1"
                     data-count="12000"
                     data-suffix="+">
                    12,000+
                </div>
                <div class="text-sm text-slate-500 font-medium">Talenta Terdaftar</div>
            </div>
            <div class="text-center reveal-on-scroll animate-delay-200">
                <div class="text-3xl lg:text-4xl font-black text-blue-900 mb-1">
                    95%
                </div>
                <div class="text-sm text-slate-500 font-medium">Akurasi Matching Kerja</div>
            </div>
            <div class="text-center reveal-on-scroll animate-delay-300">
                <div class="text-3xl lg:text-4xl font-black text-blue-900 mb-1">
                    100%
                </div>
                <div class="text-sm text-slate-500 font-medium">Tanpa Diskriminasi</div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════════════
    HOW IT WORKS
═══════════════════════════════════════════════════════════════════════════ --}}
<section id="tentang" class="bg-blue-50 py-20" aria-labelledby="how-it-works-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-12">
            <p class="text-blue-600 text-xs font-bold tracking-widest uppercase mb-2">BAGAIMANA CARA KERJANYA</p>
            <h2 id="how-it-works-heading" class="text-3xl lg:text-4xl font-bold text-slate-900 mb-3">
                Bagaimana KerjaSetara Membantu Anda?
            </h2>
            <p class="text-slate-600 text-lg max-w-2xl">
                Tiga langkah sederhana untuk menemukan pekerjaan yang memahami kebutuhan Anda.
            </p>
        </div>

        <!-- 3 Step Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Step 1 -->
            <article class="bg-white rounded-2xl p-6 shadow-sm card-hover relative overflow-hidden reveal-on-scroll" aria-label="Langkah 1: Buat Profil Aksesibilitas">
                <span class="absolute top-4 right-5 text-6xl font-black text-blue-50 leading-none select-none" aria-hidden="true">01</span>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Buat Profil Aksesibilitas</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Ceritakan kebutuhan aksesibilitas dan kemampuan Anda. Profil Anda menjadi kunci matching yang tepat dan personal.
                </p>
            </article>

            <!-- Step 2 (Active) -->
            <article class="bg-white rounded-2xl p-6 shadow-sm step-card-active relative overflow-hidden reveal-on-scroll animate-delay-100" aria-label="Langkah 2: Smart Matching (Proses aktif)">
                <span class="absolute top-4 right-5 text-6xl font-black text-blue-50 leading-none select-none" aria-hidden="true">02</span>
                <div class="w-12 h-12 bg-blue-900 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-blue-900 mb-2">Smart Matching</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Algoritma kami mencocokkan profil Anda dengan perusahaan yang benar-benar siap menyambut dan mendukung Anda.
                </p>
                <div class="mt-4 flex items-center gap-1.5 text-xs text-blue-600 font-semibold">
                    <span class="w-2 h-2 bg-blue-600 rounded-full animate-pulse" aria-hidden="true"></span>
                    Teknologi AI Matching
                </div>
            </article>

            <!-- Step 3 -->
            <article class="bg-white rounded-2xl p-6 shadow-sm card-hover relative overflow-hidden reveal-on-scroll animate-delay-200" aria-label="Langkah 3: Lamar dengan Nyaman">
                <span class="absolute top-4 right-5 text-6xl font-black text-blue-50 leading-none select-none" aria-hidden="true">03</span>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-5">
                    <svg class="w-6 h-6 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Lamar dengan Nyaman</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Proses lamaran yang didesain aksesibel penuh. Tanpa hambatan, tanpa diskriminasi, dengan dukungan di setiap langkah.
                </p>
            </article>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════════════
    ACCESSIBILITY FILTER SECTION
═══════════════════════════════════════════════════════════════════════════ --}}
<section id="aksesibilitas" class="bg-white py-20" aria-labelledby="accessibility-heading"
         x-data="{
             activeTab: '{{ $filterCategory ?? 'fisik' }}',
             tabs: {
                 fisik: {
                     icon: '♿',
                     title: 'Aksesibilitas Fisik',
                     desc: 'Fasilitas fisik yang mendukung mobilitas',
                     features: ['Ramp', 'Lift', 'Toilet Khusus', 'Parkir Disabilitas', 'Jalur Pemandu']
                 },
                 komunikasi: {
                     icon: '🗣️',
                     title: 'Komunikasi Inklusif',
                     desc: 'Dukungan komunikasi untuk semua',
                     features: ['Juru Bicara Bahasa Isyarat', 'Subtitle / Closed Caption', 'Bahasa Ramah Kognitif', 'Audio Deskripsi']
                 },
                 digital: {
                     icon: '💻',
                     title: 'Dukungan Digital',
                     desc: 'Teknologi bantu untuk produktivitas',
                     features: ['Screen Reader Friendly', 'High Contrast Mode', 'Keyboard Navigation', 'Voice Command']
                 },
                 neurodiverse: {
                     icon: '🧠',
                     title: 'Lingkungan Neurodiverse',
                     desc: 'Lingkungan adaptif dan fleksibel',
                     features: ['Ruang Tenang', 'Fleksibel WFH', 'Manajemen Beban Adaptif', 'Jadwal Fleksibel']
                 }
             }
         }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-10">
            <p class="text-blue-600 text-xs font-bold tracking-widest uppercase mb-2">AKSESIBILITAS SESUAI KEBUTUHAN</p>
            <h2 id="accessibility-heading" class="text-3xl lg:text-4xl font-bold text-slate-900 mb-3">
                Temukan tempat kerja yang sesuai dengan Anda.
            </h2>
            <p class="text-slate-600 text-lg max-w-2xl">
                Kenali fitur aksesibilitas dari tiap lowongan. Pilih kebutuhan yang paling sesuai dengan Anda.
            </p>
        </div>

        <!-- Tab Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8"
             role="tablist"
             aria-label="Kategori aksesibilitas">
            <template x-for="(tab, key) in tabs" :key="key">
                <button @click="activeTab = key"
                        :aria-selected="(activeTab === key).toString()"
                        :class="activeTab === key
                            ? 'filter-tab-active'
                            : 'bg-white border border-slate-200 text-slate-700 hover:border-blue-300 hover:shadow-sm'"
                        class="rounded-xl p-4 text-left transition-all duration-200 cursor-pointer"
                        role="tab"
                        :aria-controls="'tab-content-' + key"
                        :id="'tab-' + key">
                    <div class="text-2xl mb-2" aria-hidden="true" x-text="tab.icon"></div>
                    <div class="font-semibold text-sm leading-tight" x-text="tab.title"></div>
                    <div class="text-xs mt-1 opacity-70" x-text="tab.desc"></div>
                </button>
            </template>
        </div>

        <!-- Tab Content -->
        <div class="bg-slate-50 rounded-2xl p-6 mb-6 min-h-32">
            <template x-for="(tab, key) in tabs" :key="key">
                <div x-show="activeTab === key"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     :id="'tab-content-' + key"
                     :aria-labelledby="'tab-' + key"
                     role="tabpanel">
                    <p class="text-sm font-semibold text-slate-700 mb-4">Fitur yang tersedia:</p>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="feature in tab.features" :key="feature">
                            <div class="badge-pill bg-white border border-slate-200 text-slate-700 shadow-sm">
                                <svg class="w-3.5 h-3.5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span x-text="feature"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        <!-- Search bar & link -->
        <form method="GET" action="{{ route('home') }}" class="flex flex-col sm:flex-row gap-3 items-center" role="search" aria-label="Cari lowongan aksesibel">
            <input type="hidden" name="category" :value="activeTab">
            <div class="flex-1 relative w-full">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text"
                       name="search"
                       value="{{ $searchQuery ?? '' }}"
                       placeholder="Cari lowongan yang sesuai dengan aksesibilitas Anda..."
                       class="input-field pl-10"
                       aria-label="Kata kunci pencarian lowongan">
            </div>
            <button type="submit" class="btn-primary whitespace-nowrap flex-shrink-0" aria-label="Mulai pencarian lowongan">
                Cari Lowongan
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </button>
        </form>

        <!-- Filtered Vacancies Result -->
        @if(isset($filteredVacancies) && $filteredVacancies->count() > 0 && ($searchQuery || request('category')))
            <div class="mt-8" aria-live="polite" aria-label="Hasil pencarian lowongan">
                <p class="text-sm text-slate-500 mb-4">Menampilkan {{ $filteredVacancies->count() }} lowongan untuk kategori <strong>{{ $tabs[$filterCategory] ?? $filterCategory }}</strong></p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($filteredVacancies as $vacancy)
                        <div class="bg-white rounded-xl p-5 border border-slate-200 card-hover">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-slate-900 text-sm leading-tight">{{ $vacancy->title }}</h3>
                                    <p class="text-xs text-slate-500 mt-1">{{ $vacancy->company->company_name ?? 'Perusahaan' }}</p>
                                </div>
                                @if($vacancy->company->is_inclusive_certified)
                                    <span class="badge-pill bg-green-50 text-green-700 text-xs ml-2 flex-shrink-0">✓ Inklusif</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-500 mb-3">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $vacancy->location }}
                                <span class="badge-pill bg-blue-50 text-blue-700 py-0.5">{{ ucfirst(str_replace('-', ' ', $vacancy->job_type)) }}</span>
                            </div>
                            <div class="flex flex-wrap gap-1">
                                @foreach(array_slice($vacancy->accessibility_tags ?? [], 0, 3) as $tag)
                                    <span class="badge-pill bg-slate-100 text-slate-600 text-xs py-0.5">{{ str_replace('_', ' ', $tag) }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════════════
    TESTIMONIALS CAROUSEL
═══════════════════════════════════════════════════════════════════════════ --}}
<section class="bg-slate-50 py-20" aria-labelledby="testimonials-heading"
         x-data="{
             current: 0,
             total: {{ $testimonials->count() }},
             visible: 3,
             get maxSlide() { return Math.max(0, this.total - this.visible); },
             prev() { this.current = this.current > 0 ? this.current - 1 : 0; },
             next() { this.current = this.current < this.maxSlide ? this.current + 1 : this.maxSlide; }
         }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
            <div>
                <p class="text-blue-600 text-xs font-bold tracking-widest uppercase mb-2">CERITA NYATA PENGGUNA</p>
                <h2 id="testimonials-heading" class="text-3xl lg:text-4xl font-bold text-slate-900 mb-2">
                    Kesempatan setara, dari dua sisi.
                </h2>
                <p class="text-slate-600">Kisah nyata dari kandidat dan HR yang telah merasakan perbedaannya.</p>
            </div>
            <!-- Navigation Arrows -->
            <div class="flex items-center gap-2 flex-shrink-0" role="group" aria-label="Navigasi carousel">
                <button @click="prev()"
                        :disabled="current === 0"
                        :class="current === 0 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-blue-900 hover:text-white hover:border-blue-900'"
                        class="w-10 h-10 rounded-full border-2 border-slate-300 flex items-center justify-center text-slate-600 transition-all"
                        aria-label="Testimoni sebelumnya">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button @click="next()"
                        :disabled="current >= maxSlide"
                        :class="current >= maxSlide ? 'opacity-40 cursor-not-allowed' : 'hover:bg-blue-900 hover:text-white hover:border-blue-900'"
                        class="w-10 h-10 rounded-full border-2 border-slate-300 flex items-center justify-center text-slate-600 transition-all"
                        aria-label="Testimoni berikutnya">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>

        <!-- Carousel -->
        <div class="overflow-hidden" aria-live="polite">
            <div class="flex gap-6 transition-transform duration-500 ease-in-out"
                 :style="'transform: translateX(calc(-' + current + ' * (100% / 3 + 0.5rem)))'">
                @forelse($testimonials as $testimonial)
                    <article class="bg-white rounded-2xl p-6 shadow-sm card-hover flex-shrink-0 w-full md:w-[calc(33.333%-1rem)]"
                             aria-label="Testimoni dari {{ $testimonial->name }}">
                        <!-- Quote -->
                        <div class="mb-5">
                            <svg class="w-8 h-8 text-blue-100 mb-3" fill="currentColor" viewBox="0 0 32 32" aria-hidden="true">
                                <path d="M10 8C5.6 8 2 11.6 2 16s3.6 8 8 8c4.4 0 8-3.6 8-8V8h-8zm20 0c-4.4 0-8 3.6-8 8s3.6 8 8 8V8h-8v8c0 0 0 0 0 0z"/>
                            </svg>
                            <p class="text-slate-700 text-sm leading-relaxed italic">
                                "{{ $testimonial->content }}"
                            </p>
                        </div>
                        <!-- Author -->
                        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0 avatar-{{ $testimonial->avatar_color }}">
                                {{ $testimonial->avatar_initials }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-slate-900 text-sm truncate">{{ $testimonial->name }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ $testimonial->position }}{{ $testimonial->company ? ' — ' . $testimonial->company : '' }}</p>
                            </div>
                            <span class="badge-pill text-xs flex-shrink-0 {{ $testimonial->role_type === 'kandidat' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                {{ $testimonial->role_type === 'kandidat' ? 'Kandidat' : 'HR / Recruiter' }}
                            </span>
                        </div>
                    </article>
                @empty
                    <div class="text-slate-500 text-sm py-8">Belum ada testimoni.</div>
                @endforelse
            </div>
        </div>

        <!-- Dots -->
        @if($testimonials->count() > 3)
            <div class="flex justify-center gap-2 mt-8" role="group" aria-label="Indikator posisi carousel">
                @for($i = 0; $i <= $testimonials->count() - 3; $i++)
                    <button @click="current = {{ $i }}"
                            :class="current === {{ $i }} ? 'bg-blue-900 w-6' : 'bg-slate-300 w-2'"
                            class="h-2 rounded-full transition-all duration-300"
                            :aria-label="'Ke slide {{ $i + 1 }}'"
                            :aria-current="(current === {{ $i }}).toString()">
                    </button>
                @endfor
            </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════════════
    CTA BANNER
═══════════════════════════════════════════════════════════════════════════ --}}
<section class="py-12 px-4 sm:px-6 lg:px-8" id="perusahaan" aria-label="Call to action">
    <div class="max-w-7xl mx-auto">
        <div class="cta-gradient rounded-3xl py-16 px-8 text-center relative overflow-hidden">
            <!-- Background decorative circles -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4" aria-hidden="true"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4" aria-hidden="true"></div>

            <div class="relative z-10">
                <h2 class="text-white text-3xl lg:text-4xl font-bold mb-3">
                    Siap Memulai Langkah Karir Inklusif Anda?
                </h2>
                <p class="text-blue-200 text-lg mb-8 max-w-2xl mx-auto">
                    Bergabunglah dengan ribuan talenta dan perusahaan yang percaya pada kesetaraan di KerjaSetara.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('register') }}"
                       class="bg-white text-blue-900 font-bold px-8 py-3.5 rounded-xl hover:bg-blue-50 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 inline-flex items-center justify-center gap-2"
                       aria-label="Daftar akun KerjaSetara sekarang">
                        Daftar Mulai
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <a href="#aksesibilitas"
                       class="border-2 border-white/40 text-white font-semibold px-8 py-3.5 rounded-xl hover:bg-white/10 transition-all inline-flex items-center justify-center gap-2"
                       aria-label="Pelajari fitur aksesibilitas">
                        Lihat Fitur Aksesibilitas
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
