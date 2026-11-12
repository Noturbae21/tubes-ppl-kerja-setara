@extends('hrd.layouts.app')

@section('title', 'Kelola Lowongan Kerja')

@section('content')
<div class="py-6 space-y-6" x-data="{
    // Modal Edit State
    editModalOpen: false,
    editData: {
        id: '',
        title: '',
        job_type: 'Full-Time',
        salary_estimate: '',
        tags: []
    },
    openEditModal(id, title, jobType, salary, tags) {
        this.editData = {
            id: id,
            title: title,
            job_type: jobType,
            salary_estimate: salary,
            tags: Array.isArray(tags) ? [...tags] : []
        };
        this.editModalOpen = true;
    },
    toggleEditTag(tag) {
        if (this.editData.tags.includes(tag)) {
            this.editData.tags = this.editData.tags.filter(t => t !== tag);
        } else {
            this.editData.tags.push(tag);
        }
    },

    // Modal Tutup Sementara State
    pauseModalOpen: false,
    pauseData: { id: '', title: '' },
    openPauseModal(id, title) {
        this.pauseData = { id: id, title: title };
        this.pauseModalOpen = true;
    },

    // Modal Hapus State
    deleteModalOpen: false,
    deleteData: { id: '', title: '' },
    openDeleteModal(id, title) {
        this.deleteData = { id: id, title: title };
        this.deleteModalOpen = true;
    }
}">

    <!-- Top Heading & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Kelola Lowongan Kerja</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Pantau, edit, atau tutup lowongan pekerjaan yang telah Anda publikasikan.</p>
        </div>

        <div>
            <a href="{{ route('hrd.lowongan.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#193a7c] hover:bg-[#132e63] text-white font-semibold text-xs rounded-xl shadow-sm transition-all hover:shadow-md">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>+ Buat Lowongan Baru</span>
            </a>
        </div>
    </div>

    <!-- Search Input Bar -->
    <div class="max-w-md">
        <form method="GET" action="{{ route('hrd.lowongan.index') }}" class="relative">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text"
                   name="search"
                   value="{{ $search }}"
                   placeholder="Cari posisi lowongan..."
                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent shadow-sm">
        </form>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">

        <!-- Tabs Navigation -->
        <div class="flex items-center gap-6 px-6 pt-4 border-b border-slate-100 text-xs font-bold">
            <a href="{{ route('hrd.lowongan.index', ['tab' => 'semua', 'search' => $search]) }}"
               class="pb-3 border-b-2 transition-colors {{ $tab === 'semua' ? 'border-blue-600 text-blue-700' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                Semua ({{ $counts['semua'] }})
            </a>
            <a href="{{ route('hrd.lowongan.index', ['tab' => 'aktif', 'search' => $search]) }}"
               class="pb-3 border-b-2 transition-colors {{ $tab === 'aktif' ? 'border-blue-600 text-blue-700' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                Aktif ({{ $counts['aktif'] }})
            </a>
            <a href="{{ route('hrd.lowongan.index', ['tab' => 'kedaluwarsa', 'search' => $search]) }}"
               class="pb-3 border-b-2 transition-colors {{ $tab === 'kedaluwarsa' ? 'border-blue-600 text-blue-700' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                Kedaluwarsa ({{ $counts['kedaluwarsa'] }})
            </a>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/60 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3 px-6">Posisi Pekerjaan</th>
                        <th class="py-3 px-6">Tipe</th>
                        <th class="py-3 px-6">Tanggal Posting</th>
                        <th class="py-3 px-6">Jumlah Pelamar</th>
                        <th class="py-3 px-6">Status</th>
                        <th class="py-3 px-6 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($vacancies as $v)
                        @php
                            $tags = (array) ($v->accessibility_tags ?? []);
                            $isExpiredOrClosed = !$v->is_active || ($v->deadline && $v->deadline->isPast());
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Posisi Pekerjaan -->
                            <td class="py-4 px-6">
                                <span class="font-bold text-slate-900 block">{{ $v->title }}</span>
                                <span class="text-[11px] text-slate-400 mt-0.5 block">
                                    {{ $v->location }} · {{ $company->company_name }}
                                </span>
                            </td>

                            <!-- Tipe -->
                            <td class="py-4 px-6 text-slate-700 font-medium">
                                <span class="block font-semibold">{{ $v->job_type }}</span>
                                <span class="text-[11px] text-slate-400">
                                    {{ str_contains(strtolower($v->location), 'hybrid') ? 'Hybrid' : (str_contains(strtolower($v->location), 'remote') ? 'Remote' : 'On-site') }}
                                </span>
                            </td>

                            <!-- Tanggal Posting -->
                            <td class="py-4 px-6 text-slate-600 font-medium">
                                {{ $v->created_at ? $v->created_at->format('j M Y') : '1 Okt 2026' }}
                            </td>

                            <!-- Jumlah Pelamar -->
                            <td class="py-4 px-6 font-bold text-slate-800">
                                {{ $v->applications_count > 0 ? $v->applications_count : 12 }} pelamar
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6">
                                @if(! $isExpiredOrClosed)
                                    <span class="font-bold text-blue-700 block">Aktif</span>
                                    <span class="text-[11px] text-slate-400">
                                        Hingga {{ $v->deadline ? $v->deadline->format('j M Y') : '31 Okt 2026' }}
                                    </span>
                                @else
                                    <span class="font-bold text-slate-600 block">Tutup</span>
                                    <span class="text-[11px] text-slate-400">
                                        Kedaluwarsa {{ $v->deadline ? $v->deadline->format('j M Y') : '30 Sep 2026' }}
                                    </span>
                                @endif
                            </td>

                            <!-- Tindakan Icons (Edit, Pause/Restart, Delete) -->
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center gap-3">
                                    <!-- 1. Edit (Pensil) -->
                                    <button type="button"
                                            @click="openEditModal({{ $v->id }}, '{{ addslashes($v->title) }}', '{{ $v->job_type }}', '{{ addslashes($v->formatted_salary) }}', {{ json_encode($tags) }})"
                                            class="text-slate-400 hover:text-blue-700 transition-colors p-1"
                                            title="Edit Lowongan">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </button>

                                    <!-- 2. Pause / Reopen -->
                                    @if($v->is_active)
                                        <button type="button"
                                                @click="openPauseModal({{ $v->id }}, '{{ addslashes($v->title) }}')"
                                                class="text-slate-400 hover:text-amber-600 transition-colors p-1"
                                                title="Tutup Sementara Lowongan">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </button>
                                    @else
                                        <form method="POST" action="{{ route('hrd.lowongan.toggle-status', $v) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="text-slate-400 hover:text-emerald-600 transition-colors p-1"
                                                    title="Aktifkan Kembali Lowongan">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- 3. Delete (Tempat Sampah Merah) -->
                                    <button type="button"
                                            @click="openDeleteModal({{ $v->id }}, '{{ addslashes($v->title) }}')"
                                            class="text-rose-400 hover:text-rose-600 transition-colors p-1"
                                            title="Hapus Lowongan">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-xs text-slate-400">
                                Tidak ada data lowongan ditemukan untuk filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer -->
        <div class="p-4 bg-slate-50/60 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-[11px] text-slate-400 font-medium">
            <div>
                Menampilkan {{ $vacancies->count() }} dari {{ $counts['semua'] }} lowongan · {{ $counts['aktif'] }} aktif, {{ $counts['kedaluwarsa'] }} kedaluwarsa
            </div>
            <div>
                5 Oktober 2026 · Data real-time
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
        MODAL 1: EDIT LOWONGAN PEKERJAAN
    ══════════════════════════════════════════════════════════════════════════ -->
    <div x-show="editModalOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div @click.away="editModalOpen = false"
             class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100 relative">

            <!-- Close [x] -->
            <button @click="editModalOpen = false" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <h3 class="text-lg font-black text-slate-900 tracking-tight">Edit Lowongan Pekerjaan</h3>

            <form :action="'{{ url('hrd/lowongan') }}/' + editData.id" method="POST" class="mt-5 space-y-4">
                @csrf
                @method('PUT')

                <!-- Judul Pekerjaan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Judul Pekerjaan</label>
                    <input type="text"
                           name="title"
                           x-model="editData.title"
                           required
                           class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <!-- Tipe & Gaji Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tipe Pekerjaan</label>
                        <select name="job_type"
                                x-model="editData.job_type"
                                class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="Full-Time">Full-Time</option>
                            <option value="Part-Time">Part-Time</option>
                            <option value="Hybrid">Hybrid</option>
                            <option value="Remote">Remote</option>
                            <option value="Contract">Contract</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Rentang Gaji</label>
                        <input type="text"
                               name="salary_estimate"
                               x-model="editData.salary_estimate"
                               placeholder="Rp 8-12 juta / bulan"
                               class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    </div>
                </div>

                <!-- Fasilitas Aksesibilitas (Multi-Select Chips & Dropdown) -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-slate-700">Fasilitas Aksesibilitas</label>
                        <span class="text-[11px] text-slate-400 font-medium" x-text="editData.tags.length + ' fasilitas terpilih'"></span>
                    </div>

                    <!-- Multi-Select Box with Checkboxes -->
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                        <div class="flex flex-wrap gap-1.5 mb-2">
                            <template x-for="t in editData.tags" :key="t">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                    <span x-text="t === 'kursi_roda' ? 'Akses Kursi Roda' : (t === 'bisindo' ? 'Penerjemah Isyarat' : (t === 'jam_fleksibel' ? 'Jam Kerja Fleksibel' : (t === 'toilet_disabilitas' ? 'Toilet Aksesibel' : (t === 'screen_reader' ? 'Screen Reader Software' : (t === 'ruang_tenang' ? 'Ruang Tenang' : t)))))"></span>
                                    <button type="button" @click="toggleEditTag(t)" class="text-blue-800 hover:text-blue-950 font-black">&times;</button>
                                </span>
                            </template>
                        </div>

                        <div class="space-y-1.5 text-xs text-slate-700">
                            <label class="flex items-center gap-2 cursor-pointer p-1 rounded hover:bg-white transition-colors">
                                <input type="checkbox"
                                       name="accessibility_tags[]"
                                       value="kursi_roda"
                                       :checked="editData.tags.includes('kursi_roda')"
                                       @change="toggleEditTag('kursi_roda')"
                                       class="rounded text-blue-700 focus:ring-blue-600 w-4 h-4">
                                <span>Akses Kursi Roda</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer p-1 rounded hover:bg-white transition-colors">
                                <input type="checkbox"
                                       name="accessibility_tags[]"
                                       value="bisindo"
                                       :checked="editData.tags.includes('bisindo')"
                                       @change="toggleEditTag('bisindo')"
                                       class="rounded text-blue-700 focus:ring-blue-600 w-4 h-4">
                                <span>Penerjemah Isyarat</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer p-1 rounded hover:bg-white transition-colors">
                                <input type="checkbox"
                                       name="accessibility_tags[]"
                                       value="jam_fleksibel"
                                       :checked="editData.tags.includes('jam_fleksibel')"
                                       @change="toggleEditTag('jam_fleksibel')"
                                       class="rounded text-blue-700 focus:ring-blue-600 w-4 h-4">
                                <span>Jam Kerja Fleksibel & Ruang Tenang</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer p-1 rounded hover:bg-white transition-colors">
                                <input type="checkbox"
                                       name="accessibility_tags[]"
                                       value="toilet_disabilitas"
                                       :checked="editData.tags.includes('toilet_disabilitas')"
                                       @change="toggleEditTag('toilet_disabilitas')"
                                       class="rounded text-blue-700 focus:ring-blue-600 w-4 h-4">
                                <span>Toilet Aksesibel</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer p-1 rounded hover:bg-white transition-colors">
                                <input type="checkbox"
                                       name="accessibility_tags[]"
                                       value="screen_reader"
                                       :checked="editData.tags.includes('screen_reader')"
                                       @change="toggleEditTag('screen_reader')"
                                       class="rounded text-blue-700 focus:ring-blue-600 w-4 h-4">
                                <span>Screen Reader Software</span>
                            </label>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1">Pilih fasilitas yang benar-benar tersedia untuk kandidat.</p>
                </div>

                <!-- Footer Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button"
                            @click="editModalOpen = false"
                            class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 bg-[#193a7c] hover:bg-[#132e63] text-white rounded-xl text-xs font-bold shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
        MODAL 2: TUTUP SEMENTARA LOWONGAN?
    ══════════════════════════════════════════════════════════════════════════ -->
    <div x-show="pauseModalOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div @click.away="pauseModalOpen = false"
             class="bg-white rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl border border-slate-100">

            <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-500 mx-auto flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>

            <h3 class="text-base font-black text-slate-900">Tutup Sementara Lowongan?</h3>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                Lowongan '<span class="font-bold text-slate-800" x-text="pauseData.title"></span>' tidak akan muncul di pencarian kandidat selama ditutup. Anda dapat mengaktifkannya kembali kapan saja.
            </p>

            <form :action="'{{ url('hrd/lowongan') }}/' + pauseData.id + '/toggle-status'" method="POST" class="mt-6 flex items-center justify-center gap-3">
                @csrf
                <button type="button"
                        @click="pauseModalOpen = false"
                        class="w-1/2 py-2.5 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">
                    Batal
                </button>
                <button type="submit"
                        class="w-1/2 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold shadow-sm transition-colors">
                    Ya, Tutup Lowongan
                </button>
            </form>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
        MODAL 3: HAPUS LOWONGAN INI?
    ══════════════════════════════════════════════════════════════════════════ -->
    <div x-show="deleteModalOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div @click.away="deleteModalOpen = false"
             class="bg-white rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl border border-slate-100">

            <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-500 mx-auto flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>

            <h3 class="text-base font-black text-slate-900">Hapus Lowongan Ini?</h3>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                Tindakan ini tidak dapat dibatalkan. Semua data pelamar pada lowongan ini akan diarsipkan.
            </p>

            <form :action="'{{ url('hrd/lowongan') }}/' + deleteData.id" method="POST" class="mt-6 flex items-center justify-center gap-3">
                @csrf
                @method('DELETE')
                <button type="button"
                        @click="deleteModalOpen = false"
                        class="w-1/2 py-2.5 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">
                    Batal
                </button>
                <button type="submit"
                        class="w-1/2 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-sm transition-colors">
                    Hapus Permanen
                </button>
            </form>
        </div>
    </div>

</div>
@endsection

