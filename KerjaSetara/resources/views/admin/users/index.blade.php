@extends('admin.layouts.app')

@section('title', 'Manajemen Pengguna Platform')
@section('page_title', 'Manajemen Pengguna Platform')

@section('content')
<div x-data="userManagement()" class="space-y-6">

    <!-- ══════════════════════════════════════════════════════════════════════
        PAGE BREADCRUMB & HEADER (Matches Image 1)
    ══════════════════════════════════════════════════════════════════════ -->
    <div>
        <div class="flex items-center gap-2 text-xs font-medium text-slate-400 mb-1">
            <span>Admin</span>
            <span>/</span>
            <span class="text-slate-600 font-semibold">Kelola User</span>
        </div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Manajemen Pengguna Platform</h1>
        <p class="text-xs text-slate-500 mt-1">Kelola akun seluruh kandidat dan perusahaan yang terdaftar.</p>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════
        SEARCH & FILTER BAR (Exact Match to Image 1)
    ══════════════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('admin.users') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 flex-1">
                <!-- Search Input -->
                <div class="relative flex-1 max-w-md">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle cx="11" cy="11" r="8" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35"/>
                        </svg>
                    </span>
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari nama, email, atau role..."
                           class="w-full bg-slate-50/70 border border-slate-200 focus:border-blue-700 focus:bg-white text-slate-900 text-xs rounded-xl pl-9 pr-3 py-2.5 outline-none transition-colors">
                </div>

                <!-- Role Filter Dropdown -->
                <div class="relative">
                    <select name="role"
                            onchange="this.form.submit()"
                            class="w-full sm:w-auto appearance-none bg-white border border-slate-200 text-xs font-semibold rounded-xl pl-3.5 pr-8 py-2.5 text-slate-700 outline-none focus:border-blue-700 cursor-pointer">
                        <option value="all" {{ $roleFilter === 'all' ? 'selected' : '' }}>Role: Semua</option>
                        <option value="kandidat" {{ $roleFilter === 'kandidat' ? 'selected' : '' }}>Role: Kandidat</option>
                        <option value="hrd" {{ $roleFilter === 'hrd' ? 'selected' : '' }}>Role: Perusahaan</option>
                        <option value="admin" {{ $roleFilter === 'admin' ? 'selected' : '' }}>Role: Admin</option>
                    </select>
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </span>
                </div>

                <!-- Status Filter Dropdown -->
                <div class="relative">
                    <select name="status"
                            onchange="this.form.submit()"
                            class="w-full sm:w-auto appearance-none bg-white border border-slate-200 text-xs font-semibold rounded-xl pl-3.5 pr-8 py-2.5 text-slate-700 outline-none focus:border-blue-700 cursor-pointer">
                        <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Status: Semua</option>
                        <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Status: Aktif</option>
                        <option value="blocked" {{ $statusFilter === 'blocked' ? 'selected' : '' }}>Status: Diblokir</option>
                    </select>
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </span>
                </div>

                @if(!empty($search) || $roleFilter !== 'all' || $statusFilter !== 'all')
                    <a href="{{ route('admin.users') }}" class="text-xs text-slate-500 hover:text-blue-900 underline px-1 py-2">
                        Reset
                    </a>
                @endif
            </div>

            <!-- Add User Button -->
            <button type="button"
                    @click="openAddModal()"
                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-blue-900 hover:bg-blue-800 text-white text-xs font-bold transition-all shadow-sm cursor-pointer whitespace-nowrap self-end sm:self-auto">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>+ Tambah User Baru</span>
            </button>
        </form>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════
        USERS DATA TABLE (Matches Image 1)
    ══════════════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 font-bold uppercase text-[10px] tracking-wider">
                        <th class="py-4 px-4 w-12 text-center">
                            <input type="checkbox" class="rounded text-blue-900 focus:ring-0 cursor-pointer">
                        </th>
                        <th class="py-4 px-4">Nama Pengguna</th>
                        <th class="py-4 px-4">Email</th>
                        <th class="py-4 px-4">Role</th>
                        <th class="py-4 px-4">Tanggal Gabung</th>
                        <th class="py-4 px-4">Status</th>
                        <th class="py-4 px-6 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/60 transition-colors {{ $user->isBlocked() ? 'bg-red-50/20' : '' }}">
                            <!-- Checkbox -->
                            <td class="py-4 px-4 text-center">
                                <input type="checkbox" class="rounded text-blue-900 focus:ring-0 cursor-pointer">
                            </td>

                            <!-- Nama & Identifier (KS-U001, KS-C001, KS-A001) -->
                            <td class="py-4 px-4">
                                <div class="font-bold text-slate-900 text-sm">
                                    {{ $user->name }}
                                </div>
                                <div class="text-[11px] font-mono text-slate-400 mt-0.5">
                                    {{ $user->identifier }}
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="py-4 px-4 text-slate-600">
                                {{ $user->email }}
                            </td>

                            <!-- Role Badge (Matches Image 1) -->
                            <td class="py-4 px-4">
                                @if($user->isAdmin())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-200/60">
                                        Admin
                                    </span>
                                @elseif($user->isHrd())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium text-slate-700 bg-slate-100 border border-slate-200/60">
                                        Perusahaan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium text-slate-700 bg-slate-100 border border-slate-200/60">
                                        Kandidat
                                    </span>
                                @endif
                            </td>

                            <!-- Tanggal Gabung (e.g. 25 Okt 2026) -->
                            <td class="py-4 px-4 text-slate-500 text-xs">
                                {{ $user->formatted_joined_date }}
                            </td>

                            <!-- Status Akun (● Aktif / ● Diblokir) -->
                            <td class="py-4 px-4">
                                @if($user->isBlocked())
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-red-600">
                                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                        <span>Diblokir</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <span>Aktif</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Tindakan (Pencil & Trash, Matches Image 1) -->
                            <td class="py-4 px-6 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <!-- Edit Button (Pencil in Box) -->
                                    <button type="button"
                                            @click="openEditModal({{ json_encode([
                                                'id' => $user->id,
                                                'name' => $user->name,
                                                'email' => $user->email,
                                                'role' => $user->role === 'hrd' ? 'perusahaan' : ($user->role === 'user' ? 'kandidat' : $user->role),
                                                'status' => $user->status ?? 'active',
                                                'is_active' => !$user->isBlocked(),
                                                'identifier' => $user->identifier,
                                                'is_self' => $user->id === auth()->id(),
                                            ]) }})"
                                            title="Edit Informasi Pengguna"
                                            class="p-1.5 text-blue-900 hover:bg-slate-100 border border-slate-200 rounded-lg transition-colors cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </button>

                                    <!-- Delete / Block Button (Trash in Red Box) -->
                                    @if(auth()->id() !== $user->id)
                                        <button type="button"
                                                @click="openBlockOrDeleteModal({{ json_encode([
                                                    'id' => $user->id,
                                                    'name' => $user->name,
                                                    'email' => $user->email,
                                                    'role_label' => $user->role_label,
                                                    'is_blocked' => $user->isBlocked(),
                                                    'block_url' => route('admin.users.block', $user->id),
                                                    'delete_url' => route('admin.users.destroy', $user->id),
                                                ]) }})"
                                                title="Blokir atau Hapus Akun"
                                                class="p-1.5 text-red-600 hover:bg-red-50 border border-red-200 rounded-lg transition-colors cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-400 text-sm">
                                <div class="max-w-xs mx-auto text-center space-y-2">
                                    <div class="font-bold text-slate-700">Tidak ada data pengguna</div>
                                    <p class="text-xs text-slate-500">Tidak ada akun yang sesuai dengan pencarian atau filter yang dipilih.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination & Summary Footer (Matches Image 1) -->
        <div class="px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-500">
            <div>
                Menampilkan {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} pengguna aktif
            </div>
            <div class="flex items-center gap-4">
                <span>Halaman {{ $users->currentPage() }} dari {{ max(1, $users->lastPage()) }}</span>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>

        <!-- Bottom Audit & Legend Bar -->
        <div class="px-6 py-3.5 bg-slate-50/90 border-t border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-[11px] text-slate-500">
            <div class="flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Perubahan akun tercatat di Log Aktivitas. Seluruh data pengguna adalah contoh.</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-slate-400">Referensi status:</span>
                <span class="inline-flex items-center gap-1 font-medium text-emerald-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Aktif
                </span>
                <span class="inline-flex items-center gap-1 font-medium text-red-600">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span> Diblokir
                </span>
            </div>
        </div>
    </div>


    <!-- ══════════════════════════════════════════════════════════════════════
        MODAL: EDIT INFORMASI PENGGUNA (Matches Image 1 / Previous Design)
    ══════════════════════════════════════════════════════════════════════ -->
    <div x-show="editModalOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
             @click="editModalOpen = false"></div>

        <!-- Modal Dialog -->
        <div class="relative bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 z-10"
             @click.stop>
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">Edit Informasi Pengguna</h3>
                <button type="button"
                        @click="editModalOpen = false"
                        class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form :action="'{{ url('admin/users') }}/' + editUser.id" method="POST" class="mt-6 space-y-5">
                @csrf
                @method('PUT')

                <!-- Nama Lengkap / Nama Perusahaan -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">Nama Lengkap / Nama Perusahaan</label>
                    <input type="text"
                           name="name"
                           x-model="editUser.name"
                           required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-blue-700 focus:ring-1 focus:ring-blue-700 outline-none text-slate-900 transition-colors">
                </div>

                <!-- Email Pengguna -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">Email Pengguna</label>
                    <input type="email"
                           name="email"
                           x-model="editUser.email"
                           required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-blue-700 focus:ring-1 focus:ring-blue-700 outline-none text-slate-900 transition-colors">
                </div>

                <!-- Role Pengguna -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">Role Pengguna</label>
                    <select name="role"
                            x-model="editUser.role"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-blue-700 focus:ring-1 focus:ring-blue-700 outline-none text-slate-900 transition-colors bg-white">
                        <option value="kandidat">Kandidat</option>
                        <option value="perusahaan">Perusahaan</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>

                <!-- Status Akun: Aktif Toggle Switch -->
                <div class="pt-2 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-800">
                            Status Akun:
                            <span :class="editUser.is_active ? 'text-emerald-700' : 'text-red-600'" x-text="editUser.is_active ? 'Aktif' : 'Diblokir'"></span>
                        </span>
                        <template x-if="editUser.is_self">
                            <p class="text-[10px] text-amber-600 mt-0.5">Akun administrator Anda sendiri tidak dapat diblokir.</p>
                        </template>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-500 uppercase" x-text="editUser.is_active ? 'ON' : 'OFF'"></span>
                        <input type="hidden" name="status" :value="editUser.is_active ? 'active' : 'blocked'">
                        <button type="button"
                                @click="if(!editUser.is_self) editUser.is_active = !editUser.is_active"
                                :disabled="editUser.is_self"
                                :class="editUser.is_active ? 'bg-blue-900' : 'bg-slate-300'"
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed"
                                role="switch"
                                :aria-checked="editUser.is_active">
                            <span :class="editUser.is_active ? 'translate-x-5' : 'translate-x-0'"
                                  class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out flex items-center justify-center">
                                <template x-if="editUser.is_active">
                                    <svg class="w-3 h-3 text-blue-900" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </template>
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button"
                            @click="editModalOpen = false"
                            class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-blue-900 hover:bg-blue-800 text-white text-xs font-bold transition-colors shadow-md shadow-blue-900/20 cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- ══════════════════════════════════════════════════════════════════════
        MODAL: TAMBAH PENGGUNA BARU (Exact Match to Image 4)
    ══════════════════════════════════════════════════════════════════════ -->
    <div x-show="addModalOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
             @click="addModalOpen = false"></div>

        <!-- Modal Dialog -->
        <div class="relative bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-100 z-10"
             @click.stop>
            <!-- Header -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-900 tracking-tight">Tambah Pengguna Baru</h3>
                <button type="button"
                        @click="addModalOpen = false"
                        class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.users.store') }}" method="POST" class="mt-6 space-y-4">
                @csrf

                <!-- Nama Lengkap / Nama Perusahaan -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">Nama Lengkap / Nama Perusahaan</label>
                    <input type="text"
                           name="name"
                           required
                           placeholder="Masukkan nama lengkap atau perusahaan"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-blue-700 focus:ring-1 focus:ring-blue-700 outline-none text-slate-900 transition-colors">
                </div>

                <!-- Email Pengguna -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">Email Pengguna</label>
                    <input type="email"
                           name="email"
                           required
                           placeholder="Masukkan alamat email pengguna"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-blue-700 focus:ring-1 focus:ring-blue-700 outline-none text-slate-900 transition-colors">
                </div>

                <!-- Role Pengguna -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">Role Pengguna</label>
                    <select name="role"
                            x-model="newUserRole"
                            required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-blue-700 focus:ring-1 focus:ring-blue-700 outline-none text-slate-900 transition-colors bg-white">
                        <option value="" disabled selected>Pilih Role</option>
                        <option value="kandidat">Kandidat</option>
                        <option value="perusahaan">Perusahaan</option>
                        <option value="admin">Admin</option>
                    </select>
                    <p class="text-[11px] text-slate-400 mt-1">Pilihan: Kandidat / Perusahaan / Admin</p>
                </div>

                <!-- Kata Sandi Sementara with Toggle Visibility (Matches Image 4) -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">Kata Sandi Sementara</label>
                    <div class="relative">
                        <input :type="showTempPassword ? 'text' : 'password'"
                               name="password"
                               placeholder="••••••••"
                               class="w-full px-4 py-2.5 pr-10 rounded-xl border border-slate-200 text-sm focus:border-blue-700 focus:ring-1 focus:ring-blue-700 outline-none text-slate-900 transition-colors">
                        <button type="button"
                                @click="showTempPassword = !showTempPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                            <template x-if="!showTempPassword">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                </svg>
                            </template>
                            <template x-if="showTempPassword">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </template>
                        </button>
                    </div>
                </div>

                <!-- Status Akun: Aktif Toggle Switch (Matches Image 4) -->
                <div class="pt-2 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-800">
                        Status Akun: <span :class="newStatusActive ? 'text-emerald-700' : 'text-red-600'" x-text="newStatusActive ? 'Aktif' : 'Nonaktif'"></span>
                    </span>

                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-slate-500 uppercase" x-text="newStatusActive ? 'ON' : 'OFF'"></span>
                        <input type="hidden" name="status" :value="newStatusActive ? 'active' : 'blocked'">
                        <button type="button"
                                @click="newStatusActive = !newStatusActive"
                                :class="newStatusActive ? 'bg-blue-900' : 'bg-slate-300'"
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                role="switch"
                                :aria-checked="newStatusActive">
                            <span :class="newStatusActive ? 'translate-x-5' : 'translate-x-0'"
                                  class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-sm ring-0 transition duration-200 ease-in-out flex items-center justify-center">
                                <template x-if="newStatusActive">
                                    <svg class="w-3 h-3 text-blue-900" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </template>
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button"
                            @click="addModalOpen = false"
                            class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-blue-900 hover:bg-blue-800 text-white text-xs font-bold transition-colors shadow-md shadow-blue-900/20 cursor-pointer">
                        Simpan & Buat User
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- ══════════════════════════════════════════════════════════════════════
        MODAL: BLOKIR ATAU HAPUS AKUN INI? (Exact Match to Image 2)
    ══════════════════════════════════════════════════════════════════════ -->
    <div x-show="blockOrDeleteModalOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
             @click="blockOrDeleteModalOpen = false"></div>

        <!-- Modal Dialog -->
        <div class="relative bg-white rounded-3xl max-w-sm w-full p-6 sm:p-7 shadow-2xl border border-slate-100 z-10 text-center"
             @click.stop>
            <!-- Hexagon Shield Icon with red outline -->
            <div class="w-12 h-12 rounded-2xl bg-red-50 border border-red-200 text-red-500 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>

            <h3 class="text-base font-bold text-slate-900 mb-2">Blokir atau Hapus Akun Ini?</h3>

            <p class="text-xs text-slate-500 leading-relaxed mb-6">
                Pengguna '<span class="font-semibold text-slate-800" x-text="targetUser.name"></span>' tidak akan dapat masuk ke platform KerjaSetara jika akun diblokir atau dihapus.
            </p>

            <div class="flex items-center justify-center gap-3">
                <button type="button"
                        @click="blockOrDeleteModalOpen = false"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition-colors cursor-pointer">
                    Batal
                </button>
                <form :action="targetUser.block_url" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-xs font-bold transition-colors shadow-sm cursor-pointer">
                        Ya, Blokir Akun
                    </button>
                </form>
            </div>

            <!-- Optional permanent deletion action link -->
            <div class="mt-4 pt-3 border-t border-slate-100">
                <form :action="targetUser.delete_url" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini secara permanen?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-[11px] text-red-500 hover:text-red-700 hover:underline font-medium">
                        Atau hapus akun ini secara permanen
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

<!-- ══════════════════════════════════════════════════════════════════════
    ALPINE.JS COMPONENT SCRIPT
══════════════════════════════════════════════════════════════════════ -->
<script>
function userManagement() {
    return {
        // Modal States
        editModalOpen: false,
        addModalOpen: false,
        blockOrDeleteModalOpen: false,

        // Add Modal State
        newUserRole: '',
        showTempPassword: false,
        newStatusActive: true,

        // Edit Modal State
        editUser: {
            id: '',
            name: '',
            email: '',
            role: 'kandidat',
            status: 'active',
            is_active: true,
            identifier: '',
            is_self: false,
        },

        // Target User for Block/Delete
        targetUser: {
            id: '',
            name: '',
            email: '',
            role_label: '',
            is_blocked: false,
            block_url: '',
            delete_url: '',
        },

        // Handlers
        openEditModal(user) {
            this.editUser = { ...user };
            this.editModalOpen = true;
        },
        openAddModal() {
            this.newUserRole = '';
            this.showTempPassword = false;
            this.newStatusActive = true;
            this.addModalOpen = true;
        },
        openBlockOrDeleteModal(user) {
            this.targetUser = { ...user };
            this.blockOrDeleteModalOpen = true;
        }
    };
}
</script>
@endsection
