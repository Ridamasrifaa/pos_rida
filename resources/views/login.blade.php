<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - POS Rida</title>

    <!-- Tailwind CSS & DaisyUI CDN -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#e11d48',
                        'primary-focus': '#be123c',
                    }
                }
            }
        }
    </script>

    <style>
        /* Mengunci layar penuh agar statis, rapi, dan tidak bisa di-scroll */
        html,
        body {
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        @keyframes smoothEnter {
            0% {
                opacity: 0;
                transform: translateY(15px) scale(0.97);
            }

            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .animate-card {
            animation: smoothEnter 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Animasi Karakter 3D Melayang & Berputar Halus di Luar Card */
        @keyframes floatCharacter {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-10px) rotate(3deg);
            }
        }

        .floating-cartoon {
            animation: floatCharacter 4s ease-in-out infinite;
        }

        /* Animasi Kaget/Loncat Ceria saat Tombol Masuk Diklik */
        @keyframes clickJump {
            0% {
                transform: translateY(0) scale(1) rotate(0deg);
            }

            30% {
                transform: translateY(-20px) scale(1.15) rotate(-8deg);
                filter: drop-shadow(0 15px 15px rgba(225, 29, 72, 0.3));
            }

            60% {
                transform: translateY(5px) scale(0.95) rotate(6deg);
            }

            100% {
                transform: translateY(0) scale(1) rotate(0deg);
            }
        }

        .animate-action {
            animation: clickJump 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }
    </style>
</head>

<body
    class="bg-gradient-to-br from-rose-50 via-slate-50 to-rose-100/40 h-screen flex items-center justify-center p-4 font-sans selection:bg-rose-600 selection:text-white">

    <!-- Latar Belakang Statis Terkunci yang Rapi -->
    <div class="fixed w-[30rem] h-[30rem] bg-rose-400/10 rounded-full blur-3xl -top-24 -left-24 pointer-events-none">
    </div>
    <div
        class="fixed w-[30rem] h-[30rem] bg-rose-600/10 rounded-full blur-3xl -bottom-24 -right-24 pointer-events-none">
    </div>

    <div class="w-full max-w-md animate-card relative z-10 flex flex-col items-center">

        <!-- Karakter Kartun 3D Melayang di ATAS Card -->
        <div id="cartoonCharacter" class="floating-cartoon mb-[-30px] z-20 pointer-events-none drop-shadow-xl">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" class="w-28 h-28">
                <!-- Efek Bayangan Kaki Kartun -->
                <ellipse cx="60" cy="112" rx="18" ry="5" fill="#e11d48" opacity="0.2" />

                <!-- Badan Robot/Karakter Lucu 3D -->
                <rect x="35" y="55" width="50" height="42" rx="14" fill="#e11d48" />
                <rect x="42" y="62" width="36" height="28" rx="8" fill="#ffffff" opacity="0.95" />

                <!-- Mata Kartun Berkedip Senang -->
                <circle cx="53" cy="72" r="3.5" fill="#1e293b" />
                <circle cx="67" cy="72" r="3.5" fill="#1e293b" />
                <path d="M57 80 Q60 84 63 80" stroke="#1e293b" stroke-width="2.5" stroke-linecap="round"
                    fill="none" />

                <!-- Kepala 3D Semi Lingkaran -->
                <path d="M35 55 Q60 25 85 55 Z" fill="#fb7185" />
                <circle cx="60" cy="32" r="4" fill="#ffffff" opacity="0.8" />

                <!-- Antena Lucu di Kepala -->
                <line x1="60" y1="28" x2="60" y2="14" stroke="#e11d48" stroke-width="3"
                    stroke-linecap="round" />
                <circle cx="60" cy="12" r="5" fill="#fda4af" />
            </svg>
        </div>

        <!-- Card Utama -->
        <div
            class="w-full bg-white/95 backdrop-blur-xl shadow-2xl shadow-rose-950/10 border border-white rounded-[2.5rem] overflow-hidden">
            <div class="card-body p-8 sm:p-10 pt-12">

                <!-- Header Teks -->
                <div class="text-center mb-7">
                    <h1 class="text-2xl font-black tracking-tight text-slate-800 flex items-center justify-center gap-1">
                        <span>TOKO</span><span class="text-rose-600">GO</span>
                    </h1>
                      <p class="text-xs text-slate-500 mt-5 font-medium">Silakan login menggunakan akun Anda.</p>
                
                </div>
                    
                

                <!-- Form Login -->
                <form id="loginForm" action="{{ route('auth') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Email -->
                    <div class="form-control">
                        <label class="label pb-1.5">
                            <span
                                class="label-text text-[11px] font-bold uppercase tracking-wider text-slate-600">Email</span>
                        </label>
                        <label
                            class="input input-bordered rounded-2xl flex items-center gap-3 bg-slate-50/60 focus-within:bg-white focus-within:border-rose-600 focus-within:ring-4 focus-within:ring-rose-600/10 transition-all h-12">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="w-4 h-4 text-slate-400">
                                <path
                                    d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM12.735 14c.618 0 1.093-.561.872-1.139a6.002 6.002 0 00-11.215 0c-.22.578.254 1.139.872 1.139h9.47Z" />
                            </svg>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                placeholder="nama@email.com"
                                class="grow text-sm bg-transparent focus:outline-none text-slate-800" />
                        </label>
                        @error('email')
                            <span class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-control">
                        <label class="label pb-1.5">
                            <span class="label-text text-[11px] font-bold uppercase tracking-wider text-slate-600">Kata
                                Sandi</span>

                        </label>
                        <label
                            class="input input-bordered rounded-2xl flex items-center gap-3 bg-slate-50/60 focus-within:bg-white focus-within:border-rose-600 focus-within:ring-4 focus-within:ring-rose-600/10 transition-all h-12">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                class="w-4 h-4 text-slate-400">
                                <path fill-rule="evenodd"
                                    d="M14 6a4 4 0 0 1-4.899 3.899l-1.955 1.955a.5.5 0 0 1-.353.146H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2.293a.5.5 0 0 1 .146-.353l3.955-3.955A4 4 0 1 1 14 6Zm-4-2a.75.75 0 0 0 0 1.5.5.5 0 0 1 .5.5.75.75 0 0 0 1.5 0 2 2 0 0 0-2-2Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <input type="password" name="password" required placeholder="••••••••"
                                class="grow text-sm bg-transparent focus:outline-none text-slate-800" />
                        </label>
                        @error('password')
                            <span class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Ingat Saya -->
                    <div class="form-control pt-1">
                        <label class="label cursor-pointer justify-start gap-3 p-0">
                            <input type="checkbox" name="remember"
                                class="checkbox checkbox-error checkbox-sm rounded-lg" />
                            <span class="label-text text-xs text-slate-600 font-medium">Ingat perangkat ini</span>
                        </label>
                    </div>

                    <!-- Tombol Masuk -->
                    <div class="form-control pt-2">
                        <button type="submit" id="submitBtn"
                            class="btn bg-rose-600 hover:bg-rose-700 border-none rounded-2xl text-white font-semibold text-sm shadow-lg shadow-rose-600/30 hover:shadow-rose-600/50 hover:scale-[1.02] active:scale-[0.98] transition-all h-12">
                            Masuk 
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-xs text-slate-400 mt-4 font-medium">
            POS TokoGo &copy; 2026
        </p>

    </div>

    <!-- Script Animasi Kartun 3D Saat Tombol Ditekan -->
    <script>
        const form = document.getElementById('loginForm');
        const character = document.getElementById('cartoonCharacter');

        form.addEventListener('submit', function(e) {
            character.classList.remove('floating-cartoon');
            character.classList.add('animate-action');
        });
    </script>
</body>

</html>
