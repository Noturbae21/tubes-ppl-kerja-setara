@extends('hrd.layouts.app')

@section('title', 'Posting Lowongan Kerja Baru')

@section('content')
<div class="py-6 max-w-5xl mx-auto space-y-6" x-data="{
    // Character counters
    descText: '',
    qualText: '',

    // Facilities multi-select
    dropdownOpen: false,
    selectedFacilities: {{ isset($activeFacilities) && $activeFacilities->isNotEmpty() ? Js::from($activeFacilities->take(2)->pluck('id')->map(fn($id) => (string) $id)) : "['kursi_roda', 'toilet_disabilitas', 'bisindo']" }},
    facilitiesList: {{ isset($activeFacilities) && $activeFacilities->isNotEmpty() ? Js::from($activeFacilities->map(fn($f) => [
        'id' => (string) $f->id,
        'label' => $f->name,
        'desc' => $f->description,
    ])) : json_encode([
        ['id' => 'kursi_roda', 'label' => 'Akses Kursi Roda', 'desc' => 'Ramp, lift, dan pintu lebar'],
        ['id' => 'toilet_disabilitas', 'label' => 'Toilet Aksesibel', 'desc' => 'Pegangan rambat & ruang luas'],
        ['id' => 'ruang_tenang', 'label' => 'Ruang Tenang / Neurodiverse', 'desc' => 'Quiet room fokus'],
        ['id' => 'bisindo', 'label' => 'Layanan Penerjemah Isyarat', 'desc' => 'Interpreter BISINDO saat meeting'],
        ['id' => 'screen_reader', 'label' => 'Dukungan Pembaca Layar', 'desc' => 'Workstation screen reader friendly']
    ]) }},
    toggleFacility(id) {
        if (this.selectedFacilities.includes(id)) {
            this.selectedFacilities = this.selectedFacilities.filter(f => f !== id);
        } else {
            this.selectedFacilities.push(id);
        }
    },
    getFacilityLabels() {
        return this.facilitiesList
            .filter(f => this.selectedFacilities.includes(f.id))
            .map(f => f.label)
            .join(', ');
    },

    // Publish toggle
    publishNow: true,

    // Insert formatting helper
    insertFormat(ref, openTag, closeTag) {
        // Simple markdown toolbar simulation
        const textarea = this.$refs[ref];
        if (!textarea) return;
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        const selected = text.substring(start, end);
        const replacement = openTag + selected + closeTag;
        textarea.value = text.substring(0, start) + replacement + text.substring(end);
        if (ref === 'desc') this.descText = textarea.value;
        if (ref === 'qual') this.qualText = textarea.value;
    }
}">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
        <a href="{{ route('hrd.lowongan.index') }}" class="hover:text-blue-700 transition-colors">Kelola Lowongan</a>
        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-blue-800">Buat Lowongan Baru</span>
    </div>

    <!-- Page Title -->
    <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Posting Lowongan Kerja Baru.</h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Lengkapi detail pekerjaan dan fasilitas aksesibilitas yang tersedia.</p>
    </div>

    <form method="POST" action="{{ route('hrd.lowongan.store') }}" class="space-y-6">
        @csrf

        <!-- ══════════════════════════════════════════════════════════════════════
            CARD 1: INFORMASI DASAR PEKERJAAN
        ══════════════════════════════════════════════════════════════════════ -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Informasi Dasar Pekerjaan</h3>
                    <p class="text-[11px] text-slate-400">Bantu kandidat mengenali posisi dan pengaturan kerja Anda.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                <!-- Judul Pekerjaan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Judul Pekerjaan</label>
                    <input type="text"
                           name="title"
                           required
                           placeholder="Contoh: UI/UX Designer"
                           class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <!-- Tipe Pekerjaan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tipe Pekerjaan</label>
                    <div class="relative">
                        <select name="job_type"
                                class="w-full appearance-none px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="Full-Time">Full-Time</option>
                            <option value="Part-Time">Part-Time</option>
                            <option value="Hybrid">Hybrid</option>
                            <option value="Remote">Remote</option>
                            <option value="Contract">Contract</option>
                        </select>
                        <svg class="w-4 h-4 text-slate-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                <!-- Lokasi Kerja -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Lokasi Kerja</label>
                    <div class="relative">
                        <input type="text"
                               name="location"
                               required
                               placeholder="Contoh: Jakarta, Hybrid"
                               class="w-full pl-3.5 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <svg class="w-4 h-4 text-slate-400 absolute right-3.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 block">Pilihan tipe pekerjaan: Full-Time atau Part-Time.</span>
                </div>

                <!-- Estimasi Gaji (Optional) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Estimasi Gaji (Optional)</label>
                    <input type="text"
                           name="salary_estimate"
                           placeholder="Contoh: Rp 8–12 juta / bulan"
                           class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════════════════════
            CARD 2: DESKRIPSI & KUALIFIKASI
        ══════════════════════════════════════════════════════════════════════ -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Deskripsi & Kualifikasi</h3>
                    <p class="text-[11px] text-slate-400">Gunakan bahasa yang jelas, inklusif, dan berfokus pada kompetensi.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                <!-- Deskripsi Pekerjaan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi Pekerjaan</label>
                    <div class="border border-slate-200 rounded-2xl overflow-hidden focus-within:ring-2 focus-within:ring-blue-600 bg-white">
                        <!-- Toolbar -->
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 border-b border-slate-200 text-slate-600 text-xs">
                            <button type="button" @click="insertFormat('desc', '**', '**')" class="px-1.5 py-0.5 hover:bg-slate-200 rounded font-bold">B</button>
                            <button type="button" @click="insertFormat('desc', '*', '*')" class="px-1.5 py-0.5 hover:bg-slate-200 rounded italic font-serif">I</button>
                            <button type="button" @click="insertFormat('desc', '- ', '')" class="px-1.5 py-0.5 hover:bg-slate-200 rounded flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                <span>Lists</span>
                            </button>
                        </div>
                        <textarea name="description"
                                  x-ref="desc"
                                  x-model="descText"
                                  rows="6"
                                  required
                                  placeholder="Jelaskan tanggung jawab, aktivitas sehari-hari, dan cara tim Anda bekerja..."
                                  class="w-full p-3.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none resize-none"></textarea>
                        <div class="px-3.5 py-1.5 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-400">
                            <span x-text="descText.length + ' karakter'"></span>
                            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Kualifikasi Pelamar -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kualifikasi Pelamar</label>
                    <div class="border border-slate-200 rounded-2xl overflow-hidden focus-within:ring-2 focus-within:ring-blue-600 bg-white">
                        <!-- Toolbar -->
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-slate-50 border-b border-slate-200 text-slate-600 text-xs">
                            <button type="button" @click="insertFormat('qual', '**', '**')" class="px-1.5 py-0.5 hover:bg-slate-200 rounded font-bold">B</button>
                            <button type="button" @click="insertFormat('qual', '*', '*')" class="px-1.5 py-0.5 hover:bg-slate-200 rounded italic font-serif">I</button>
                            <button type="button" @click="insertFormat('qual', '- ', '')" class="px-1.5 py-0.5 hover:bg-slate-200 rounded flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                <span>Lists</span>
                            </button>
                        </div>
                        <textarea name="qualifications"
                                  x-ref="qual"
                                  x-model="qualText"
                                  rows="6"
                                  placeholder="Tuliskan keterampilan, pengalaman, dan kualifikasi yang dibutuhkan untuk posisi ini..."
                                  class="w-full p-3.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none resize-none"></textarea>
                        <div class="px-3.5 py-1.5 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-400">
                            <span x-text="qualText.length + ' karakter'"></span>
                            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ══════════════════════════════════════════════════════════════════════
            CARD 3: FASILITAS AKSESIBILITAS (MATCHINGENGINE)
        ══════════════════════════════════════════════════════════════════════ -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm9 7h-6v13h-2v-6h-2v6H9V9H3V7h18v2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Fasilitas Aksesibilitas</h3>
                    <p class="text-[11px] text-slate-400">Hubungkan kandidat dengan lingkungan kerja yang mendukung kebutuhannya.</p>
                </div>
            </div>

            <div class="pt-2 space-y-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700">Fasilitas Aksesibilitas yang Tersedia di Lokasi Kerja:</label>
                    <p class="text-[11px] text-slate-400 mt-0.5">Pilih minimal satu fasilitas untuk mengaktifkan MatchingEngine.</p>
                </div>

                <!-- Custom Multi-select Dropdown matching Mockup Spec (Screenshot 6) -->
                <div class="relative">
                    <button type="button"
                            @click="dropdownOpen = !dropdownOpen"
                            class="w-full flex items-center justify-between px-4 py-3 bg-white border border-slate-200 rounded-2xl text-xs font-bold text-slate-800 hover:border-slate-300 shadow-sm">
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm9 7h-6v13h-2v-6h-2v6H9V9H3V7h18v2z"/>
                            </svg>
                            <span x-text="selectedFacilities.length + ' Fasilitas Terpilih'"></span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="dropdownOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown Options List -->
                    <div x-show="dropdownOpen"
                         x-cloak
                         @click.away="dropdownOpen = false"
                         class="absolute z-20 left-0 right-0 mt-2 bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden p-2 space-y-1">

                        <template x-for="item in facilitiesList" :key="item.id">
                            <div @click="toggleFacility(item.id)"
                                 :class="selectedFacilities.includes(item.id) ? 'bg-amber-50/80 border-amber-200' : 'hover:bg-slate-50 border-transparent'"
                                 class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl border cursor-pointer transition-colors">
                                <input type="checkbox"
                                       name="accessibility_tags[]"
                                       :value="item.id"
                                       :checked="selectedFacilities.includes(item.id)"
                                       @click.stop
                                       @change="toggleFacility(item.id)"
                                       class="rounded text-blue-700 focus:ring-blue-600 w-4 h-4">
                                <div class="flex-1">
                                    <span class="text-xs font-bold" :class="selectedFacilities.includes(item.id) ? 'text-slate-900' : 'text-slate-700'" x-text="item.label"></span>
                                    <span class="block text-[10px] text-slate-400" x-text="item.desc"></span>
                                </div>
                                <span x-show="selectedFacilities.includes(item.id)" class="text-[10px] font-bold text-amber-700">Terpilih</span>
                            </div>
                        </template>

                        <div class="pt-2 px-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                            <span x-text="selectedFacilities.length + ' fasilitas terpilih'"></span>
                            <button type="button" @click="dropdownOpen = false" class="text-blue-700 font-bold hover:underline">Selesai</button>
                        </div>
                    </div>
                </div>

                <!-- Helper Text Terpilih -->
                <p class="text-[11px] text-slate-500 font-medium">
                    Terpilih: <span class="font-semibold text-slate-700" x-text="getFacilityLabels() || 'Belum ada fasilitas dipilih'"></span>
                </p>

                <!-- MatchingEngine Live Indicator -->
                <div x-show="selectedFacilities.length > 0" class="flex items-center gap-2 p-3 rounded-xl bg-blue-50/60 border border-blue-100 text-[11px] text-blue-900 font-medium">
                    <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>
                        MatchingEngine aktif · Kandidat dapat mencocokkan kebutuhan dengan <span class="font-bold" x-text="selectedFacilities.length"></span> fasilitas yang tersedia.
                    </span>
                </div>
            </div>
        </div>

  

        <!-- ══════════════════════════════════════════════════════════════════════
            BOTTOM BAR
        ══════════════════════════════════════════════════════════════════════ -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2 pb-6">
            <div class="flex items-center gap-2 text-xs text-slate-400">
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Draf tidak akan ditampilkan kepada kandidat.</span>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        name="action"
                        value="draft"
                        class="px-5 py-2.5 border border-slate-300 rounded-xl bg-white font-semibold text-xs text-slate-700 hover:bg-slate-50 transition-colors shadow-sm">
                    Simpan Draf
                </button>
                <button type="submit"
                        name="action"
                        value="publish"
                        class="px-6 py-2.5 bg-[#193a7c] hover:bg-[#132e63] rounded-xl font-bold text-xs text-white shadow-sm transition-all hover:shadow-md">
                    Publikasikan Lowongan
                </button>
            </div>
        </div>
    </form>

</div>
@endsection

