@extends('admin.layouts.app')

@section('title', 'Manajemen Pengguna')
@section('page_title', 'Manajemen Pengguna Platform')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Filter Tabs -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Role Tabs -->
        <div class="flex items-center gap-1 p-1 rounded-2xl bg-slate-100 border border-slate-200 overflow-x-auto">
            <a href="{{ route('admin.users', ['role' => 'all', 'search' => $search]) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ $roleFilter === 'all' ? 'bg-white text-blue-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Semua ({{ $roleCounts['all'] }})
            </a>
            <a href="{{ route('admin.users', ['role' => 'kandidat', 'search' => $search]) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ $roleFilter === 'kandidat' ? 'bg-white text-blue-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Talenta / User ({{ $roleCounts['kandidat'] }})
            </a>
            <a href="{{ route('admin.users', ['role' => 'hrd', 'search' => $search]) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ $roleFilter === 'hrd' ? 'bg-white text-blue-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Perusahaan / HRD ({{ $roleCounts['hrd'] }})
            </a>
            <a href="{{ route('admin.users', ['role' => 'admin', 'search' => $search]) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ $roleFilter === 'admin' ? 'bg-white text-blue-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Admin ({{ $roleCounts['admin'] }})
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('admin.users') }}" class="flex items-center gap-2">
            <input type="hidden" name="role" value="{{ $roleFilter }}">
            <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari nama atau email..."
                       class="w-full bg-slate-50 border border-slate-200 focus:border-blue-600 focus:bg-white text-slate-900 text-xs rounded-xl pl-9 pr-3 py-2 outline-none transition-colors">
            </div>
            <button type="submit" class="px-3.5 py-2 bg-blue-900 text-white rounded-xl text-xs font-semibold hover:bg-blue-800 transition-colors">
                Cari
            </button>
            @if(!empty($search))
                <a href="{{ route('admin.users', ['role' => $roleFilter]) }}" class="text-xs text-slate-500 hover:text-slate-700 underline">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Users Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 font-bold uppercase text-[10px] tracking-wider">
                        <th class="py-3.5 px-6">Pengguna</th>
                        <th class="py-3.5 px-4">Peran (Role)</th>
                        <th class="py-3.5 px-4">Kontak & Lokasi</th>
                        <th class="py-3.5 px-4">Aksesibilitas Utama</th>
                        <th class="py-3.5 px-4">Terdaftar</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Name & Avatar -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-blue-700 to-blue-900 text-white font-bold flex items-center justify-center text-xs shadow-xs flex-shrink-0">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900 text-sm truncate">{{ $user->name }}</div>
                                        <div class="text-[11px] text-slate-500 truncate">{{ $user->email }}</div>
                                        @if($user->headline)
                                            <div class="text-[10px] text-blue-700 truncate font-medium">{{ $user->headline }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Role Badge -->
                            <td class="py-4 px-4">
                                @if($user->isAdmin())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                        🛡️ Administrator
                                    </span>
                                @elseif($user->isHrd())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                        🏢 HRD / Perusahaan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        🎯 Pencari Kerja
                                    </span>
                                @endif
                            </td>

                            <!-- Contact & City -->
                            <td class="py-4 px-4 text-slate-600">
                                <div>{{ $user->phone ?? '—' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $user->city ?? 'Indonesia' }}</div>
                            </td>

                            <!-- Accessibility Preference -->
                            <td class="py-4 px-4">
                                @if($user->accessibility_category)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-blue-50 text-blue-800 border border-blue-200/80 capitalize">
                                        @if($user->accessibility_category === 'fisik') ♿ Fisik
                                        @elseif($user->accessibility_category === 'komunikasi') 🗣️ Komunikasi
                                        @elseif($user->accessibility_category === 'digital') 💻 Digital
                                        @elseif($user->accessibility_category === 'neurodiverse') 🧠 Neurodiverse
                                        @else {{ $user->accessibility_category }}
                                        @endif
                                    </span>
                                @else
                                    <span class="text-slate-400 text-xs">—</span>
                                @endif
                            </td>

                            <!-- Created At -->
                            <td class="py-4 px-4 text-slate-500 text-[11px]">
                                {{ $user->created_at->format('d M Y') }}
                            </td>

                            <!-- Action -->
                            <td class="py-4 px-6 text-right">
                                @if(auth()->id() !== $user->id)
                                    <form method="POST"
                                          action="{{ route('admin.users.destroy', $user->id) }}"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna {{ $user->name }}? Tindakan ini permanen.')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-2.5 py-1 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg text-xs font-semibold transition-colors cursor-pointer border border-transparent hover:border-red-200">
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[10px] text-slate-400 italic">Akun Anda</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-slate-400 text-sm">
                                Tidak ada data pengguna yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="p-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500">
                    Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} pengguna
                </span>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
