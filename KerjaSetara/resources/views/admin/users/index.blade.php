@extends('admin.layouts.app')

@section('title', 'Manajemen Pengguna Platform')
@section('page_title', 'Manajemen Pengguna Platform')

@section('content')
<div x-data="userManagement()" class="space-y-6">

    <!-- ══════════════════════════════════════════════════════════════════════
        PAGE BREADCRUMB & HEADER
    ══════════════════════════════════════════════════════════════════════ -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-medium text-slate-400 mb-1">
                <span>Admin</span>
                <span>/</span>
                <span class="text-slate-600 font-semibold">Kelola User</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Manajemen Pengguna Platform</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola akun seluruh pengguna, kelola kredensial akses, dan pantau status akun.</p>
        </div>

        <!-- Add User Button -->
        <button type="button"
                @click="openAddModal()"
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-blue-900 hover:bg-blue-800 text-white text-xs font-bold transition-all shadow-md shadow-blue-950/20 cursor-pointer self-start md:self-auto">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <span>+ Tambah User Baru</span>
        </button>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════
        SEARCH & FILTER CONTROLS
    ══════════════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs space-y-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.users') }}" class="flex items-center gap-2 flex-1 max-w-md">
                <input type="hidden" name="role" value="{{ $roleFilter }}">
                <input type="hidden" name="status" value="{{ $statusFilter }}">
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle cx="11" cy="11" r="8" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35"/>
                        </svg>
                    </span>
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari nama, email, role..."
                           class="w-full bg-slate-50 border border-slate-200 focus:border-blue-700 focus:bg-white text-slate-900 text-xs rounded-xl pl-9 pr-3 py-2.5 outline-none transition-colors">
                </div>
                <button type="submit" class="px-4 py-2.5 bg-blue-900 text-white rounded-xl text-xs font-semibold hover:bg-blue-800 transition-colors cursor-pointer">
                    Cari
                </button>
                @if(!empty($search))
                    <a href="{{ route('admin.users', ['role' => $roleFilter, 'status' => $statusFilter]) }}"
                       class="text-xs text-slate-500 hover:text-slate-800 underline px-1">
                        Reset
                    </a>
                @endif
            </form>

            <!-- Status & Role Filter Badges -->
            <div class="flex flex-wrap items-center gap-2">
                <!-- Status Filter Dropdown -->
                <div class="flex items-center gap-1.5 p-1 rounded-xl bg-slate-100 border border-slate-200 text-xs">
                    <a href="{{ route('admin.users', ['role' => $roleFilter, 'status' => 'all', 'search' => $search]) }}"
                       class="px-3 py-1 rounded-lg font-semibold transition-all {{ $statusFilter === 'all' ? 'bg-white text-blue-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Semua Status ({{ $statusCounts['all'] }})
                    </a>
                    <a href="{{ route('admin.users', ['role' => $roleFilter, 'status' => 'active', 'search' => $search]) }}"
                       class="px-3 py-1 rounded-lg font-semibold transition-all {{ $statusFilter === 'active' ? 'bg-white text-emerald-800 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1"></span>Aktif ({{ $statusCounts['active'] }})
                    </a>
                    <a href="{{ route('admin.users', ['role' => $roleFilter, 'status' => 'blocked', 'search' => $search]) }}"
                       class="px-3 py-1 rounded-lg font-semibold transition-all {{ $statusFilter === 'blocked' ? 'bg-white text-red-700 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        <span class="inline-block w-2 h-2 rounded-full bg-red-500 mr-1"></span>Diblokir ({{ $statusCounts['blocked'] }})
                    </a>
                </div>
            </div>
        </div>

        <!-- Role Filter Tabs (Sub-filter) -->
        <div class="flex items-center gap-1.5 pt-2 border-t border-slate-100 overflow-x-auto text-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mr-2">Filter Peran:</span>
            <a href="{{ route('admin.users', ['role' => 'all', 'status' => $statusFilter, 'search' => $search]) }}"
               class="px-3 py-1 rounded-lg font-medium transition-all {{ $roleFilter === 'all' ? 'bg-blue-50 text-blue-900 font-bold border border-blue-200' : 'text-slate-600 hover:bg-slate-50' }}">
                Semua Peran ({{ $roleCounts['all'] }})
            </a>
            <a href="{{ route('admin.users', ['role' => 'kandidat', 'status' => $statusFilter, 'search' => $search]) }}"
               class="px-3 py-1 rounded-lg font-medium transition-all {{ $roleFilter === 'kandidat' ? 'bg-blue-50 text-blue-900 font-bold border border-blue-200' : 'text-slate-600 hover:bg-slate-50' }}">
                Talenta / User ({{ $roleCounts['kandidat'] }})
            </a>
            <a href="{{ route('admin.users', ['role' => 'hrd', 'status' => $statusFilter, 'search' => $search]) }}"
               class="px-3 py-1 rounded-lg font-medium transition-all {{ $roleFilter === 'hrd' ? 'bg-blue-50 text-blue-900 font-bold border border-blue-200' : 'text-slate-600 hover:bg-slate-50' }}">
                Perusahaan / HRD ({{ $roleCounts['hrd'] }})
            </a>
            <a href="{{ route('admin.users', ['role' => 'admin', 'status' => $statusFilter, 'search' => $search]) }}"
               class="px-3 py-1 rounded-lg font-medium transition-all {{ $roleFilter === 'admin' ? 'bg-blue-50 text-blue-900 font-bold border border-blue-200' : 'text-slate-600 hover:bg-slate-50' }}">
                Administrator ({{ $roleCounts['admin'] }})
            </a>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════════════
        USERS DATA TABLE
    ══════════════════════════════════════════════════════════════════════ -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200/80 text-slate-500 font-bold uppercase text-[10px] tracking-wider">
                        <th class="py-3.5 px-4 w-12 text-center">
                            <input type="checkbox" class="rounded text-blue-900 focus:ring-0 cursor-pointer">
                        </th>
                        <th class="py-3.5 px-4">Nama Pengguna</th>
                        <th class="py-3.5 px-4">Email</th>
                        <th class="py-3.5 px-4">Role</th>
                        <th class="py-3.5 px-4">Tanggal Gabung</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-6 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/70 transition-colors {{ $user->isBlocked() ? 'bg-red-50/20' : '' }}">
                            <!-- Checkbox -->
                            <td class="py-4 px-4 text-center">
                                <input type="checkbox" class="rounded text-blue-900 focus:ring-0 cursor-pointer">
                            </td>

                            <!-- Nama & Identifier -->
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full {{ $user->isAdmin() ? 'bg-purple-700' : ($user->isHrd() ? 'bg-blue-800' : 'bg-emerald-700') }} text-white font-bold flex items-center justify-center text-xs shadow-xs flex-shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900 text-sm truncate flex items-center gap-1.5">
                                            <span>{{ $user->name }}</span>
                                            @if($user->id === auth()->id())
                                                <span class="text-[9px] px-1.5 py-0.2 rounded bg-blue-100 text-blue-800 font-semibold">Anda</span>
                                            @endif
                                            @if($user->must_change_password)
                                                <span class="text-[9px] px-1.5 py-0.2 rounded bg-amber-100 text-amber-800 font-medium" title="Kata sandi sementara belum diganti">Sandi Sementara</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] font-mono text-slate-400 mt-0.5">
                                            {{ $user->identifier }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="py-4 px-4 text-slate-600 font-medium">
                                {{ $user->email }}
                            </td>

                            <!-- Role Badge -->
                            <td class="py-4 px-4">
                                @if($user->isAdmin())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                                        Administrator
                                    </span>
                                @elseif($user->isHrd())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                        Perusahaan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        Kandidat
                                    </span>
                                @endif
                            </td>

                            <!-- Tanggal Gabung -->
                            <td class="py-4 px-4 text-slate-500 text-[11px]">
                                {{ $user->created_at->format('d M Y') }}
                            </td>

                            <!-- Status Akun -->
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

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <!-- Detail Button -->
                                    <button type="button"
                                            @click="openDetailModal({{ $user->id }})"
                                            title="Lihat Detail Pengguna"
                                            class="p-1.5 text-slate-400 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>

                                    <!-- Edit Button -->
                                    <button type="button"
                                            @click="openEditModal({{ json_encode([
                                                'id' => $user->id,
                                                'name' => $user->name,
                                                'email' => $user->email,
                                                'role' => $user->role === 'hrd' ? 'perusahaan' : $user->role,
                                                'status' => $user->status ?? 'active',
                                                'is_active' => !$user->isBlocked(),
                                                'identifier' => $user->identifier,
                                                'is_self' => $user->id === auth()->id(),
                                            ]) }})"
                                            title="Edit Informasi Pengguna"
                                            class="p-1.5 text-slate-500 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </button>

                                    <!-- Block / Activate Button -->
                                    @if(auth()->id() !== $user->id)
                                        <button type="button"
                                                @click="openBlockModal({{ json_encode([
                                                    'id' => $user->id,
                                                    'name' => $user->name,
                                                    'email' => $user->email,
                                                    'is_blocked' => $user->isBlocked(),
                                                    'toggle_url' => route('admin.users.toggle-status', $user->id),
                                                ]) }})"
                                                title="{{ $user->isBlocked() ? 'Aktifkan Pengguna' : 'Blokir Pengguna' }}"
                                                class="p-1.5 {{ $user->isBlocked() ? 'text-emerald-600 hover:bg-emerald-50' : 'text-amber-600 hover:bg-amber-50' }} rounded-lg transition-colors cursor-pointer">
                                            @if($user->isBlocked())
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                </svg>
                                            @endif
                                        </button>
                                    @endif

                                    <!-- Delete Button -->
                                    @if(auth()->id() !== $user->id)
                                        <button type="button"
                                                @click="openDeleteModal({{ json_encode([
                                                    'id' => $user->id,
                                                    'name' => $user->name,
                                                    'email' => $user->email,
                                                    'role_label' => $user->role_label,
                                                    'delete_url' => route('admin.users.destroy', $user->id),
                                                ]) }})"
                                                title="Hapus Akun Pengguna"
                                                class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                    <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                    </div>
                                    <div class="font-bold text-slate-700">Tidak ada data pengguna</div>
                                    <p class="text-xs text-slate-500">Tidak ada akun yang sesuai dengan pencarian atau filter yang dipilih.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination & Summary Footer -->
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
        MODAL 1: EDIT INFORMASI PENGGUNA (Matches Exact Screenshot)
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
            <!-- Modal Header -->
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

            <!-- Form -->
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
        MODAL 2: TAMBAH USER BARU
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
        <div class="relative bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 z-10"
             @click.stop>
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 tracking-tight">Tambah User Baru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Daftarkan akun pengguna baru ke ekosistem KerjaSetara.</p>
                </div>
                <button type="button"
                        @click="addModalOpen = false"
                        class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="mt-6 space-y-4">
                @csrf

                <!-- Nama Lengkap / Nama Perusahaan -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">Nama Lengkap / Nama Perusahaan *</label>
                    <input type="text"
                           name="name"
                           required
                           placeholder="Masukkan nama lengkap atau nama perusahaan"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-blue-700 focus:ring-1 focus:ring-blue-700 outline-none text-slate-900 transition-colors">
                </div>

                <!-- Email Pengguna -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">Email Pengguna *</label>
                    <input type="email"
                           name="email"
                           required
                           placeholder="user@example.com"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-blue-700 focus:ring-1 focus:ring-blue-700 outline-none text-slate-900 transition-colors">
                </div>

                <!-- Role Pengguna -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">Role Pengguna *</label>
                    <select name="role"
                            x-model="newUserRole"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-blue-700 focus:ring-1 focus:ring-blue-700 outline-none text-slate-900 transition-colors bg-white">
                        <option value="kandidat">Kandidat</option>
                        <option value="perusahaan">Perusahaan</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>

                <!-- Accessibility Category (Optional if role is Kandidat) -->
                <div x-show="newUserRole === 'kandidat'" class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-800">Kategori Aksesibilitas Utama</label>
                    <select name="accessibility_category"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-blue-700 focus:ring-1 focus:ring-blue-700 outline-none text-slate-900 transition-colors bg-white">
                        <option value="fisik">♿ Fisik & Mobilitas</option>
                        <option value="komunikasi">🗣️ Komunikasi & Sensorik</option>
                        <option value="digital">💻 Digital & Screen Reader</option>
                        <option value="neurodiverse">🧠 Neurodiverse & Sensori</option>
                    </select>
                </div>

                <!-- Kata Sandi / Temporary Password -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">Kata Sandi (Opsional)</label>
                    <input type="password"
                           name="password"
                           placeholder="Kosongkan untuk otomatis membuat kata sandi sementara"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-blue-700 focus:ring-1 focus:ring-blue-700 outline-none text-slate-900 transition-colors">
                    <p class="text-[10px] text-slate-400 mt-1">Jika dikosongkan, sistem membuat kata sandi acak aman dan menyimpannya secara terenkripsi (hash).</p>
                </div>

                <!-- Must Change Password Checkbox -->
                <div class="flex items-start gap-2.5 pt-1">
                    <input type="checkbox"
                           id="must_change_password"
                           name="must_change_password"
                           value="1"
                           checked
                           class="mt-0.5 rounded text-blue-900 focus:ring-0 cursor-pointer">
                    <label for="must_change_password" class="text-xs text-slate-700 cursor-pointer">
                        <span class="font-semibold text-slate-900">Wajibkan penggantian kata sandi saat login pertama</span>
                        <span class="block text-[11px] text-slate-500">Pengguna akan diarahkan ke formulir perubahan sandi baru sebelum dapat mengakses sistem.</span>
                    </label>
                </div>

                <!-- Status Akun -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1.5">Status Akun Awal</label>
                    <select name="status"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-blue-700 focus:ring-1 focus:ring-blue-700 outline-none text-slate-900 transition-colors bg-white">
                        <option value="active">Aktif</option>
                        <option value="blocked">Diblokir / Nonaktif</option>
                    </select>
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
                        Tambah Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- ══════════════════════════════════════════════════════════════════════
        MODAL 3: KONFIRMASI BLOKIR / NONAKTIFKAN / AKTIFKAN
    ══════════════════════════════════════════════════════════════════════ -->
    <div x-show="blockModalOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
             @click="blockModalOpen = false"></div>

        <div class="relative bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-100 z-10"
             @click.stop>
            <div class="text-center space-y-3">
                <div :class="blockUser.is_blocked ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600'"
                     class="w-12 h-12 rounded-2xl flex items-center justify-center mx-auto">
                    <template x-if="blockUser.is_blocked">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </template>
                    <template x-if="!blockUser.is_blocked">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </template>
                </div>

                <h3 class="text-lg font-bold text-slate-900" x-text="blockUser.is_blocked ? 'Aktifkan Kembali Pengguna?' : 'Konfirmasi Blokir Akun Pengguna'"></h3>

                <p class="text-xs text-slate-500 leading-relaxed">
                    <template x-if="!blockUser.is_blocked">
                        <span>
                            Apakah Anda yakin ingin memblokir akses pengguna <strong class="text-slate-800" x-text="blockUser.name"></strong> (<span x-text="blockUser.email"></span>)? Pengguna tidak akan dapat masuk ke sistem KerjaSetara hingga diaktifkan kembali.
                        </span>
                    </template>
                    <template x-if="blockUser.is_blocked">
                        <span>
                            Apakah Anda yakin ingin mengaktifkan kembali akun <strong class="text-slate-800" x-text="blockUser.name"></strong>? Pengguna akan dapat login kembali secara normal.
                        </span>
                    </template>
                </p>
            </div>

            <form :action="blockUser.toggle_url" method="POST" class="mt-6 flex items-center justify-center gap-3">
                @csrf
                <button type="button"
                        @click="blockModalOpen = false"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                        :class="blockUser.is_blocked ? 'bg-emerald-700 hover:bg-emerald-800' : 'bg-red-600 hover:bg-red-700'"
                        class="px-5 py-2.5 rounded-xl text-white text-xs font-bold transition-colors shadow-md cursor-pointer"
                        x-text="blockUser.is_blocked ? 'Ya, Aktifkan Akun' : 'Ya, Blokir Pengguna'">
                </button>
            </form>
        </div>
    </div>


    <!-- ══════════════════════════════════════════════════════════════════════
        MODAL 4: KONFIRMASI HAPUS AKUN (Separated Confirmation Dialog)
    ══════════════════════════════════════════════════════════════════════ -->
    <div x-show="deleteModalOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
             @click="deleteModalOpen = false"></div>

        <div class="relative bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-slate-100 z-10"
             @click.stop>
            <div class="text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>

                <h3 class="text-lg font-bold text-slate-900">Hapus Akun Pengguna?</h3>

                <p class="text-xs text-slate-500 leading-relaxed">
                    Tindakan ini permanen dan tidak dapat dibatalkan. Menghapus akun <strong class="text-slate-800" x-text="deleteUser.name"></strong> (<span x-text="deleteUser.role_label"></span>) akan membersihkan seluruh data relasi terkait (lamaran, lowongan, preferensi) secara aman sesuai kebijakan integritas sistem.
                </p>
            </div>

            <form :action="deleteUser.delete_url" method="POST" class="mt-6 flex items-center justify-center gap-3">
                @csrf
                @method('DELETE')
                <button type="button"
                        @click="deleteModalOpen = false"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition-colors shadow-md shadow-red-600/20 cursor-pointer">
                    Ya, Hapus Akun
                </button>
            </form>
        </div>
    </div>


    <!-- ══════════════════════════════════════════════════════════════════════
        MODAL 5: LIHAT DETAIL PENGGUNA
    ══════════════════════════════════════════════════════════════════════ -->
    <div x-show="detailModalOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
             @click="detailModalOpen = false"></div>

        <div class="relative bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 z-10"
             @click.stop>
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 tracking-tight">Detail Profil Pengguna</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Informasi akun pengguna dan metadata sistem.</p>
                </div>
                <button type="button"
                        @click="detailModalOpen = false"
                        class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="mt-6 space-y-4">
                <div class="flex items-center gap-3.5 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="w-12 h-12 rounded-2xl bg-blue-900 text-white font-bold flex items-center justify-center text-sm shadow-xs">
                        <span x-text="detailUser.name ? detailUser.name.substring(0, 2).toUpperCase() : 'US'"></span>
                    </div>
                    <div>
                        <div class="font-bold text-slate-900 text-sm" x-text="detailUser.name"></div>
                        <div class="text-xs text-slate-500" x-text="detailUser.email"></div>
                        <div class="text-[11px] font-mono text-blue-800 font-semibold mt-0.5" x-text="detailUser.identifier"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="text-[10px] text-slate-400 font-bold uppercase">Role Akun</div>
                        <div class="font-semibold text-slate-800 mt-0.5" x-text="detailUser.role_label"></div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="text-[10px] text-slate-400 font-bold uppercase">Status Akun</div>
                        <div class="font-semibold mt-0.5"
                             :class="detailUser.status === 'blocked' ? 'text-red-600' : 'text-emerald-700'"
                             x-text="detailUser.status_label"></div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="text-[10px] text-slate-400 font-bold uppercase">Tanggal Registrasi</div>
                        <div class="font-semibold text-slate-800 mt-0.5" x-text="detailUser.created_at"></div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="text-[10px] text-slate-400 font-bold uppercase">Kata Sandi Sementara</div>
                        <div class="font-semibold mt-0.5"
                             :class="detailUser.must_change_password ? 'text-amber-600' : 'text-slate-600'"
                             x-text="detailUser.must_change_password ? 'Wajib Diganti' : 'Permanen (Aman)'"></div>
                    </div>
                </div>

                <template x-if="detailUser.company_name">
                    <div class="p-3.5 rounded-xl bg-blue-50/60 border border-blue-100 text-xs">
                        <div class="text-[10px] font-bold text-blue-900 uppercase">Perusahaan Terafiliasi</div>
                        <div class="font-bold text-slate-900 mt-0.5" x-text="detailUser.company_name"></div>
                    </div>
                </template>

                <template x-if="detailUser.accessibility_category">
                    <div class="p-3.5 rounded-xl bg-emerald-50/60 border border-emerald-100 text-xs">
                        <div class="text-[10px] font-bold text-emerald-900 uppercase">Kategori Aksesibilitas Kandidat</div>
                        <div class="font-bold text-slate-900 mt-0.5 capitalize" x-text="detailUser.accessibility_category"></div>
                    </div>
                </template>
            </div>

            <div class="pt-6 mt-6 border-t border-slate-100 flex justify-end">
                <button type="button"
                        @click="detailModalOpen = false"
                        class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-colors cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>

<!-- ══════════════════════════════════════════════════════════════════════
    ALPINE.JS DATA COMPONENT SCRIPT
══════════════════════════════════════════════════════════════════════ -->
<script>
function userManagement() {
    return {
        // Modal States
        editModalOpen: false,
        addModalOpen: false,
        blockModalOpen: false,
        deleteModalOpen: false,
        detailModalOpen: false,

        // Data Objects
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
        newUserRole: 'kandidat',
        blockUser: {
            id: '',
            name: '',
            email: '',
            is_blocked: false,
            toggle_url: '',
        },
        deleteUser: {
            id: '',
            name: '',
            email: '',
            role_label: '',
            delete_url: '',
        },
        detailUser: {},

        // Handlers
        openEditModal(user) {
            this.editUser = { ...user };
            this.editModalOpen = true;
        },
        openAddModal() {
            this.newUserRole = 'kandidat';
            this.addModalOpen = true;
        },
        openBlockModal(user) {
            this.blockUser = { ...user };
            this.blockModalOpen = true;
        },
        openDeleteModal(user) {
            this.deleteUser = { ...user };
            this.deleteModalOpen = true;
        },
        openDetailModal(userId) {
            fetch('{{ url('admin/users') }}/' + userId)
                .then(res => res.json())
                .then(data => {
                    this.detailUser = data;
                    this.detailModalOpen = true;
                })
                .catch(err => {
                    console.error('Gagal mengambil data detail:', err);
                });
        }
    };
}
</script>
@endsection
