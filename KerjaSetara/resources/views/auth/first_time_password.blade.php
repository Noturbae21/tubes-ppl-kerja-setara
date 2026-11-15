<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Kata Sandi Pertama — KerjaSetara</title>
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased bg-slate-50 flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-3xl p-8 shadow-xl border border-slate-200">
        <!-- Logo -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-blue-900 text-white shadow-lg mb-3">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h1 class="text-xl font-bold text-slate-900">Atur Kata Sandi Baru</h1>
            <p class="text-xs text-slate-500 mt-1">
                Halo, <strong>{{ $user->name }}</strong>. Akun Anda dibuat dengan kata sandi sementara. Demi keamanan, silakan tentukan kata sandi pribadi baru Anda.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-5 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700 text-xs">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.first_time.update') }}" class="space-y-4">
            @csrf

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Kata Sandi Baru</label>
                <input type="password" id="password" name="password" required minlength="8"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-blue-700 focus:ring-1 focus:ring-blue-700 outline-none"
                       placeholder="Minimal 8 karakter">
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:border-blue-700 focus:ring-1 focus:ring-blue-700 outline-none"
                       placeholder="Ulangi kata sandi baru">
            </div>

            <button type="submit"
                    class="w-full py-3 px-4 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded-xl text-xs transition-colors shadow-md shadow-blue-900/20 cursor-pointer">
                Simpan Kata Sandi & Lanjutkan
            </button>
        </form>
    </div>

</body>
</html>
