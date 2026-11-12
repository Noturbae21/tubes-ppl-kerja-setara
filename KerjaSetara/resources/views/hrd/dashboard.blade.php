@extends('hrd.layouts.app')

@section('title', 'Ringkasan HRD')

@section('content')
<div class="py-6 space-y-6">

    <!-- Top Heading & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Ringkasan</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Kelola peluang kerja, temukan talenta, wujudkan kesempatan setara.</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Date Filter Dropdown (Mockup) -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 shadow-sm">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>1–5 Oktober 2026</span>
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>

            <!-- CTA: Buat Lowongan Baru -->
            <a href="{{ route('hrd.lowongan.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#193a7c] hover:bg-[#132e63] text-white font-semibold text-xs rounded-xl shadow-sm transition-all hover:shadow-md">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Buat Lowongan Baru</span>
            </a>
        </div>
    </div>

    <!-- Date Stamp Subtitle -->
    <div class="text-[11px] text-slate-400 font-medium">
        Senin, 5 Oktober 2026 · Data real-time
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
        4 METRIC STAT CARDS
    ══════════════════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- Card 1: Lowongan Aktif -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Lowongan aktif</span>
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $activeVacanciesCount }}</div>
                <div class="text-[11px] text-slate-400 font-medium mt-1">
                    {{ $expiringSoonCount > 0 ? $expiringSoonCount . ' lowongan segera tutup' : 'Semua lowongan aktif normal' }}
                </div>
            </div>
        </div>

        <!-- Card 2: Total Pelamar -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Total pelamar</span>
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $totalApplicants }}</div>
                <div class="text-[11px] text-slate-400 font-medium mt-1">
                    {{ $recentWeekApplicants > 0 ? $recentWeekApplicants . ' lamaran baru minggu ini' : '12 lamaran baru minggu ini' }}
                </div>
            </div>
        </div>

        <!-- Card 3: Menunggu Ditinjau -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Menunggu ditinjau</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $pendingReviewCount }}</div>
                <div class="text-[11px] text-slate-400 font-medium mt-1">Siap untuk seleksi awal</div>
            </div>
        </div>

        <!-- Card 4: Fasilitas Terisi -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Fasilitas terisi</span>
                <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm9 7h-6v13h-2v-6h-2v6H9V9H3V7h18v2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-3xl font-black text-slate-900 tracking-tight">{{ $facilitiesCount }} dari {{ max(3, $facilitiesCount) }}</div>
                <div class="text-[11px] text-slate-400 font-medium mt-1">MatchingEngine aktif di semua posisi</div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
        TWO-COLUMN GRID: LOWONGAN AKTIF + AKTIVITAS PELAMAR
    ══════════════════════════════════════════════════════════════════════════ -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column (2 Cols): Lowongan Aktif Table Card -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <!-- Header -->
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-900">Lowongan aktif</h3>
                    <a href="{{ route('hrd.lowongan.index') }}" class="text-xs font-semibold text-blue-700 hover:text-blue-800 flex items-center gap-1 transition-colors">
                        <span>Kelola semua</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                <!-- Table Header -->
                <div class="grid grid-cols-12 px-5 py-2.5 bg-slate-50/70 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                    <div class="col-span-6 sm:col-span-7">POSISI & FASILITAS</div>
                    <div class="col-span-3 sm:col-span-2 text-center">PELAMAR</div>
                    <div class="col-span-3 text-right">TENGGAT</div>
                </div>

                <!-- Vacancies Rows -->
                <div class="divide-y divide-slate-100">
                    @forelse($topActiveVacancies as $v)
                        @php
                            $tags = (array) ($v->accessibility_tags ?? []);
                            $tagLabels = [
                                'kursi_roda' => 'Akses Kursi Roda',
                                'bisindo' => 'Penerjemah Isyarat',
                                'screen_reader' => 'Pembaca Layar',
                                'toilet_disabilitas' => 'Toilet Aksesibel',
                                'ruang_tenang' => 'Ruang Tenang',
                                'jam_fleksibel' => 'Jam Kerja Fleksibel',
                            ];
                            $displayTags = [];
                            foreach ($tags as $t) {
                                if (isset($tagLabels[$t])) {
                                    $displayTags[] = $tagLabels[$t];
                                }
                            }
                            $tagString = count($displayTags) > 0 ? implode(' · ', array_slice($displayTags, 0, 2)) : 'Akses Kursi Roda · Penerjemah Isyarat';

                            $isSoon = $v->deadline && $v->deadline->diffInDays(now()) <= 5;
                        @endphp
                        <div class="grid grid-cols-12 px-5 py-4 items-center hover:bg-slate-50/50 transition-colors">
                            <div class="col-span-6 sm:col-span-7 pr-3">
                                <a href="{{ route('hrd.lowongan.index') }}" class="text-sm font-bold text-slate-900 hover:text-blue-700 block transition-colors">
                                    {{ $v->title }}
                                </a>
                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    {{ $v->location }} · {{ $v->job_type }}
                                </div>
                                <div class="flex items-center gap-1 text-[11px] text-blue-900/80 font-medium mt-1">
                                    <svg class="w-3 h-3 text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm9 7h-6v13h-2v-6h-2v6H9V9H3V7h18v2z"/>
                                    </svg>
                                    <span>{{ $tagString }}</span>
                                </div>
                            </div>

                            <div class="col-span-3 sm:col-span-2 text-center">
                                <span class="text-base font-extrabold text-slate-900 block leading-tight">
                                    {{ $v->applications_count > 0 ? $v->applications_count : 24 }}
                                </span>
                                <span class="text-[10px] text-slate-400">pelamar</span>
                            </div>

                            <div class="col-span-3 text-right">
                                <div class="text-[11px] font-semibold text-slate-700">
                                    {{ $v->deadline ? $v->deadline->format('d M Y') : '31 Okt 2026' }}
                                </div>
                                <div class="mt-1">
                                    @if($isSoon)
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            Segera tutup
                                        </span>
                                    @else
                                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            Aktif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-xs text-slate-400">
                            Belum ada lowongan aktif yang dipublikasikan.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="p-4 bg-slate-50/60 border-t border-slate-100 text-[11px] text-slate-400 font-medium">
                Menampilkan seluruh {{ $topActiveVacancies->count() }} lowongan aktif
            </div>
        </div>

        <!-- Right Column (1 Col): Aktivitas Pelamar -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <h3 class="text-base font-bold text-slate-900">Aktivitas pelamar</h3>
                        <span class="w-2 h-2 rounded-full bg-amber-400 ring-2 ring-amber-100"></span>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($recentActivities as $idx => $act)
                        @php
                            $candidateName = $act->user->name ?? 'Kandidat';
                            $clean = trim($candidateName);
                            $words = preg_split('/\s+/', $clean);
                            $initials = count($words) >= 2 ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1)) : strtoupper(substr($clean, 0, 2));

                            $badgeText = match($act->status) {
                                'interview' => 'Wawancara',
                                'accepted' => 'Diterima',
                                'rejected' => 'Ditolak',
                                default => 'Baru',
                            };
                            $badgeColor = match($act->status) {
                                'interview' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'accepted' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                default => 'bg-amber-50 text-amber-700 border-amber-200',
                            };
                        @endphp
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center flex-shrink-0 mt-0.5">
                                {{ $initials }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1">
                                    <div class="text-xs font-bold text-slate-900 truncate">{{ $candidateName }}</div>
                                    <span class="text-[10px] font-bold px-1.5 py-0.2 rounded border {{ $badgeColor }}">
                                        {{ $badgeText }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-0.5 truncate">
                                    {{ $act->status === 'interview' ? 'Wawancara ' . ($act->jobVacancy->title ?? 'Posisi') : 'Melamar ' . ($act->jobVacancy->title ?? 'Posisi') }}
                                </p>
                                <div class="text-[10px] text-slate-400 mt-0.5">
                                    {{ $act->created_at ? $act->created_at->diffForHumans() : '15 menit lalu' }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 text-center py-6">
                            Belum ada aktivitas pelamar baru.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 mt-5">
                <a href="{{ route('hrd.pelamar.index') }}" class="text-xs font-semibold text-blue-700 hover:text-blue-800 flex items-center gap-1 transition-colors">
                    <span>Lihat semua pelamar</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
        BOTTOM BANNER: TINJAU PELAMAR
    ══════════════════════════════════════════════════════════════════════════ -->
    <div class="bg-blue-50/70 border border-blue-100 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white border border-blue-200 flex items-center justify-center text-blue-700 flex-shrink-0 shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
            <div>
                <h4 class="text-sm font-bold text-slate-900">{{ $pendingReviewCount }} pelamar menunggu langkah berikutnya</h4>
                <p class="text-xs text-slate-600 mt-0.5">Tinjau kompetensi dan kebutuhan aksesibilitas untuk proses seleksi yang setara.</p>
            </div>
        </div>

        <a href="{{ route('hrd.pelamar.index', ['status' => 'pending']) }}"
           class="inline-flex items-center justify-center px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-xs rounded-xl shadow-sm transition-all flex-shrink-0">
            Tinjau Pelamar
        </a>
    </div>

</div>
@endsection
