@extends('layouts.app')

@section('title', 'Daftar Akun — KerjaSetara')
@section('meta_description', 'Bergabung dengan KerjaSetara sebagai pencari kerja atau perusahaan ramah disabilitas.')

@section('content')
<section class="min-h-[calc(100vh-4rem)] flex flex-col justify-center py-8 lg:py-12 bg-slate-50" aria-label="Halaman Pendaftaran">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100 grid lg:grid-cols-12 min-h-[640px]">

            {{-- ════════════════════════════════════════════════════════════════════
                LEFT PANEL: Visual & Values
            ════════════════════════════════════════════════════════════════════ --}}
            <div class="lg:col-span-5 auth-left-panel p-8 sm:p-10 lg:p-12 flex flex-col justify-between relative overflow-hidden">
                <div class="absolute -top-16 -left-16 w-48 h-48 bg-blue-200/50 rounded-full blur-2xl pointer-events-none" aria-hidden="true"></div>
                <div class="absolute -bottom-16 -right-16 w-56 h-56 bg-indigo-200/40 rounded-full blur-2xl pointer-events-none" aria-hidden="true"></div>

                <div class="relative z-10">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 group mb-8" aria-label="Kembali ke Beranda KerjaSetara">
                        <div class="w-9 h-9 bg-blue-900 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-blue-900 tracking-tight">KerjaSetara</span>
                    </a>

                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-100/80 text-blue-800 text-xs font-semibold mb-4">
                        <span>🌱</span> Langkah awal karir setara
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-snug tracking-tight mb-4">
                        Bergabunglah dalam Ekosistem Karir yang Inklusif.
                    </h1>

                    <p class="text-sm text-slate-600 leading-relaxed mb-6">
                        Apakah Anda seorang talenta yang mencari peluang yang menghargai kemampuan Anda, atau perusahaan yang berkomitmen membangun lingkungan kerja yang inklusif?
                    </p>

                    <div class="space-y-3">
                        <div class="flex items-start gap-3 bg-white/70 backdrop-blur-sm p-3 rounded-xl border border-white">
                            <span class="text-green-600 font-bold">✓</span>
                            <p class="text-xs text-slate-700"><strong>Smart Matching:</strong> Rekomendasi pekerjaan berdasarkan kecocokan aksesibilitas dan skill.</p>
                        </div>
                        <div class="flex items-start gap-3 bg-white/70 backdrop-blur-sm p-3 rounded-xl border border-white">
                            <span class="text-green-600 font-bold">✓</span>
                            <p class="text-xs text-slate-700"><strong>Aksesibilitas Terverifikasi:</strong> Informasi fasilitas kantor yang transparan dan akurat.</p>
                        </div>
                        <div class="flex items-start gap-3 bg-white/70 backdrop-blur-sm p-3 rounded-xl border border-white">
                            <span class="text-green-600 font-bold">✓</span>
                            <p class="text-xs text-slate-700"><strong>100% Bebas Diskriminasi:</strong> Menjamin perlindungan privasi dan kenyamanan Anda.</p>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 pt-6 text-xs text-slate-500 border-t border-blue-200/60 mt-6">
                    Dirancang untuk kenyamanan semua talenta Indonesia 🇮🇩
                </div>
            </div>

            {{-- ════════════════════════════════════════════════════════════════════
                RIGHT PANEL: Registration Form
            ════════════════════════════════════════════════════════════════════ --}}
            <div class="lg:col-span-7 p-8 sm:p-10 lg:p-12 flex flex-col justify-center"
                 x-data="{
                     role: '{{ old('role', request('role', 'kandidat')) }}',
                     category: '{{ old('accessibility_category', 'fisik') }}'
                 }">
                <div class="max-w-md w-full mx-auto">

                    <div class="mb-6">
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight mb-2">
                            Buat Akun Baru
                        </h2>
                        <p class="text-sm text-slate-500">
                            Lengkapi data diri Anda untuk memulai perjalanan karir inklusif.
                        </p>
                    </div>

                    <!-- Error Alert -->
                    @if ($errors->any())
                        <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm flex items-start gap-3" role="alert">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <!-- Role Selector -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                                Daftar Sebagai
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="role" value="kandidat" x-model="role" class="sr-only">
                                    <div :class="role === 'kandidat' ? 'border-blue-900 bg-blue-50 text-blue-900 ring-2 ring-blue-900/20' : 'border-slate-200 text-slate-600 hover:border-slate-300'"
                                         class="p-3 rounded-xl border text-center transition-all">
                                        <div class="text-lg mb-1">🎯</div>
                                        <div class="font-bold text-xs">Pencari Kerja</div>
                                        <div class="text-[10px] text-slate-500">Kandidat Talenta</div>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="role" value="hrd" x-model="role" class="sr-only">
                                    <div :class="role === 'hrd' ? 'border-blue-900 bg-blue-50 text-blue-900 ring-2 ring-blue-900/20' : 'border-slate-200 text-slate-600 hover:border-slate-300'"
                                         class="p-3 rounded-xl border text-center transition-all">
                                        <div class="text-lg mb-1">🏢</div>
                                        <div class="font-bold text-xs">Perusahaan / HR</div>
                                        <div class="text-[10px] text-slate-500">Pasang Lowongan</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                Nama Lengkap <span x-show="role === 'hrd'">(atau Nama Perwakilan)</span>
                            </label>
                            <input id="name"
                                   name="name"
                                   type="text"
                                   required
                                   value="{{ old('name') }}"
                                   placeholder="Contoh: Siti Rahmawati"
                                   class="input-field @error('name') border-red-500 @enderror">
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                Alamat Email
                            </label>
                            <input id="email"
                                   name="email"
                                   type="email"
                                   required
                                   value="{{ old('email') }}"
                                   placeholder="nama@email.com"
                                   class="input-field @error('email') border-red-500 @enderror">
                        </div>

                        <!-- Accessibility Category (Only for Candidate) -->
                        <div x-show="role === 'kandidat'" x-transition>
                            <label for="accessibility_category" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                Kebutuhan Aksesibilitas Utama
                            </label>
                            <select id="accessibility_category"
                                    name="accessibility_category"
                                    class="input-field text-sm">
                                <option value="fisik" {{ old('accessibility_category') == 'fisik' ? 'selected' : '' }}>♿ Aksesibilitas Fisik (Mobilitas, Ramp, Lift)</option>
                                <option value="komunikasi" {{ old('accessibility_category') == 'komunikasi' ? 'selected' : '' }}>🗣️ Komunikasi Inklusif (Bahasa Isyarat, Subtitle)</option>
                                <option value="digital" {{ old('accessibility_category') == 'digital' ? 'selected' : '' }}>💻 Dukungan Digital (Screen Reader, High Contrast)</option>
                                <option value="neurodiverse" {{ old('accessibility_category') == 'neurodiverse' ? 'selected' : '' }}>🧠 Lingkungan Neurodiverse (Ruang Tenang, Fleksibel)</option>
                            </select>
                            <p class="text-[11px] text-slate-500 mt-1">Dapat diatur lebih rinci lagi di profil Anda nantinya.</p>
                        </div>

                        <!-- Password Fields -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Kata Sandi
                                </label>
                                <input id="password"
                                       name="password"
                                       type="password"
                                       required
                                       placeholder="Min. 8 karakter"
                                       class="input-field @error('password') border-red-500 @enderror">
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Konfirmasi Sandi
                                </label>
                                <input id="password_confirmation"
                                       name="password_confirmation"
                                       type="password"
                                       required
                                       placeholder="Ulangi sandi"
                                       class="input-field">
                            </div>
                        </div>

                        <!-- Terms Checkbox -->
                        <div class="flex items-start pt-1">
                            <input id="terms"
                                   type="checkbox"
                                   required
                                   class="h-4 w-4 mt-0.5 rounded border-slate-300 text-blue-900 focus:ring-blue-600 transition-colors">
                            <label for="terms" class="ml-2.5 block text-xs text-slate-600 leading-normal">
                                Saya menyetujui <a href="#" class="text-blue-900 font-semibold underline">Syarat & Ketentuan</a> serta <a href="#" class="text-blue-900 font-semibold underline">Kebijakan Privasi</a> KerjaSetara.
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                                class="w-full btn-primary py-3 justify-center text-sm font-semibold rounded-xl shadow-md hover:shadow-lg transition-all mt-2">
                            Daftar Sekarang
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </form>

                    <!-- Login Link -->
                    <p class="text-center text-xs text-slate-600 mt-5">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-bold text-blue-900 hover:text-blue-700 transition-colors underline decoration-blue-300 underline-offset-2">
                            Masuk di Sini
                        </a>
                    </p>

                </div>
            </div>

        </div>
    </div>
</section>
@endsection
