@extends('layouts.kandidat')

@section('title', 'Profil Saya — KerjaSetara')
@section('meta_description', 'Kelola informasi pribadi, preferensi aksesibilitas, CV, dan keamanan akun Anda di KerjaSetara.')

@section('content')
@php
    $defaultPreferences = ['kursi_roda', 'toilet_disabilitas', 'bisindo', 'screen_reader', 'quiet_room', 'jam_fleksibel'];
    $userPreferences = (is_array($user->accessibility_preferences) && count($user->accessibility_preferences) > 0)
        ? $userPreferences = $user->accessibility_preferences
        : $defaultPreferences;
@endphp

<div class="bg-slate-50 min-h-screen py-8 sm:py-10" 
     x-data="{ 
         activeTab: '{{ request('tab', 'personal') }}',
         preferences: {{ json_encode($userPreferences) }},
         togglePref(val) {
             if (this.preferences.includes(val)) {
                 this.preferences = this.preferences.filter(item => item !== val);
             } else {
                 this.preferences.push(val);
             }
         }
     }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Flash Success Notification -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3.5 rounded-2xl text-sm font-semibold flex items-center gap-3 shadow-xs">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-300 text-rose-800 px-4 py-3.5 rounded-2xl text-sm font-semibold shadow-xs">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- ─── SIDEBAR KIRI: PROFIL CARD & MENU NAVIGASI TAB ───────────── -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Card Profil Ringkas -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs text-center">
                    <div class="relative w-24 h-24 mx-auto mb-4">
                        <img src="{{ $user->avatar ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=240&auto=format&fit=crop&q=80' }}" 
                             alt="Foto Profil {{ $user->name }}" 
                             class="w-24 h-24 rounded-full object-cover shadow-sm mx-auto ring-4 ring-blue-50 border border-slate-100">
                    </div>
                    
                    <h2 class="text-lg font-bold text-slate-900 leading-snug">
                        {{ $user->full_name ?: ($user->name ?: 'Nadia Saraswati') }}
                    </h2>
                    <p class="text-xs text-slate-500 font-medium mt-1 leading-snug">
                        {{ $user->headline ?: 'UI/UX Designer & Accessibility Advocate' }}
                    </p>
                    <div class="flex items-center justify-center gap-1.5 text-xs text-slate-500 mt-2.5">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ $user->city ?: 'Jakarta, Indonesia' }}</span>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-slate-100 my-5"></div>

                    <!-- Menu Navigasi Tab Vertikal -->
                    <nav class="space-y-1.5" aria-label="Menu Tab Profil">
                        
                        <!-- 1. Informasi Pribadi -->
                        <button type="button"
                                @click="activeTab = 'personal'"
                                :class="activeTab === 'personal' ? 'bg-blue-50/80 text-blue-900 font-bold border-l-4 border-blue-900' : 'text-slate-600 hover:bg-slate-50 font-medium'"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs sm:text-sm transition-all text-left focus:outline-none">
                            <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Informasi Pribadi</span>
                        </button>

                        <!-- 2. Preferensi Aksesibilitas -->
                        <button type="button"
                                @click="activeTab = 'accessibility'"
                                :class="activeTab === 'accessibility' ? 'bg-blue-50/80 text-blue-900 font-bold border-l-4 border-blue-900' : 'text-slate-600 hover:bg-slate-50 font-medium'"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs sm:text-sm transition-all text-left focus:outline-none">
                            <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                <circle cx="12" cy="4" r="2" stroke-width="2"/>
                            </svg>
                            <span>Preferensi Aksesibilitas</span>
                        </button>

                        <!-- 3. CV & Portofolio -->
                        <button type="button"
                                @click="activeTab = 'cv'"
                                :class="activeTab === 'cv' ? 'bg-blue-50/80 text-blue-900 font-bold border-l-4 border-blue-900' : 'text-slate-600 hover:bg-slate-50 font-medium'"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs sm:text-sm transition-all text-left focus:outline-none">
                            <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>CV & Portofolio</span>
                        </button>

                        <!-- 4. Riwayat Lamaran (Link ke halaman Riwayat Lamaran) -->
                        <a href="{{ route('kandidat.lamaran') }}"
                           class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs sm:text-sm text-slate-600 hover:bg-slate-50 font-medium transition-all text-left">
                            <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>Riwayat Lamaran</span>
                        </a>

                        <!-- 5. Pengaturan Akun & Keamanan -->
                        <button type="button"
                                @click="activeTab = 'security'"
                                :class="activeTab === 'security' ? 'bg-blue-50/80 text-blue-900 font-bold border-l-4 border-blue-900' : 'text-slate-600 hover:bg-slate-50 font-medium'"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-xs sm:text-sm transition-all text-left focus:outline-none">
                            <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span>Pengaturan Akun & Keamanan</span>
                        </button>

                    </nav>

                </div>
            </div>

            <!-- ─── KONTEN UTAMA KANAN (TABS) ─────────────────────────────────── -->
            <div class="lg:col-span-8">

                <!-- ════════════════════════════════════════════════════════════════
                     TAB 1: INFORMASI PRIBADI
                ═════════════════════════════════════════════════════════════════ -->
                <div x-show="activeTab === 'personal'" 
                     class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                    
                    <div>
                        <h1 class="text-xl font-extrabold text-[#193a7c] tracking-tight">Informasi Pribadi</h1>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">Perbarui data diri dan informasi kontak Anda.</p>
                    </div>

                    <form method="POST" action="{{ route('kandidat.profil.update') }}" class="space-y-5">
                        @csrf
                        <input type="hidden" name="_section" value="personal">

                        <!-- Foto Profil Row -->
                        <div class="flex items-center gap-4 p-4 rounded-xl border border-slate-200/80 bg-white">
                            <img src="{{ $user->avatar ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=80' }}" 
                                 alt="Avatar {{ $user->name }}" 
                                 class="w-14 h-14 rounded-full object-cover shadow-xs border border-slate-100 flex-shrink-0">
                            <div class="flex-1">
                                <h3 class="text-sm font-bold text-slate-900 leading-snug">Foto Profil</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Gunakan foto yang jelas agar recruiter mudah mengenali Anda.</p>
                            </div>
                            <button type="button" 
                                    class="px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-xl transition-colors shadow-2xs">
                                Ganti Foto
                            </button>
                        </div>

                        <!-- Grid Input 2 Kolom -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="input_full_name" class="block text-xs font-bold text-slate-800 mb-1.5">Nama Lengkap</label>
                                <input type="text" 
                                       id="input_full_name"
                                       name="full_name" 
                                       value="{{ old('full_name', $user->full_name ?: $user->name) }}" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-900 bg-white focus:border-blue-800 focus:ring-1 focus:ring-blue-800 focus:outline-none" 
                                       placeholder="Nama Lengkap" 
                                       required>
                            </div>
                            <div>
                                <label for="input_headline" class="block text-xs font-bold text-slate-800 mb-1.5">Gelar Profesional / Headline</label>
                                <input type="text" 
                                       id="input_headline"
                                       name="headline" 
                                       value="{{ old('headline', $user->headline ?: 'UI/UX Designer & Accessibility Advocate') }}" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-900 bg-white focus:border-blue-800 focus:ring-1 focus:ring-blue-800 focus:outline-none" 
                                       placeholder="Contoh: UI/UX Designer & Accessibility Advocate">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="input_email" class="block text-xs font-bold text-slate-800 mb-1.5">Email Utama</label>
                                <input type="email" 
                                       id="input_email"
                                       value="{{ $user->email }}" 
                                       disabled 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-500 bg-slate-50/70 cursor-not-allowed">
                            </div>
                            <div>
                                <label for="input_phone" class="block text-xs font-bold text-slate-800 mb-1.5">Nomor WhatsApp / Telepon</label>
                                <input type="text" 
                                       id="input_phone"
                                       name="phone" 
                                       value="{{ old('phone', $user->phone ?: '+62 812 3456 7890') }}" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-900 bg-white focus:border-blue-800 focus:ring-1 focus:ring-blue-800 focus:outline-none" 
                                       placeholder="+62 812 3456 7890">
                            </div>
                        </div>

                        <div>
                            <label for="input_city" class="block text-xs font-bold text-slate-800 mb-1.5">Kota Domisili</label>
                            <input type="text" 
                                   id="input_city"
                                   name="city" 
                                   value="{{ old('city', $user->city ?: 'Jakarta, Indonesia') }}" 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-900 bg-white focus:border-blue-800 focus:ring-1 focus:ring-blue-800 focus:outline-none" 
                                   placeholder="Kota domisili Anda">
                        </div>

                        <div>
                            <label for="input_bio" class="block text-xs font-bold text-slate-800 mb-1.5">Bio Singkat</label>
                            <textarea id="input_bio"
                                      name="bio" 
                                      rows="3" 
                                      placeholder="Ceritakan sedikit tentang latar belakang dan keahlian Anda..." 
                                      class="w-full p-3 rounded-xl border border-slate-200 text-sm text-slate-800 bg-white focus:border-blue-800 focus:ring-1 focus:ring-blue-800 focus:outline-none">{{ old('bio', $user->bio) }}</textarea>
                        </div>

                        <!-- Hidden fallback for name field if needed by model -->
                        <input type="hidden" name="name" value="{{ $user->name ?: 'Nadia' }}">

                        <div class="flex justify-end pt-2">
                            <button type="submit" 
                                    class="px-6 py-2.5 bg-[#193a7c] hover:bg-blue-900 text-white font-bold text-sm rounded-xl shadow-xs transition-colors focus:ring-2 focus:ring-blue-600 focus:outline-none">
                                Simpan Perubahan Profil
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ════════════════════════════════════════════════════════════════
                     TAB 2: PREFERENSI & KEBUTUHAN AKSESIBILITAS
                ═════════════════════════════════════════════════════════════════ -->
                <div x-show="activeTab === 'accessibility'" 
                     x-cloak 
                     class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                    
                    <div>
                        <h2 class="text-xl font-extrabold text-[#193a7c] tracking-tight">Preferensi &amp; Kebutuhan Aksesibilitas</h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">Data ini digunakan oleh MatchingEngine untuk menghitung persentase kecocokan Anda dengan fasilitas di lowongan kerja.</p>
                    </div>

                    <form method="POST" action="{{ route('kandidat.profil.update') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="_section" value="preferensi">

                        <!-- Kategori 1: Aksesibilitas Fisik & Lingkungan Kerja -->
                        <div class="space-y-3">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Aksesibilitas Fisik & Lingkungan Kerja</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Pilih fasilitas fisik yang Anda perlukan di tempat kerja:</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <!-- Kursi Roda -->
                                <label class="flex items-center justify-between p-3.5 rounded-xl border transition-all cursor-pointer"
                                       :class="preferences.includes('kursi_roda') ? 'bg-[#fffbeb] border-[#fde68a] text-slate-900 shadow-2xs' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50'">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-base" aria-hidden="true">♿</span>
                                        <span class="text-xs sm:text-sm font-medium">Akses Kursi Roda (Ramp / Lift)</span>
                                    </div>
                                    <input type="checkbox" 
                                           name="accessibility_preferences[]" 
                                           value="kursi_roda"
                                           :checked="preferences.includes('kursi_roda')"
                                           @change="togglePref('kursi_roda')"
                                           class="w-4 h-4 rounded text-amber-900 border-amber-300 focus:ring-amber-500 cursor-pointer accent-[#854d0e]">
                                </label>

                                <!-- Toilet Ramah Disabilitas -->
                                <label class="flex items-center justify-between p-3.5 rounded-xl border transition-all cursor-pointer"
                                       :class="preferences.includes('toilet_disabilitas') ? 'bg-[#fffbeb] border-[#fde68a] text-slate-900 shadow-2xs' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50'">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-base" aria-hidden="true">🚻</span>
                                        <span class="text-xs sm:text-sm font-medium">Toilet Ramah Disabilitas</span>
                                    </div>
                                    <input type="checkbox" 
                                           name="accessibility_preferences[]" 
                                           value="toilet_disabilitas"
                                           :checked="preferences.includes('toilet_disabilitas')"
                                           @change="togglePref('toilet_disabilitas')"
                                           class="w-4 h-4 rounded text-amber-900 border-amber-300 focus:ring-amber-500 cursor-pointer accent-[#854d0e]">
                                </label>

                                <!-- Meja Kerja Adjustable -->
                                <label class="flex items-center justify-between p-3.5 rounded-xl border transition-all cursor-pointer"
                                       :class="preferences.includes('meja_adjustable') ? 'bg-[#fffbeb] border-[#fde68a] text-slate-900 shadow-2xs' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50'">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-base" aria-hidden="true">🖥️</span>
                                        <span class="text-xs sm:text-sm font-medium">Meja Kerja Adjustable (Sit-to-Stand)</span>
                                    </div>
                                    <input type="checkbox" 
                                           name="accessibility_preferences[]" 
                                           value="meja_adjustable"
                                           :checked="preferences.includes('meja_adjustable')"
                                           @change="togglePref('meja_adjustable')"
                                           class="w-4 h-4 rounded text-amber-900 border-amber-300 focus:ring-amber-500 cursor-pointer accent-[#854d0e]">
                                </label>

                                <!-- Parkir Khusus Disabilitas -->
                                <label class="flex items-center justify-between p-3.5 rounded-xl border transition-all cursor-pointer"
                                       :class="preferences.includes('parkir_disabilitas') ? 'bg-[#fffbeb] border-[#fde68a] text-slate-900 shadow-2xs' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50'">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-base" aria-hidden="true">🅿️</span>
                                        <span class="text-xs sm:text-sm font-medium">Parkir Khusus Disabilitas</span>
                                    </div>
                                    <input type="checkbox" 
                                           name="accessibility_preferences[]" 
                                           value="parkir_disabilitas"
                                           :checked="preferences.includes('parkir_disabilitas')"
                                           @change="togglePref('parkir_disabilitas')"
                                           class="w-4 h-4 rounded text-amber-900 border-amber-300 focus:ring-amber-500 cursor-pointer accent-[#854d0e]">
                                </label>
                            </div>
                        </div>

                        <!-- Kategori 2: Komunikasi & Pendukung Digital -->
                        <div class="space-y-3 pt-2">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Komunikasi & Pendukung Digital</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Pilih dukungan komunikasi atau teknologi asistif:</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <!-- Penerjemah Bisindo -->
                                <label class="flex items-center justify-between p-3.5 rounded-xl border transition-all cursor-pointer"
                                       :class="preferences.includes('bisindo') ? 'bg-[#fffbeb] border-[#fde68a] text-slate-900 shadow-2xs' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50'">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-base" aria-hidden="true">🤟</span>
                                        <span class="text-xs sm:text-sm font-medium">Penerjemah Bahasa Isyarat (Bisindo)</span>
                                    </div>
                                    <input type="checkbox" 
                                           name="accessibility_preferences[]" 
                                           value="bisindo"
                                           :checked="preferences.includes('bisindo')"
                                           @change="togglePref('bisindo')"
                                           class="w-4 h-4 rounded text-amber-900 border-amber-300 focus:ring-amber-500 cursor-pointer accent-[#854d0e]">
                                </label>

                                <!-- Software Pembaca Layar -->
                                <label class="flex items-center justify-between p-3.5 rounded-xl border transition-all cursor-pointer"
                                       :class="preferences.includes('screen_reader') ? 'bg-[#fffbeb] border-[#fde68a] text-slate-900 shadow-2xs' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50'">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-base" aria-hidden="true">💻</span>
                                        <span class="text-xs sm:text-sm font-medium">Software Pembaca Layar (Screen Reader)</span>
                                    </div>
                                    <input type="checkbox" 
                                           name="accessibility_preferences[]" 
                                           value="screen_reader"
                                           :checked="preferences.includes('screen_reader')"
                                           @change="togglePref('screen_reader')"
                                           class="w-4 h-4 rounded text-amber-900 border-amber-300 focus:ring-amber-500 cursor-pointer accent-[#854d0e]">
                                </label>

                                <!-- Text-to-Speech / Closed Captioning -->
                                <label class="flex items-center justify-between p-3.5 rounded-xl border transition-all cursor-pointer"
                                       :class="preferences.includes('text_to_speech') ? 'bg-[#fffbeb] border-[#fde68a] text-slate-900 shadow-2xs' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50'">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-base" aria-hidden="true">💬</span>
                                        <span class="text-xs sm:text-sm font-medium">Text-to-Speech / Closed Captioning</span>
                                    </div>
                                    <input type="checkbox" 
                                           name="accessibility_preferences[]" 
                                           value="text_to_speech"
                                           :checked="preferences.includes('text_to_speech')"
                                           @change="togglePref('text_to_speech')"
                                           class="w-4 h-4 rounded text-amber-900 border-amber-300 focus:ring-amber-500 cursor-pointer accent-[#854d0e]">
                                </label>
                            </div>
                        </div>

                        <!-- Kategori 3: Fleksibilitas & Neurodiversity -->
                        <div class="space-y-3 pt-2">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">Fleksibilitas & Neurodiversity</h3>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <!-- Ruang Tenang -->
                                <label class="flex items-center justify-between p-3.5 rounded-xl border transition-all cursor-pointer"
                                       :class="preferences.includes('quiet_room') ? 'bg-[#fffbeb] border-[#fde68a] text-slate-900 shadow-2xs' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50'">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-base" aria-hidden="true">🪟</span>
                                        <span class="text-xs sm:text-sm font-medium">Ruang Tenang (Quiet Room)</span>
                                    </div>
                                    <input type="checkbox" 
                                           name="accessibility_preferences[]" 
                                           value="quiet_room"
                                           :checked="preferences.includes('quiet_room')"
                                           @change="togglePref('quiet_room')"
                                           class="w-4 h-4 rounded text-amber-900 border-amber-300 focus:ring-amber-500 cursor-pointer accent-[#854d0e]">
                                </label>

                                <!-- Jam Kerja Fleksibel -->
                                <label class="flex items-center justify-between p-3.5 rounded-xl border transition-all cursor-pointer"
                                       :class="preferences.includes('jam_fleksibel') ? 'bg-[#fffbeb] border-[#fde68a] text-slate-900 shadow-2xs' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50'">
                                    <div class="flex items-center gap-2.5">
                                        <span class="text-base" aria-hidden="true">🕒</span>
                                        <span class="text-xs sm:text-sm font-medium">Jam Kerja Fleksibel / Remote Work Option</span>
                                    </div>
                                    <input type="checkbox" 
                                           name="accessibility_preferences[]" 
                                           value="jam_fleksibel"
                                           :checked="preferences.includes('jam_fleksibel')"
                                           @change="togglePref('jam_fleksibel')"
                                           class="w-4 h-4 rounded text-amber-900 border-amber-300 focus:ring-amber-500 cursor-pointer accent-[#854d0e]">
                                </label>
                            </div>
                        </div>

                        <!-- Catatan Tambahan untuk Recruiter -->
                        <div class="pt-2">
                            <label for="input_accessibility_notes" class="block text-xs font-bold text-slate-800 mb-1.5">Catatan Tambahan untuk Recruiter</label>
                            <textarea id="input_accessibility_notes"
                                      name="accessibility_notes" 
                                      rows="3" 
                                      placeholder="Tuliskan kebutuhan khusus lainnya jika ada (misal: membutuhkan pencahayaan sedang, jadwal istirahat fleksibel)..." 
                                      class="w-full p-3 rounded-xl border border-slate-200 text-sm text-slate-800 bg-white focus:border-blue-800 focus:ring-1 focus:ring-blue-800 focus:outline-none">{{ old('accessibility_notes', $user->accessibility_notes) }}</textarea>
                        </div>

                        <!-- Bottom Action Bar -->
                        <div class="flex items-center justify-between pt-2">
                            <span class="text-xs font-medium text-slate-500" x-text="preferences.length + ' preferensi dipilih'"></span>
                            <button type="submit" 
                                    class="px-6 py-2.5 bg-[#193a7c] hover:bg-blue-900 text-white font-bold text-sm rounded-xl shadow-xs transition-colors focus:ring-2 focus:ring-blue-600 focus:outline-none">
                                Simpan Perubahan Preferensi
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ════════════════════════════════════════════════════════════════
                     TAB 3: CV & PORTOFOLIO
                ═════════════════════════════════════════════════════════════════ -->
                <div x-show="activeTab === 'cv'" 
                     x-cloak 
                     class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                    
                    <div>
                        <h2 class="text-xl font-extrabold text-[#193a7c] tracking-tight">Manajemen CV & Portofolio</h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">Unggah dokumen lamaran terbaik Anda agar mudah dilihat oleh recruiter perusahaan.</p>
                    </div>

                    <form method="POST" action="{{ route('kandidat.profil.update') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="_section" value="cv">

                        <!-- Dokumen CV / Resume Utama -->
                        <div class="space-y-3">
                            <h3 class="text-sm font-bold text-slate-900">Dokumen CV / Resume Utama</h3>

                            <div class="p-4 rounded-xl border border-slate-200 bg-white flex items-center justify-between">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-10 h-10 rounded-lg bg-rose-50 border border-rose-200 text-rose-600 flex flex-col items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs sm:text-sm font-bold text-slate-900 leading-snug">
                                            {{ $user->resume_file ?: 'CV_Nadia_Saraswati_Inklusif_2026.pdf' }}
                                        </h4>
                                        <p class="text-[11px] text-slate-500 mt-0.5">2.4 MB · Diunggah 2 hari lalu</p>
                                    </div>
                                </div>
                                <div class="w-6 h-6 rounded-full border border-slate-300 flex items-center justify-center text-slate-600">
                                    <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <button type="button" 
                                        class="px-4 py-2 bg-blue-50/80 hover:bg-blue-100 text-blue-900 text-xs font-semibold rounded-xl transition-colors">
                                    Ganti File CV
                                </button>
                                <button type="button" 
                                        class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold rounded-xl transition-colors">
                                    Unduh Preview
                                </button>
                            </div>
                            <p class="text-[11px] text-slate-400">Format PDF, maksimal 5 MB. Pastikan CV Anda sudah diperbarui.</p>
                        </div>

                        <!-- Tautan Portofolio / Tautan Karya -->
                        <div class="space-y-4 pt-3 border-t border-slate-100">
                            <h3 class="text-sm font-bold text-slate-900">Tautan Portofolio / Tautan Karya</h3>

                            <div>
                                <label for="input_portfolio_url" class="block text-xs font-bold text-slate-800 mb-1.5">Link Figma / Behance / Website Pribadi</label>
                                <input type="url" 
                                       id="input_portfolio_url"
                                       name="portfolio_url" 
                                       value="{{ old('portfolio_url', $user->portfolio_url ?: 'https://behance.net/nadiasaras') }}" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-900 bg-white focus:border-blue-800 focus:ring-1 focus:ring-blue-800 focus:outline-none" 
                                       placeholder="https://behance.net/username">
                            </div>

                            <div>
                                <label for="input_linkedin_url" class="block text-xs font-bold text-slate-800 mb-1.5">Link LinkedIn</label>
                                <input type="url" 
                                       id="input_linkedin_url"
                                       name="linkedin_url" 
                                       value="{{ old('linkedin_url', $user->linkedin_url ?: 'https://linkedin.com/in/nadiasaraswati') }}" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-900 bg-white focus:border-blue-800 focus:ring-1 focus:ring-blue-800 focus:outline-none" 
                                       placeholder="https://linkedin.com/in/username">
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" 
                                    class="px-6 py-2.5 bg-[#193a7c] hover:bg-blue-900 text-white font-bold text-sm rounded-xl shadow-xs transition-colors focus:ring-2 focus:ring-blue-600 focus:outline-none">
                                Simpan Tautan & Berkas
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ════════════════════════════════════════════════════════════════
                     TAB 5: PENGATURAN AKUN & KEAMANAN
                ═════════════════════════════════════════════════════════════════ -->
                <div x-show="activeTab === 'security'" 
                     x-cloak 
                     class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                    
                    <div>
                        <h2 class="text-xl font-extrabold text-[#193a7c] tracking-tight">Pengaturan Akun & Keamanan</h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-1">Kelola kredensial login dan keamanan akun Anda.</p>
                    </div>

                    <form method="POST" action="{{ route('kandidat.profil.update') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="_section" value="security">

                        <div>
                            <label for="input_current_password" class="block text-xs font-bold text-slate-800 mb-1.5">Kata Sandi Saat Ini</label>
                            <input type="password" 
                                   id="input_current_password"
                                   name="current_password" 
                                   placeholder="••••••••••••" 
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-900 bg-white focus:border-blue-800 focus:ring-1 focus:ring-blue-800 focus:outline-none">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="input_new_password" class="block text-xs font-bold text-slate-800 mb-1.5">Kata Sandi Baru</label>
                                <input type="password" 
                                       id="input_new_password"
                                       name="new_password" 
                                       placeholder="Minimal 8 karakter" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-900 bg-white focus:border-blue-800 focus:ring-1 focus:ring-blue-800 focus:outline-none">
                            </div>
                            <div>
                                <label for="input_new_password_confirmation" class="block text-xs font-bold text-slate-800 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                                <input type="password" 
                                       id="input_new_password_confirmation"
                                       name="new_password_confirmation" 
                                       placeholder="Ulangi kata sandi baru" 
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-900 bg-white focus:border-blue-800 focus:ring-1 focus:ring-blue-800 focus:outline-none">
                            </div>
                        </div>

                        <p class="text-xs text-slate-500">Gunakan minimal 8 karakter dengan kombinasi huruf, angka, dan simbol.</p>

                        <div class="flex justify-end pt-2">
                            <button type="submit" 
                                    class="px-6 py-2.5 bg-[#193a7c] hover:bg-blue-900 text-white font-bold text-sm rounded-xl shadow-xs transition-colors focus:ring-2 focus:ring-blue-600 focus:outline-none">
                                Perbarui Kata Sandi
                            </button>
                        </div>
                    </form>

                    <!-- Zona Bahaya -->
                    <div class="pt-6 border-t border-slate-200">
                        <h3 class="text-xs font-bold text-rose-600 uppercase tracking-wider mb-2">Zona Bahaya</h3>
                        <div class="p-4 rounded-xl bg-rose-50/50 border border-rose-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <p class="text-xs text-rose-900 leading-relaxed">
                                Menghapus akun akan menghilangkan seluruh data riwayat lamaran dan preferensi Anda secara permanen.
                            </p>
                            <button type="button" 
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan.')" 
                                    class="px-4 py-2 bg-white border border-rose-300 text-rose-700 hover:bg-rose-100 text-xs font-bold rounded-xl shadow-2xs flex-shrink-0 transition-colors">
                                Hapus Akun Saya
                            </button>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
