@extends('hrd.layouts.app')

@section('title', 'Pengaturan Perusahaan')

@section('content')
<div class="py-6 space-y-6" x-data="{
    activeTab: '{{ $activeTab }}',
    showCurPass: false,
    showNewPass: false,
    showConfPass: false,
    nibFileModal: false,
    npwpFileModal: false
}">

    <!-- Top Heading -->
    <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Pengaturan Perusahaan</h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Kelola profil perusahaan, informasi kantor, dan dokumen legalitas.</p>
    </div>

    <!-- Tabs Navigation Bar -->
    <div class="flex items-center gap-6 sm:gap-8 border-b border-slate-200/80 overflow-x-auto text-xs font-bold">
        <button type="button"
                @click="activeTab = 'profil'"
                :class="activeTab === 'profil' ? 'border-blue-600 text-blue-700' : 'border-transparent text-slate-500 hover:text-slate-800'"
                class="pb-3 border-b-2 transition-colors whitespace-nowrap">
            Profil Perusahaan
        </button>
        <button type="button"
                @click="activeTab = 'fasilitas'"
                :class="activeTab === 'fasilitas' ? 'border-blue-600 text-blue-700' : 'border-transparent text-slate-500 hover:text-slate-800'"
                class="pb-3 border-b-2 transition-colors whitespace-nowrap">
            Fasilitas Aksesibilitas Kantor
        </button>
        <button type="button"
                @click="activeTab = 'dokumen'"
                :class="activeTab === 'dokumen' ? 'border-blue-600 text-blue-700' : 'border-transparent text-slate-500 hover:text-slate-800'"
                class="pb-3 border-b-2 transition-colors whitespace-nowrap">
            Dokumen Legalitas & Verifikasi
        </button>
        <button type="button"
                @click="activeTab = 'keamanan'"
                :class="activeTab === 'keamanan' ? 'border-blue-600 text-blue-700' : 'border-transparent text-slate-500 hover:text-slate-800'"
                class="pb-3 border-b-2 transition-colors whitespace-nowrap">
            Keamanan Akun
        </button>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
        TAB 1: PROFIL PERUSAHAAN (Screenshot 5)
    ══════════════════════════════════════════════════════════════════════════ -->
    <div x-show="activeTab === 'profil'" class="space-y-6">
        <form method="POST" action="{{ route('hrd.pengaturan.updateProfile') }}" class="space-y-6">
            @csrf

            <!-- Card Informasi Perusahaan -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Informasi Perusahaan</h3>
                    </div>
                    <span class="text-[11px] text-slate-400">Informasi yang ditampilkan kepada kandidat</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-1">
                    <!-- Nama Perusahaan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Perusahaan</label>
                        <input type="text"
                               name="company_name"
                               value="{{ old('company_name', $company->company_name) }}"
                               required
                               class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    </div>

                    <!-- Alamat Kantor Pusat -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Kantor Pusat</label>
                        <input type="text"
                               name="location"
                               value="{{ old('location', $company->location ?? 'Jl. Kantor Inklusif No. 10, Jakarta Selatan') }}"
                               required
                               class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    </div>

                    <!-- Nomor Kontak HR -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Kontak HR</label>
                        <input type="text"
                               name="phone"
                               value="{{ old('phone', $company->phone ?? '021 0000 2026 (contoh)') }}"
                               class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    </div>

                    <!-- Website Resmi -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Website Resmi</label>
                        <input type="text"
                               name="website"
                               value="{{ old('website', $company->website ?? 'https://teknologiinovatif.example') }}"
                               class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    </div>
                </div>
            </div>

            <!-- Two Bottom Cards: Fasilitas & Legalitas Preview -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <!-- Fasilitas Terdaftar Preview -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between">
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Fasilitas Aksesibilitas Terdaftar</h4>
                        <p class="text-[11px] text-slate-400 mt-1">Mengacu pada kategori Master Data KerjaSetara. Kelola melalui tab fasilitas kantor.</p>

                        <div class="mt-4 space-y-2">
                            @php
                                $features = (array) ($company->accessibility_features ?? ['kursi_roda', 'toilet_disabilitas', 'bisindo']);
                            @endphp
                            <div class="flex items-center gap-2 text-xs font-semibold text-slate-800">
                                <svg class="w-4 h-4 text-blue-700" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-9 14l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                <span>Akses Kursi Roda</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs font-semibold text-slate-800">
                                <svg class="w-4 h-4 text-blue-700" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-9 14l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                <span>Toilet Aksesibel</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs font-semibold text-slate-800">
                                <svg class="w-4 h-4 text-blue-700" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-9 14l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                <span>Penerjemah Isyarat</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dokumen Legalitas Preview -->
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <h4 class="text-xs font-bold text-slate-900">Dokumen Legalitas</h4>
                        </div>

                        <div class="mt-3 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>NPWP & Surat Izin Usaha: Terverifikasi (Admin KerjaSetara)</span>
                        </div>

                        <p class="text-[11px] text-slate-400 mt-3">2 jenis dokumen · Akun contoh - Tanpa nomor atau berkas legal asli.</p>
                    </div>

                    <div class="pt-4 text-right">
                        <button type="button"
                                @click="activeTab = 'dokumen'"
                                class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                            Kelola / Unggah
                        </button>
                    </div>
                </div>
            </div>

            <!-- Footer Save Profile -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                <div class="flex items-center gap-2 text-xs text-slate-400">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Tinjau kembali informasi perusahaan sebelum menyimpan.</span>
                </div>

                <button type="submit"
                        class="px-6 py-2.5 bg-[#193a7c] hover:bg-[#132e63] text-white font-bold text-xs rounded-xl shadow-sm transition-all hover:shadow-md">
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
        TAB 2: FASILITAS AKSESIBILITAS KANTOR (Screenshot 5 Tab 2)
    ══════════════════════════════════════════════════════════════════════════ -->
    <div x-show="activeTab === 'fasilitas'" class="space-y-6">
        <form method="POST" action="{{ route('hrd.pengaturan.updateFacilities') }}" class="space-y-6">
            @csrf

            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Kelola Fasilitas Aksesibilitas Fisik & Digital Perusahaan</h3>
                    <p class="text-xs text-slate-500 mt-1">Fasilitas ini yang akan disinkronkan secara otomatis oleh sistem MatchingEngine pada setiap lowongan yang Anda buat.</p>
                </div>

                @php
                    $currentFeatures = (array) ($company->accessibility_features ?? []);
                @endphp

                <!-- 5 Checklist Items -->
                <div class="space-y-3 pt-2">
                    @foreach($availableFacilities as $key => $f)
                        @php
                            $isSelected = in_array($key, $currentFeatures);
                        @endphp
                        <label class="flex items-center justify-between p-4 rounded-xl border {{ $isSelected ? 'border-slate-200 bg-white' : 'border-slate-100 bg-slate-50/50' }} hover:border-slate-300 transition-colors cursor-pointer">
                            <div class="flex items-center gap-3">
                                <input type="checkbox"
                                       name="facilities[]"
                                       value="{{ $key }}"
                                       {{ $isSelected ? 'checked' : '' }}
                                       class="rounded text-blue-700 focus:ring-blue-600 w-4 h-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm9 7h-6v13h-2v-6h-2v6H9V9H3V7h18v2z"/>
                                    </svg>
                                    <span class="text-xs font-semibold text-slate-800">{{ $f['name'] }}</span>
                                </div>
                            </div>
                            <span class="text-[11px] font-semibold {{ $isSelected ? 'text-blue-700' : 'text-slate-400' }}">
                                {{ $isSelected ? 'Terpilih' : 'Belum dipilih' }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Footer Save Facilities -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                <div class="text-xs text-slate-400 font-medium">
                    {{ count($currentFeatures) }} dari 5 fasilitas dipilih · Mengacu pada Master Data KerjaSetara
                </div>

                <button type="submit"
                        class="px-6 py-2.5 bg-[#193a7c] hover:bg-[#132e63] text-white font-bold text-xs rounded-xl shadow-sm transition-all hover:shadow-md">
                    Simpan Daftar Fasilitas
                </button>
            </div>
        </form>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
        TAB 3: DOKUMEN LEGALITAS & VERIFIKASI (Screenshot 5 Tab 3)
    ══════════════════════════════════════════════════════════════════════════ -->
    <div x-show="activeTab === 'dokumen'" class="space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-6">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Verifikasi Legalitas Badan Usaha</h3>
                <p class="text-xs text-slate-500 mt-1">Dokumen ini wajib diunggah agar perusahaan Anda mendapatkan lencana terverifikasi dari Admin KerjaSetara.</p>
            </div>

            <!-- Card 1: NIB -->
            <div class="p-5 rounded-2xl border border-slate-200 bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <h4 class="text-xs font-bold text-slate-900">Surat Izin Usaha / NIB</h4>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            ✓ Terverifikasi oleh Admin
                        </span>
                    </div>
                    <div class="flex items-center gap-2 mt-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">{{ $company->nib_file ?? 'NIB_PT_Teknologi_Inovatif.pdf' }}</span>
                            <span class="text-[10px] text-slate-400">PDF · Dokumen terunggah</span>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('hrd.pengaturan.updateDocument') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="document_type" value="nib">
                    <label class="cursor-pointer inline-flex items-center justify-center px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs rounded-xl shadow-sm transition-colors">
                        <span>Ganti Dokumen</span>
                        <input type="file" name="file" onchange="this.form.submit()" class="hidden">
                    </label>
                </form>
            </div>

            <!-- Card 2: NPWP -->
            <div class="p-5 rounded-2xl border border-slate-200 bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <h4 class="text-xs font-bold text-slate-900">NPWP Perusahaan</h4>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            ✓ Terverifikasi
                        </span>
                    </div>
                    <div class="flex items-center gap-2 mt-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-800 block">{{ $company->npwp_file ?? 'NPWP_PT_Teknologi_Inovatif.pdf' }}</span>
                            <span class="text-[10px] text-slate-400">PDF · Nama berkas ilustratif</span>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('hrd.pengaturan.updateDocument') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="document_type" value="npwp">
                    <label class="cursor-pointer inline-flex items-center justify-center px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs rounded-xl shadow-sm transition-colors">
                        <span>Ganti Dokumen</span>
                        <input type="file" name="file" onchange="this.form.submit()" class="hidden">
                    </label>
                </form>
            </div>

            <!-- Notice Box -->
            <div class="p-3.5 rounded-xl bg-blue-50/70 border border-blue-100 flex items-center gap-2 text-xs text-blue-900">
                <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Proses verifikasi ulang membutuhkan waktu maksimal 1x24 jam kerja setelah dokumen diperbarui.</span>
            </div>

            <div class="text-[11px] text-slate-400">
                Akun contoh - Tanpa nomor atau berkas legal asli.
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════════
        TAB 4: KEAMANAN AKUN (Screenshot 5 Tab 4)
    ══════════════════════════════════════════════════════════════════════════ -->
    <div x-show="activeTab === 'keamanan'" class="space-y-6">
        <div>
            <h3 class="text-sm font-bold text-slate-900">Keamanan & Autentikasi Akun Perusahaan</h3>
            <p class="text-xs text-slate-500 mt-0.5">Kelola sandi dan lapis pengamanan untuk akun HR Anda.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Card 1: Perbarui Kata Sandi -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <h4 class="text-xs font-bold text-slate-900">Perbarui Kata Sandi</h4>

                <form method="POST" action="{{ route('hrd.pengaturan.updatePassword') }}" class="space-y-4">
                    @csrf

                    <!-- Kata Sandi Saat Ini -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi Saat Ini</label>
                        <div class="relative">
                            <input :type="showCurPass ? 'text' : 'password'"
                                   name="current_password"
                                   required
                                   class="w-full pl-3.5 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <button type="button" @click="showCurPass = !showCurPass" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Kata Sandi Baru -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi Baru</label>
                        <div class="relative">
                            <input :type="showNewPass ? 'text' : 'password'"
                                   name="password"
                                   required
                                   class="w-full pl-3.5 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <button type="button" @click="showNewPass = !showNewPass" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Konfirmasi Kata Sandi Baru -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                        <div class="relative">
                            <input :type="showConfPass ? 'text' : 'password'"
                                   name="password_confirmation"
                                   required
                                   class="w-full pl-3.5 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <button type="button" @click="showConfPass = !showConfPass" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <p class="text-[10px] text-slate-400">Gunakan sandi unik dengan kombinasi huruf, angka, dan simbol.</p>

                    <div>
                        <button type="submit"
                                class="px-5 py-2.5 bg-[#193a7c] hover:bg-[#132e63] text-white font-bold text-xs rounded-xl shadow-sm transition-all hover:shadow-md">
                            Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card 2: Sesi Perangkat Aktif -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-4 flex flex-col justify-between">
                <div>
                    <h4 class="text-xs font-bold text-slate-900">Sesi Perangkat Aktif</h4>
                    <p class="text-[11px] text-slate-400 mt-0.5">Tinjau perangkat yang masuk ke akun HR Anda.</p>

                    <div class="mt-4 space-y-3">
                        <!-- Perangkat 1: Sesi Ini -->
                        <div class="p-4 rounded-xl border border-slate-200 bg-white flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-700">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-slate-900 block">Windows PC – Chrome</span>
                                    <span class="text-[10px] text-slate-400">Aktif Saat Ini – Surabaya, Indonesia</span>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                ● Sesi ini
                            </span>
                        </div>

                        <!-- Perangkat 2 -->
                        <div class="p-4 rounded-xl border border-slate-200 bg-white flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-700">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-slate-900 block">MacBook Pro – Safari</span>
                                    <span class="text-[10px] text-slate-400">2 hari lalu</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <button type="button"
                            class="inline-flex items-center gap-2 text-xs font-bold text-rose-600 hover:text-rose-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Keluar dari Semua Perangkat Lain</span>
                    </button>
                    <p class="text-[10px] text-slate-400 mt-2 leading-relaxed">
                        Sesi saat ini tetap aktif. Anda tidak akan keluar dari perangkat ini. Daftar perangkat ini adalah contoh ilustratif.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

