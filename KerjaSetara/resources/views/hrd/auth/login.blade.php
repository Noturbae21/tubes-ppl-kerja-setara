<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Perusahaan / HRD — KerjaSetara</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full antialiased font-sans flex flex-col justify-between">

    <header class="py-6 px-6 lg:px-12 flex items-center justify-between">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-blue-900 text-xs font-semibold">
            ← Kembali ke Beranda
        </a>
        <span class="text-xs text-slate-500 font-medium">Portal Mitra Perusahaan Inklusif</span>
    </header>

    <main class="flex-1 flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-3xl p-8 shadow-xl border border-slate-100">
            <div class="text-center mb-6">
                <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl">
                    🏢
                </div>
                <h1 class="text-2xl font-bold text-slate-900">Portal HRD & Perusahaan</h1>
                <p class="text-xs text-slate-500 mt-1">Kelola lowongan inklusif dan temukan talenta terbaik</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-3 rounded-xl bg-red-50 text-red-700 text-xs">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('hrd.login.submit') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Email Perusahaan</label>
                    <input type="email" name="email" required value="{{ old('email', 'hrd@example.com') }}" class="input-field">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Kata Sandi</label>
                    <input type="password" name="password" required class="input-field" placeholder="••••••••">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="rem" class="rounded border-slate-300 text-blue-900">
                    <label for="rem" class="ml-2 text-xs text-slate-600">Ingat saya</label>
                </div>
                <button type="submit" class="w-full btn-primary py-2.5 justify-center text-sm font-semibold">
                    Masuk ke Portal Perusahaan
                </button>
            </form>

            <div class="mt-6 pt-4 border-t border-slate-100 text-center text-xs text-slate-500">
                Akun demo HRD: <span class="font-mono text-blue-900 font-semibold">hrd@example.com</span> / sandi: <span class="font-mono text-blue-900 font-semibold">password</span>
            </div>
        </div>
    </main>

    <footer class="py-4 text-center text-xs text-slate-400">
        © {{ date('Y') }} KerjaSetara. Portal Khusus Rekruter & Perusahaan Ramah Aksesibilitas.
    </footer>
</body>
</html>
