<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'POS Rida')</title>
    
    <!-- Tailwind CSS & DaisyUI CDN -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> <!-- Atau gunakan script resmi tailwind v3 di bawah jika v4 error -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-alert {
            animation: fadeIn 0.4s ease-out forwards;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen font-sans antialiased text-slate-800 flex flex-col justify-between selection:bg-rose-500 selection:text-white">

    <div>
        <!-- Notifikasi Flash Message Otomatis Hilang 3 Detik -->
        @if (session('success'))
            <div id="flash-message" class="fixed top-5 right-5 z-50 w-full max-w-sm px-4 transition-all duration-500 ease-in-out">
                <div role="alert" class="alert alert-success shadow-lg rounded-2xl text-white bg-emerald-600 border-none animate-alert flex items-center gap-3 p-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            </div>

            <script>
                setTimeout(function() {
                    const flashBox = document.getElementById('flash-message');
                    if (flashBox) {
                        flashBox.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                        flashBox.style.opacity = '0';
                        flashBox.style.transform = 'translateY(-10px)';
                        setTimeout(function() {
                            flashBox.remove();
                        }, 500);
                    }
                }, 3000);
            </script>
        @endif

        <!-- Panggil Navbar di Sini -->
        @auth
            @include('layouts.navbar')
        @endauth

        <!-- Konten Utama Halaman -->
        <main class="max-w-7xl mx-auto w-full p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>

    <!-- Footer Global -->
    <footer class="py-6 text-center text-xs text-slate-400 font-medium border-t border-slate-100 mt-auto">
        POS Rida &copy; 2026
    </footer>

</body>
</html>