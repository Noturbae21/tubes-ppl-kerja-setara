@extends('layouts.app')

@section('title', 'Dashboard HRD — KerjaSetara')

@section('content')
<div class="py-10 bg-slate-50 min-h-[calc(100vh-4rem)]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm mb-6 flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-100 text-blue-800">Portal Perusahaan / HRD</span>
                <h1 class="text-2xl font-bold text-slate-900 mt-2">Selamat Datang, {{ $user->name }}!</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola publikasi lowongan kerja inklusif dan pemrosesan lamaran kandidat.</p>
            </div>
            <form method="POST" action="{{ route('hrd.logout') }}">
                @csrf
                <button type="submit" class="px-4 py-2 border border-slate-300 text-xs font-semibold rounded-xl text-slate-700 hover:bg-slate-50">
                    Keluar Sesi HRD
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <span class="text-xs text-slate-400 font-medium">Lowongan Aktif</span>
                <div class="text-3xl font-black text-blue-900 mt-2">12</div>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <span class="text-xs text-slate-400 font-medium">Kandidat Melamar</span>
                <div class="text-3xl font-black text-emerald-600 mt-2">38</div>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                <span class="text-xs text-slate-400 font-medium">Status Verifikasi</span>
                <div class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full w-fit mt-3">
                    ✓ Perusahaan Terverifikasi Inklusif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
