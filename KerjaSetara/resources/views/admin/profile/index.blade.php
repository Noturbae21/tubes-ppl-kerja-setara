@extends('admin.layouts.app')

@section('title', 'Profil Administrator')
@section('page_title', 'Profil Administrator')

@section('content')
<div class="space-y-6" x-data="adminProfileForm()">

    <!-- ══════════════════════════════════════════════════════════════════════
        BREADCRUMB & HEADER SECTION
    ══════════════════════════════════════════════════════════════════════ -->
    <div class="space-y-1">
        <nav class="flex items-center gap-2 text-xs text-slate-400 font-medium" aria-label="Breadcrumb">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-600 transition-colors">Admin</a>
            <span>/</span>
            <span class="text-slate-600 font-semibold">Profil Administrator</span>
        </nav>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Profil Administrator</h2>
        <p class="text-xs text-slate-500">Kelola informasi akun administrator utama Anda.</p>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════
        FLASH NOTIFICATIONS (SUCCESS / ERROR / VALIDATION)
    ══════════════════════════════════════════════════════════════════════ -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 flex items-start gap-3 shadow-xs">
            <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="flex-1">
                <div class="text-xs font-bold text-emerald-900">Pembaruan Berhasil</div>
                <div class="text-xs text-emerald-700 mt-0.5">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200/80 flex items-start gap-3 shadow-xs">
            <div class="w-7 h-7 rounded-lg bg-red-100 text-red-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <div class="flex-1">
                <div class="text-xs font-bold text-red-900">Terdapat kesalahan pada input formulir:</div>
                <ul class="list-disc list-inside text-xs text-red-800 mt-1 space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- ══════════════════════════════════════════════════════════════════════
        MAIN PROFILE FORM GRID
    ══════════════════════════════════════════════════════════════════════ -->
    <form id="admin-profile-form"
          method="POST"
          action="{{ route('admin.profile.update') }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- ──────────────────────────────────────────────────────────────
                LEFT COLUMN: PROFILE PICTURE & IDENTITY SUMMARY CARD
            ────────────────────────────────────────────────────────────── -->
            <div class="lg:col-span-4 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex flex-col items-center text-center space-y-4">

                <!-- Avatar Circle Container (28x28 with Live Preview) -->
                <div class="relative">
                    <div class="w-28 h-28 rounded-full overflow-hidden flex items-center justify-center bg-blue-50 text-blue-900 border-2 border-blue-100 shadow-xs text-3xl font-extrabold select-none">
                        <!-- 1. Live Client-side Upload Preview -->
                        <template x-if="avatarPreview">
                            <img :src="avatarPreview" alt="Pratinjau Foto Profil" class="w-full h-full object-cover">
                        </template>

                        <!-- 2. Existing Database Avatar -->
                        <template x-if="!avatarPreview && hasExistingAvatar">
                            <img src="{{ $admin->avatar_url }}" alt="{{ $admin->name }}" class="w-full h-full object-cover">
                        </template>

                        <!-- 3. Name Initials Fallback -->
                        <template x-if="!avatarPreview && !hasExistingAvatar">
                            <span x-text="initials">{{ $admin->initials }}</span>
                        </template>
                    </div>

                    <!-- Hidden File Input -->
                    <input type="file"
                           name="avatar"
                           id="avatar_file_input"
                           accept="image/png,image/jpeg,image/jpg"
                           class="sr-only"
                           @change="handleAvatarSelect($event)">
                </div>

                <!-- Admin Name & Role Titles -->
                <div class="space-y-1">
                    <h3 class="text-base font-bold text-slate-900 tracking-tight" x-text="name || '{{ $admin->name }}'">
                        {{ $admin->name }}
                    </h3>
                    <p class="text-xs text-slate-500 font-medium">Super Admin</p>
                </div>

                <!-- Role Badge -->
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-800 border border-blue-200/60">
                        Administrator Utama KerjaSetara
                    </span>
                </div>

                <!-- Change Photo Button -->
                <div class="w-full pt-1">
                    <button type="button"
                            @click="document.getElementById('avatar_file_input').click()"
                            class="w-full py-2.5 px-4 rounded-xl border border-slate-200 hover:border-slate-300 bg-white hover:bg-slate-50 text-blue-950 font-bold text-xs shadow-2xs transition-all cursor-pointer">
                        Ganti Foto Profil
                    </button>
                    <p class="text-[11px] text-slate-400 mt-2">
                        Format JPG atau PNG. Maksimal 2 MB.
                    </p>
                </div>

                <!-- Security Authority Box -->
                <div class="w-full rounded-2xl bg-slate-50 border border-slate-100 p-3.5 text-left flex items-start gap-3 mt-2">
                    <div class="w-7 h-7 rounded-lg bg-blue-100/70 text-blue-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-900 block">Akses pengelolaan penuh</span>
                        <span class="text-[11px] text-slate-500 leading-tight block mt-0.5">
                            Kelola pengguna, verifikasi perusahaan, dan audit aktivitas platform.
                        </span>
                    </div>
                </div>

            </div>

            <!-- ──────────────────────────────────────────────────────────────
                RIGHT COLUMN: ACCOUNT DETAILS & CHANGE PASSWORD FORM
            ────────────────────────────────────────────────────────────── -->
            <div class="lg:col-span-8 bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs space-y-6">

                <!-- SECTION 1: INFORMASI AKUN -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 pb-0.5">Informasi Akun</h3>

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="input_name" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               id="input_name"
                               x-model="name"
                               required
                               placeholder="Nama lengkap Anda"
                               class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all @error('name') border-red-300 ring-2 ring-red-100 @enderror">
                        @error('name')
                            <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Grid 2 Kolom: Email Admin & Nomor Telepon -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Email Admin -->
                        <div>
                            <label for="input_email" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Email Admin <span class="text-red-500">*</span>
                            </label>
                            <input type="email"
                                   name="email"
                                   id="input_email"
                                   x-model="email"
                                   required
                                   placeholder="fakhri.admin@example.com"
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all @error('email') border-red-300 ring-2 ring-red-100 @enderror">
                            @error('email')
                                <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor Telepon -->
                        <div>
                            <label for="input_phone" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Nomor Telepon
                            </label>
                            <input type="text"
                                   name="phone"
                                   id="input_phone"
                                   x-model="phone"
                                   placeholder="08xx-xxxx-xxxx"
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all @error('phone') border-red-300 ring-2 ring-red-100 @enderror">
                            @error('phone')
                                <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: UBAH KATA SANDI -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <div class="flex items-center justify-between pb-0.5">
                        <h3 class="text-sm font-bold text-slate-900">Ubah Kata Sandi</h3>
                        <span class="text-xs text-slate-400">Kosongkan jika tidak ingin mengubah.</span>
                    </div>

                    <!-- Kata Sandi Saat Ini -->
                    <div>
                        <label for="input_current_password" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Kata Sandi Saat Ini
                        </label>
                        <div class="relative">
                            <input :type="showCurrentPassword ? 'text' : 'password'"
                                   name="current_password"
                                   id="input_current_password"
                                   x-model="currentPassword"
                                   placeholder="••••••••••••"
                                   autocomplete="current-password"
                                   class="w-full pl-3.5 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all @error('current_password') border-red-300 ring-2 ring-red-100 @enderror">
                            <button type="button"
                                    @click="showCurrentPassword = !showCurrentPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                                    tabindex="-1">
                                <template x-if="showCurrentPassword">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </template>
                                <template x-if="!showCurrentPassword">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                    </svg>
                                </template>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Grid 2 Kolom: Kata Sandi Baru & Konfirmasi -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Kata Sandi Baru -->
                        <div>
                            <label for="input_new_password" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Kata Sandi Baru
                            </label>
                            <div class="relative">
                                <input :type="showNewPassword ? 'text' : 'password'"
                                       name="new_password"
                                       id="input_new_password"
                                       x-model="newPassword"
                                       placeholder="••••••••••••"
                                       autocomplete="new-password"
                                       class="w-full pl-3.5 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all @error('new_password') border-red-300 ring-2 ring-red-100 @enderror">
                                <button type="button"
                                        @click="showNewPassword = !showNewPassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                                        tabindex="-1">
                                    <template x-if="showNewPassword">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </template>
                                    <template x-if="!showNewPassword">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                        </svg>
                                    </template>
                                </button>
                            </div>
                            @error('new_password')
                                <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Konfirmasi Kata Sandi Baru -->
                        <div>
                            <label for="input_new_password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Konfirmasi Kata Sandi Baru
                            </label>
                            <div class="relative">
                                <input :type="showConfirmPassword ? 'text' : 'password'"
                                       name="new_password_confirmation"
                                       id="input_new_password_confirmation"
                                       x-model="confirmPassword"
                                       placeholder="••••••••••••"
                                       autocomplete="new-password"
                                       class="w-full pl-3.5 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
                                <button type="button"
                                        @click="showConfirmPassword = !showConfirmPassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                                        tabindex="-1">
                                    <template x-if="showConfirmPassword">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </template>
                                    <template x-if="!showConfirmPassword">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                        </svg>
                                    </template>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ACTION BUTTONS: BATAL & SIMPAN PERUBAHAN -->
                <div class="flex items-center justify-end gap-3 pt-3">
                    <button type="button"
                            @click="resetForm()"
                            class="px-5 py-2.5 border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-bold transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-6 py-2.5 bg-[#193a7c] hover:bg-[#132e63] text-white rounded-xl text-xs font-bold shadow-xs transition-all cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>

            </div>
        </div>
    </form>

    <!-- ══════════════════════════════════════════════════════════════════════
        BOTTOM CARD: KELUAR DARI SESI ADMIN (LOGOUT)
    ══════════════════════════════════════════════════════════════════════ -->
    <div onclick="document.getElementById('admin-profile-logout-form').submit()"
         class="border border-red-200 bg-white hover:bg-red-50/40 rounded-2xl p-4 flex items-center justify-between transition-colors shadow-2xs group cursor-pointer">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-xl bg-red-50 text-red-500 border border-red-100 flex items-center justify-center flex-shrink-0 mr-3.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </div>
            <div>
                <span class="text-xs sm:text-sm font-bold text-red-600 block">Keluar dari Sesi Admin (Logout)</span>
                <span class="text-[11px] sm:text-xs text-slate-500 block mt-0.5">
                    Akhiri sesi administrator dengan aman setelah selesai mengelola platform.
                </span>
            </div>
        </div>
        <div class="text-red-400 group-hover:text-red-600 group-hover:translate-x-1 transition-all pr-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </div>
    </div>

    <!-- Hidden Dedicated Logout Form -->
    <form id="admin-profile-logout-form" method="POST" action="{{ route('admin.logout') }}" class="hidden">
        @csrf
    </form>

</div>

<!-- ══════════════════════════════════════════════════════════════════════════
    ALPINE.JS FORM STATE & LOGIC
══════════════════════════════════════════════════════════════════════════ -->
<script>
function adminProfileForm() {
    return {
        // Initial Server State for Reset
        initialName: @json(old('name', $admin->name)),
        initialEmail: @json(old('email', $admin->email)),
        initialPhone: @json(old('phone', $admin->phone ?? '')),
        hasExistingAvatar: @json(!empty($admin->avatar)),
        initialInitials: @json($admin->initials),

        // Live Form State
        name: @json(old('name', $admin->name)),
        email: @json(old('email', $admin->email)),
        phone: @json(old('phone', $admin->phone ?? '')),
        avatarPreview: null,

        // Password Fields
        currentPassword: '',
        newPassword: '',
        confirmPassword: '',

        // Password Visibility Toggles
        showCurrentPassword: false,
        showNewPassword: false,
        showConfirmPassword: false,

        get initials() {
            const raw = (this.name || 'Admin').trim();
            const parts = raw.split(/\s+/);
            if (parts.length >= 2) {
                return (parts[0][0] + parts[1][0]).toUpperCase();
            }
            return raw.substring(0, 2).toUpperCase();
        },

        // Client-side Avatar File Preview & Validation
        handleAvatarSelect(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Check file type
            const allowed = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!allowed.includes(file.type)) {
                alert('Peringatan: Format berkas harus berupa gambar JPG atau PNG.');
                event.target.value = '';
                return;
            }

            // Check size (2 MB = 2097152 bytes)
            if (file.size > 2 * 1024 * 1024) {
                alert('Peringatan: Ukuran foto profil melebihi batas maksimal 2 MB.');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                this.avatarPreview = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        // Reset Form to Initial State
        resetForm() {
            this.name = this.initialName;
            this.email = this.initialEmail;
            this.phone = this.initialPhone;
            this.currentPassword = '';
            this.newPassword = '';
            this.confirmPassword = '';
            this.avatarPreview = null;
            this.showCurrentPassword = false;
            this.showNewPassword = false;
            this.showConfirmPassword = false;

            const fileInput = document.getElementById('avatar_file_input');
            if (fileInput) {
                fileInput.value = '';
            }
        }
    };
}
</script>
@endsection
