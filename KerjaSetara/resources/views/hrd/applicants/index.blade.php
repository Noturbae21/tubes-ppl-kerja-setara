@extends('hrd.layouts.app')

@section('title', 'Kelola Pelamar Kerja')

@section('content')
<div class="py-6 space-y-6" x-data="{
    // Modal Evaluasi State
    modalOpen: false,
    evalData: {
        id: '',
        name: 'Nadia Saraswati',
        initials: 'NS',
        headline: 'Product Designer',
        vacancy_title: 'UI/UX Designer',
        status: 'pending',
        match_percentage: 95,
        resume_name: 'Unduh CV.pdf',
        resume_url: '#',
        portfolio_url: '#',
        accessibility_notes: 'Membutuhkan ramp kursi roda dan lingkungan kerja yang tenang',
        experience: {
            role: 'Product Designer',
            period: '2023–2026 · Produk digital B2B',
            description: 'Merancang alur pengguna, wireframe, dan prototipe UI/UX. Berkolaborasi dengan tim produk dan engineer untuk mengembangkan design system serta menguji kemudahan penggunaan.',
            skills: ['Figma', 'UI/UX Design', 'Usability Testing']
        },
        matching_features: [
            { label: 'Akses Kursi Roda (Tersedia di Kantor)', available: true },
            { label: 'Ruang Tenang / Flexi-time (Tersedia)', available: true },
            { label: 'Software Pendukung (Tersedia)', available: true }
        ]
    },
    openEvaluationModal(appId) {
        fetch('{{ url('hrd/pelamar') }}/' + appId)
            .then(res => res.json())
            .then(data => {
                this.evalData = data;
                this.modalOpen = true;
            })
            .catch(() => {
                this.modalOpen = true;
            });
    }
}">

    <!-- Top Heading -->
    <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Kelola Pelamar Kerja</h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Tinjau kandidat, evaluasi skor MatchingEngine, dan perbarui status rekrutmen.</p>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('hrd.pelamar.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">

            <!-- Search Nama Kandidat -->
            <div class="sm:col-span-5 relative">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nama kandidat</label>
                <div class="relative">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari nama kandidat..."
                           class="w-full pl-10 pr-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>
            </div>

            <!-- Posisi Lowongan -->
            <div class="sm:col-span-3">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Posisi lowongan</label>
                <div class="relative">
                    <select name="vacancy_id"
                            onchange="this.form.submit()"
                            class="w-full appearance-none px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-medium focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="all">Semua Posisi</option>
                        @foreach($vacancies as $vac)
                            <option value="{{ $vac->id }}" {{ $vacancyId == $vac->id ? 'selected' : '' }}>
                                {{ $vac->title }}
                            </option>
                        @endforeach
                    </select>
                    <svg class="w-4 h-4 text-slate-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>

            <!-- Status Rekrutmen -->
            <div class="sm:col-span-3">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status rekrutmen</label>
                <div class="relative">
                    <select name="status"
                            onchange="this.form.submit()"
                            class="w-full appearance-none px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-medium focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="all" {{ empty($status) || $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                        <option value="interview" {{ $status === 'interview' ? 'selected' : '' }}>Wawancara</option>
                        <option value="accepted" {{ $status === 'accepted' ? 'selected' : '' }}>Diterima</option>
                        <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <svg class="w-4 h-4 text-slate-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>

            <!-- Reset Filter -->
            <div class="sm:col-span-1 pt-4 text-right">
                <a href="{{ route('hrd.pelamar.index') }}" class="text-xs font-bold text-blue-700 hover:underline block truncate">
                    Semua Posisi
                </a>
            </div>
        </form>
    </div>

    <!-- Applicants Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/60 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4 w-10 text-center">
                            <input type="checkbox" class="rounded text-blue-700 focus:ring-blue-600 w-4 h-4">
                        </th>
                        <th class="py-3.5 px-4">Nama Kandidat & Role</th>
                        <th class="py-3.5 px-4">Posisi Dilamar</th>
                        <th class="py-3.5 px-4 text-center">Skor Matching</th>
                        <th class="py-3.5 px-4">Tanggal Lamar</th>
                        <th class="py-3.5 px-6">Status & Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($applications as $app)
                        @php
                            $candidate = $app->user;
                            $candidateName = $candidate->name ?? 'Kandidat';
                            $headline = $candidate->headline ?? 'Product Designer';
                            $clean = trim($candidateName);
                            $words = preg_split('/\s+/', $clean);
                            $initials = count($words) >= 2 ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1)) : strtoupper(substr($clean, 0, 2));

                            $statusStyle = match($app->status) {
                                'interview' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'accepted' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                default => 'bg-amber-50 text-amber-700 border-amber-200',
                            };
                            $statusText = match($app->status) {
                                'interview' => 'Wawancara',
                                'accepted' => 'Diterima',
                                'rejected' => 'Ditolak',
                                default => 'Menunggu Review',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors" x-data="{ actionOpen: false }">
                            <!-- Checkbox -->
                            <td class="py-4 px-4 text-center">
                                <input type="checkbox" class="rounded text-blue-700 focus:ring-blue-600 w-4 h-4">
                            </td>

                            <!-- Candidate Name & Role -->
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-slate-100 border border-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                        {{ $initials }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-bold text-slate-900 block truncate">{{ $candidateName }}</span>
                                        <span class="text-[11px] text-slate-400 block truncate">{{ $headline }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Posisi Dilamar -->
                            <td class="py-4 px-4 font-semibold text-slate-800">
                                {{ $app->jobVacancy->title ?? 'UI/UX Designer' }}
                            </td>

                            <!-- Skor Matching -->
                            <td class="py-4 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ $app->match_percentage }}% Match
                                </span>
                            </td>

                            <!-- Tanggal Lamar -->
                            <td class="py-4 px-4 text-slate-600 font-medium">
                                {{ $app->created_at ? $app->created_at->format('j M Y') : '5 Okt 2026' }}
                            </td>

                            <!-- Status & Tindakan -->
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $statusStyle }}">
                                            {{ $statusText }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <!-- Tombol Tinjau Pelamar -->
                                        <button type="button"
                                                @click="openEvaluationModal({{ $app->id }})"
                                                class="text-xs font-bold text-slate-700 hover:text-blue-700 transition-colors">
                                            Tinjau Pelamar
                                        </button>

                                        <!-- Dropdown Ubah Status -->
                                        <div class="relative" @click.away="actionOpen = false">
                                            <button type="button"
                                                    @click="actionOpen = !actionOpen"
                                                    class="flex items-center gap-1 text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-50 border border-slate-200 px-2 py-1 rounded-lg">
                                                <span>Ubah Status</span>
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>

                                            <!-- Dropdown Menu -->
                                            <div x-show="actionOpen"
                                                 x-cloak
                                                 class="absolute right-0 mt-1 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 py-1.5 z-40">
                                                <!-- Option 1: Menunggu Review -->
                                                <form method="POST" action="{{ route('hrd.pelamar.updateStatus', $app) }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="pending">
                                                    <button type="submit" class="w-full text-left px-3.5 py-2 text-xs font-medium text-amber-700 hover:bg-amber-50 flex items-center gap-2">
                                                        <span>🕒</span>
                                                        <span>Menunggu Review</span>
                                                    </button>
                                                </form>

                                                <!-- Option 2: Jadwalkan Wawancara -->
                                                <form method="POST" action="{{ route('hrd.pelamar.updateStatus', $app) }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="interview">
                                                    <button type="submit" class="w-full text-left px-3.5 py-2 text-xs font-medium text-blue-700 hover:bg-blue-50 flex items-center gap-2">
                                                        <span>📅</span>
                                                        <span>Jadwalkan Wawancara</span>
                                                    </button>
                                                </form>

                                                <!-- Option 3: Terima Kandidat -->
                                                <form method="POST" action="{{ route('hrd.pelamar.updateStatus', $app) }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="accepted">
                                                    <button type="submit" class="w-full text-left px-3.5 py-2 text-xs font-medium text-emerald-700 hover:bg-emerald-50 flex items-center gap-2">
                                                        <span>✓</span>
                                                        <span>Terima Kandidat (Diterima)</span>
                                                    </button>
                                                </form>

                                                <!-- Option 4: Tolak Kandidat -->
                                                <form method="POST" action="{{ route('hrd.pelamar.updateStatus', $app) }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="w-full text-left px-3.5 py-2 text-xs font-medium text-rose-700 hover:bg-rose-50 flex items-center gap-2">
                                                        <span>ⓧ</span>
                                                        <span>Tolak Kandidat (Ditolak)</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-xs text-slate-400">
                                Tidak ada data pelamar yang sesuai dengan pencarian atau filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer Pagination -->
        <div class="p-4 bg-slate-50/60 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 font-medium">
            <div>
                Menampilkan 1–{{ $applications->count() }} dari {{ $applications->total() }} pelamar · {{ $company->company_name }}
            </div>
            <div>
                {{ $applications->links() }}
            </div>
        </div>
    </div>

    <!-- Notice Footer -->
    <div class="flex items-center gap-2 text-xs text-slate-400">
        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>Skor MatchingEngine bersifat ilustratif. Tinjau kompetensi dan kebutuhan aksesibilitas sebelum mengambil keputusan.</span>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
        MODAL EVALUASI KANDIDAT & MATCHINGENGINE (Screenshot 4 - Detail)
    ══════════════════════════════════════════════════════════════════════════ -->
    <div x-show="modalOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div @click.away="modalOpen = false"
             class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-7 shadow-2xl border border-slate-100 relative">

            <!-- Close [x] -->
            <button @click="modalOpen = false" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Header Modal -->
            <div>
                <h3 class="text-lg font-black text-slate-900 tracking-tight">Evaluasi Kandidat & MatchingEngine</h3>
                <p class="text-xs text-slate-500 mt-0.5">Detail profil dan tingkat kesesuaian aksesibilitas pelamar.</p>
            </div>

            <!-- Candidate Identity Card with CV & Portfolio -->
            <div class="mt-4 p-4 rounded-2xl bg-slate-50/80 border border-slate-200/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-blue-100 text-blue-800 font-black text-sm flex items-center justify-center flex-shrink-0"
                         x-text="evalData.initials">
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900" x-text="evalData.name"></h4>
                        <p class="text-xs text-slate-500" x-text="evalData.headline"></p>
                        <span class="text-[11px] text-blue-700 font-semibold block mt-0.5">
                            Posisi dilamar: <span x-text="evalData.vacancy_title"></span>
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a :href="evalData.resume_url"
                       target="_blank"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 shadow-sm transition-colors">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Unduh CV.pdf</span>
                    </a>

                    <a :href="evalData.portfolio_url"
                       target="_blank"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 shadow-sm transition-colors">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <span>Lihat Portofolio</span>
                    </a>
                </div>
            </div>

            <!-- Two-Column Grid: Detail & MatchingEngine Gauge -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">

                <!-- Left Column: Catatan Aksesibilitas & Ringkasan Pengalaman -->
                <div class="space-y-4">
                    <!-- Catatan Aksesibilitas -->
                    <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm">
                        <span class="text-xs font-bold text-slate-800 block mb-1">Catatan Aksesibilitas Kandidat</span>
                        <p class="text-xs text-slate-600 leading-relaxed" x-text="evalData.accessibility_notes"></p>
                    </div>

                    <!-- Ringkasan Pengalaman Kerja -->
                    <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-sm space-y-2">
                        <span class="text-xs font-bold text-slate-800 block">Ringkasan Pengalaman Kerja</span>
                        <div>
                            <span class="text-xs font-bold text-slate-900 block" x-text="evalData.experience?.role"></span>
                            <span class="text-[10px] text-slate-400 block" x-text="evalData.experience?.period"></span>
                        </div>
                        <p class="text-[11px] text-slate-600 leading-relaxed" x-text="evalData.experience?.description"></p>
                        <div class="flex flex-wrap gap-1.5 pt-1">
                            <template x-for="skill in evalData.experience?.skills ?? []" :key="skill">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-700 border border-blue-100" x-text="skill"></span>
                            </template>
                        </div>
                        <span class="text-[10px] text-slate-400 block pt-1">Ringkasan pengalaman bersifat ilustratif.</span>
                    </div>
                </div>

                <!-- Right Column: MatchingEngine Progress & Facilities List -->
                <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-800 block mb-3">MatchingEngine</span>

                        <!-- Circular Gauge Simulation -->
                        <div class="flex flex-col items-center justify-center py-2">
                            <div class="w-24 h-24 rounded-full border-4 border-emerald-500 border-t-transparent flex flex-col items-center justify-center text-center shadow-inner">
                                <span class="text-xl font-black text-slate-900 leading-tight" x-text="evalData.match_percentage + '%'"></span>
                                <span class="text-[9px] font-bold text-emerald-600 uppercase">Match</span>
                            </div>
                            <span class="text-[11px] text-slate-400 font-medium mt-2">Kesesuaian aksesibilitas</span>
                        </div>

                        <!-- Check items list -->
                        <div class="space-y-2 mt-4 text-xs text-slate-700">
                            <template x-for="feat in evalData.matching_features ?? []" :key="feat.label">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-[11px] font-medium" x-text="feat.label"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <span class="text-[10px] text-slate-400 mt-4 block">
                        Skor ilustratif untuk mendukung evaluasi, bukan keputusan akhir.
                    </span>
                </div>
            </div>

            <!-- Footer Status Selector & Submit -->
            <form :action="'{{ url('hrd/pelamar') }}/' + evalData.id + '/status'" method="POST" class="mt-6 pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                @csrf
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <span class="text-xs font-bold text-slate-700 whitespace-nowrap">Status rekrutmen</span>
                    <select name="status"
                            x-model="evalData.status"
                            class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="pending">Menunggu Review</option>
                        <option value="interview">Wawancara</option>
                        <option value="accepted">Diterima</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    <button type="button"
                            @click="modalOpen = false"
                            class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">
                        Tutup
                    </button>
                    <button type="submit"
                            class="px-5 py-2 bg-[#193a7c] hover:bg-[#132e63] text-white rounded-xl text-xs font-bold shadow-sm">
                        Simpan & Perbarui Status
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

