@extends('admin.layouts.app')

@section('title', 'Moderasi Lowongan Kerja')
@section('page_title', 'Moderasi & Verifikasi Lowongan')

@section('content')
<div class="space-y-6">

    <!-- Filters & Search Toolbar -->
    <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex flex-col lg:flex-row lg:items-center justify-between gap-4">

        <!-- Status Filter Tabs -->
        <div class="flex items-center gap-1 p-1 rounded-2xl bg-slate-100 border border-slate-200 overflow-x-auto">
            <a href="{{ route('admin.lowongan', ['status' => 'all', 'category' => $categoryFilter, 'search' => $search]) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ $statusFilter === 'all' ? 'bg-white text-blue-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Semua Lowongan ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.lowongan', ['status' => 'active', 'category' => $categoryFilter, 'search' => $search]) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ $statusFilter === 'active' ? 'bg-white text-blue-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Aktif / Terverifikasi ({{ $counts['active'] }})
            </a>
            <a href="{{ route('admin.lowongan', ['status' => 'pending', 'category' => $categoryFilter, 'search' => $search]) }}"
               class="px-3.5 py-1.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all {{ $statusFilter === 'pending' ? 'bg-white text-blue-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                Menunggu Moderasi ({{ $counts['pending'] }})
            </a>
        </div>

        <!-- Category & Search Form -->
        <form method="GET" action="{{ route('admin.lowongan') }}" class="flex flex-wrap items-center gap-2">
            <input type="hidden" name="status" value="{{ $statusFilter }}">

            <!-- Category Dropdown -->
            <select name="category" onchange="this.form.submit()" class="bg-slate-50 border border-slate-200 text-xs rounded-xl px-3 py-2 text-slate-700 outline-none">
                <option value="all">Semua Kategori</option>
                <option value="fisik" {{ $categoryFilter === 'fisik' ? 'selected' : '' }}>♿ Mobilitas Fisik</option>
                <option value="komunikasi" {{ $categoryFilter === 'komunikasi' ? 'selected' : '' }}>🗣️ Komunikasi Inklusif</option>
                <option value="digital" {{ $categoryFilter === 'digital' ? 'selected' : '' }}>💻 Dukungan Digital</option>
                <option value="neurodiverse" {{ $categoryFilter === 'neurodiverse' ? 'selected' : '' }}>🧠 Neurodiverse</option>
            </select>

            <!-- Search Field -->
            <div class="relative w-full sm:w-56">
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari lowongan / PT..."
                       class="w-full bg-slate-50 border border-slate-200 focus:border-blue-600 focus:bg-white text-slate-900 text-xs rounded-xl pl-3 pr-3 py-2 outline-none transition-colors">
            </div>

            <button type="submit" class="px-3.5 py-2 bg-blue-900 text-white rounded-xl text-xs font-semibold hover:bg-blue-800 transition-colors">
                Filter
            </button>
            @if($categoryFilter !== 'all' || !empty($search))
                <a href="{{ route('admin.lowongan', ['status' => $statusFilter]) }}" class="text-xs text-slate-500 hover:text-slate-700 underline">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Vacancies Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-slate-500 font-bold uppercase text-[10px] tracking-wider">
                        <th class="py-3.5 px-6">Informasi Lowongan</th>
                        <th class="py-3.5 px-4">Gaji & Tipe</th>
                        <th class="py-3.5 px-4">Fasilitas Aksesibilitas Terdaftar</th>
                        <th class="py-3.5 px-4">Sertifikasi Inklusif</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-6 text-right">Moderasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($vacancies as $vacancy)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Job & Company -->
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900 text-sm">{{ $vacancy->title }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-1.5">
                                    <span class="font-medium text-slate-700">{{ $vacancy->company->company_name ?? 'Perusahaan' }}</span>
                                    <span>•</span>
                                    <span>{{ $vacancy->location }}</span>
                                </div>
                            </td>

                            <!-- Salary & Type -->
                            <td class="py-4 px-4">
                                <div class="font-semibold text-slate-900">{{ $vacancy->formatted_salary }}</div>
                                <div class="text-[10px] text-slate-500 uppercase mt-0.5">{{ $vacancy->job_type_label }}</div>
                            </td>

                            <!-- Tags -->
                            <td class="py-4 px-4 max-w-xs">
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($vacancy->accessibility_tags ?? [], 0, 3) as $tag)
                                        <span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-800 text-[10px] font-semibold">
                                            {{ str_replace('_', ' ', $tag) }}
                                        </span>
                                    @endforeach
                                    @if(count($vacancy->accessibility_tags ?? []) > 3)
                                        <span class="text-[10px] text-slate-400 self-center">+{{ count($vacancy->accessibility_tags) - 3 }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Company Certification -->
                            <td class="py-4 px-4">
                                @if($vacancy->company)
                                    <form method="POST" action="{{ route('admin.companies.toggle-certified', $vacancy->company->id) }}">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold transition-all cursor-pointer {{ $vacancy->company->is_inclusive_certified ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border border-slate-200 hover:bg-slate-200' }}"
                                                title="Klik untuk ubah status verifikasi perusahaan">
                                            @if($vacancy->company->is_inclusive_certified)
                                                ✓ Terverifikasi Inklusif
                                            @else
                                                Belum Sertifikasi
                                            @endif
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>

                            <!-- Active Status -->
                            <td class="py-4 px-4">
                                @if($vacancy->is_active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Disetujui
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Ditangguhkan
                                    </span>
                                @endif
                            </td>

                            <!-- Action -->
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.lowongan.toggle', $vacancy->id) }}" class="inline">
                                    @csrf
                                    @if($vacancy->is_active)
                                        <button type="submit"
                                                class="px-3 py-1.5 rounded-xl bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200 text-xs font-semibold transition-colors cursor-pointer">
                                            Tangguhkan
                                        </button>
                                    @else
                                        <button type="submit"
                                                class="px-3 py-1.5 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 shadow-xs text-xs font-semibold transition-colors cursor-pointer">
                                            Setujui Lowongan
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-slate-400 text-sm">
                                Tidak ada lowongan pekerjaan ditemukan pada filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($vacancies->hasPages())
            <div class="p-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500">
                    Menampilkan {{ $vacancies->firstItem() }} - {{ $vacancies->lastItem() }} dari {{ $vacancies->total() }} lowongan
                </span>
                <div>
                    {{ $vacancies->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
