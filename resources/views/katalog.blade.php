<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog - Toko Go</title>
    
    <!-- Tailwind CSS CDN -->
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
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome CDN untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body { font-family: 'Poppins', sans-serif; }
        .gsap-hidden { opacity: 0; }

        .card-3d {
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease;
            transform-style: preserve-3d;
        }
        .card-3d:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 30px -10px rgba(225, 29, 72, 0.15);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-rose-50 via-slate-50 to-rose-100/40 text-slate-800 antialiased overflow-x-hidden selection:bg-rose-600 selection:text-white">

    <!-- Latar Belakang Blur Estetik -->
    <div class="fixed w-[30rem] h-[30rem] bg-rose-400/10 rounded-full blur-3xl -top-24 -left-24 pointer-events-none"></div>
    <div class="fixed w-[30rem] h-[30rem] bg-rose-600/10 rounded-full blur-3xl -bottom-24 -right-24 pointer-events-none"></div>

    <!-- NAVBAR -->
    <nav class="gsap-nav bg-white/90 backdrop-blur-md border-b border-rose-100/60 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between relative z-10">
            <div class="flex items-center space-x-2">
                <span class="text-xl sm:text-2xl font-black tracking-tight text-slate-800">TOKO<span class="text-rose-600">GO</span></span>
            </div>
            <div>
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center bg-rose-600 hover:bg-rose-700 rounded-2xl text-white font-semibold text-xs sm:text-sm transition-all px-5 py-2.5 hover:scale-[1.02] active:scale-[0.98]">
                    Sign In
                </a>
            </div>
        </div>
    </nav>

    <!-- 1. HERO SECTION -->
    <section class="relative overflow-hidden py-28 lg:py-36 flex items-center justify-center">
        <!-- Background Video & Overlay -->
        <div class="absolute inset-0 z-0 overflow-hidden">
            <video autoplay muted loop playsinline class="w-full h-full object-cover scale-105">
                <source src="{{ asset('videos/toko.mp4') }}" type="video/mp4">
                Browser Anda tidak Mendukung Tag Video.
            </video>
            <!-- Overlay Gelap Transparan -->
            <div class="absolute inset-0 bg-slate-950/75 backdrop-blur-[2px]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10 text-center text-white space-y-6 max-w-3xl">
            <div class="hero-content space-y-6 gsap-hidden">
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white leading-tight">
                    Belanja Mudah, Cepat & <span class="text-rose-500 font-black">Terpercaya</span>
                </h1>
                
                <p class="text-slate-300 text-sm sm:text-base max-w-xl mx-auto font-light leading-relaxed">
                    Nikmati pengalaman melihat pilihan produk berkualitas terbaik kami langsung dari genggaman Anda.
                </p>
                
                <div class="pt-3">
                    <a href="#katalog" class="inline-flex items-center justify-center bg-rose-600 hover:bg-rose-700 rounded-2xl text-white font-semibold text-sm transition-all px-8 py-4 hover:scale-[1.02] active:scale-[0.98]">
                        Jelajahi Katalog
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. KATALOG PRODUK -->
    <section id="katalog" class="katalog-section max-w-7xl mx-auto px-6 py-24 relative z-10">
        <div class="katalog-header flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-2 gsap-hidden">
            <div>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Katalog Produk Favorit</h2>
                <p class="text-slate-500 text-xs sm:text-sm mt-1.5 font-medium">Pilih produk terlaris dan favorit pilihan pelanggan kami hari ini.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($produks->take(8) as $index => $item)
            <div class="product-card gsap-hidden bg-white/95 backdrop-blur-xl rounded-[2rem] shadow-xl shadow-rose-950/5 hover:shadow-2xl transition-all duration-300 border border-white overflow-hidden flex flex-col justify-between group card-3d relative">
                
             @if($index === 0)
<div class="absolute top-4 left-4 z-20 bg-amber-400 text-slate-900 text-xs font-bold px-3 py-1.5 rounded-full shadow-md flex items-center gap-1.5">
    <i class="fa-solid fa-star text-xs"></i>
    <span>BEST SELLER</span>
</div>
@endif

                <div>
                    <div class="w-full h-52 bg-slate-100 overflow-hidden relative">
                        @if(isset($item->foto) && $item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs font-medium">Foto Produk</div>
                        @endif
                    </div>
                    <div class="p-6 space-y-3">
                        <h3 class="font-bold text-slate-800 text-base line-clamp-1">{{ $item->nama ?? $item->nama_produk ?? 'Nama Produk' }}</h3>
                        <div class="flex items-center justify-between">
                            <p class="text-rose-600 font-black text-lg">Rp {{ number_format($item->harga ?? $item->harga_jual ?? 0, 0, ',', '.') }}</p>
                            <span class="text-xs font-semibold text-slate-600">
                                Stok: {{ $item->stok ?? 0 }}
                            </span>
                        </div>
                        
                        @if(isset($item->total_terjual))
                        <div class="text-xs text-slate-500 font-medium pt-2 border-t border-slate-100 flex items-center justify-between">
                            <span>Total Terjual:</span>
                            <span class="font-bold text-slate-700">{{ $item->total_terjual }} Unit</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16 bg-white/95 rounded-[2rem] border border-white shadow-xl shadow-rose-950/5">
                <p class="text-slate-500 text-sm font-medium">Belum ada produk yang tersedia di katalog saat ini.</p>
            </div>
            @endforelse
        </div>

        @if(isset($produks) && $produks->count() > 8)
        <div class="mt-12 text-center gsap-hidden">
            <a href="#" class="inline-flex items-center justify-center bg-white hover:bg-rose-50 text-slate-800 hover:text-rose-600 border border-slate-200 hover:border-rose-200 rounded-2xl font-semibold text-sm transition-all px-8 py-3.5 shadow-sm hover:scale-[1.02] active:scale-[0.98]">
                Lihat Selengkapnya <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
            </a>
        </div>
        @endif
    </section>

    <!-- 3. TENTANG KAMI -->
    <section class="about-section bg-white/80 backdrop-blur-md py-24 border-t border-rose-100/60 overflow-hidden relative z-10">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div class="about-text space-y-4 gsap-hidden">
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Tentang Toko Go</h2>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed font-light">
                    Toko Go hadir untuk memberikan pelayanan terbaik bagi pelanggan. Kami menyediakan berbagai macam produk berkualitas tinggi yang siap memenuhi kebutuhan harian Anda dengan cepat, mudah, dan terpercaya. Komitmen kami adalah kepuasan Anda dalam berbelanja.
                </p>
            </div>
            <div class="about-image gsap-hidden">
                <div class="w-full h-64 sm:h-80 bg-slate-100 rounded-[2rem] overflow-hidden shadow-xl border border-white">
                    <img src="{{ asset('images/download.jpg') }}" alt="Suasana Toko" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- 4. FOOTER -->
    <footer class="footer-section bg-slate-900 text-slate-400 py-16 relative z-10">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-10">
            <div class="footer-col space-y-3 md:col-span-2 gsap-hidden">
                <h3 class="text-white text-xl font-bold tracking-tight">TOKO<span class="text-rose-500">GO</span></h3>
                <p class="text-xs sm:text-sm leading-relaxed max-w-sm font-light text-slate-400">
                    Solusi belanja praktis dan modern untuk kebutuhan harian Anda. Selalu mengutamakan kualitas dan pelayanan terbaik.
                </p>
                
                <!-- Alamat & Kontak di Footer -->
                <div class="pt-2 text-xs text-slate-400 space-y-1">
                    <p><i class="fa-solid fa-location-dot text-rose-500 mr-2"></i> Jl. Raya Tasikmalaya No. 123</p>
                    <p><i class="fa-solid fa-envelope text-rose-500 mr-2"></i> support@tokogo.test | Telp: 0812-3456-7890</p>
                </div>
            </div>
            <div class="footer-col space-y-2.5 gsap-hidden">
                <h4 class="text-white font-semibold text-xs uppercase tracking-wider">Menu Utama</h4>
                <ul class="space-y-2 text-xs sm:text-sm">
                    <li><a href="#" class="hover:text-white hover:translate-x-1 inline-block transition-transform duration-300">Beranda</a></li>
                    <li><a href="#katalog" class="hover:text-white hover:translate-x-1 inline-block transition-transform duration-300">Katalog Produk</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-white hover:translate-x-1 inline-block transition-transform duration-300">Sign In Staff</a></li>
                </ul>
            </div>
            <div class="footer-col space-y-2.5 gsap-hidden">
                <h4 class="text-white font-semibold text-xs uppercase tracking-wider">Media Sosial</h4>
                <div class="flex space-x-3 pt-1">
                    <a href="#" class="w-9 h-9 rounded-2xl bg-slate-800 flex items-center justify-center text-white hover:bg-rose-600 hover:-translate-y-1 transition-all duration-300 shadow-sm">
                        <i class="fa-brands fa-instagram text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-2xl bg-slate-800 flex items-center justify-center text-white hover:bg-rose-600 hover:-translate-y-1 transition-all duration-300 shadow-sm">
                        <i class="fa-brands fa-tiktok text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-2xl bg-slate-800 flex items-center justify-center text-white hover:bg-rose-600 hover:-translate-y-1 transition-all duration-300 shadow-sm">
                        <i class="fa-brands fa-facebook-f text-sm"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="footer-bottom max-w-7xl mx-auto px-6 mt-12 pt-8 border-t border-slate-800 text-center text-xs text-slate-500 gsap-hidden">
            &copy; 2026 Toko Go. All rights reserved.
        </div>
    </footer>

    <!-- GSAP & ScrollTrigger Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", (event) => {
            gsap.registerPlugin(ScrollTrigger);

            gsap.from(".gsap-nav", {
                y: -100,
                opacity: 0,
                duration: 1,
                ease: "power3.out"
            });

            gsap.fromTo(".hero-content", 
                { y: 30, opacity: 0 },
                { y: 0, opacity: 1, duration: 1.2, ease: "power3.out", delay: 0.2 }
            );

            gsap.fromTo(".katalog-header",
                { y: 30, opacity: 0 },
                {
                    y: 0, opacity: 1, duration: 0.8, ease: "power2.out",
                    scrollTrigger: { trigger: ".katalog-section", start: "top 80%" }
                }
            );

            gsap.fromTo(".product-card",
                { y: 50, opacity: 0 },
                {
                    y: 0, opacity: 1, duration: 0.6, stagger: 0.15, ease: "power2.out",
                    scrollTrigger: { trigger: ".katalog-section", start: "top 75%" }
                }
            );

            gsap.fromTo(".about-section .about-text",
                { x: -50, opacity: 0 },
                {
                    x: 0, opacity: 1, duration: 1, ease: "power3.out",
                    scrollTrigger: { trigger: ".about-section", start: "top 75%" }
                }
            );
            
            gsap.fromTo(".about-section .about-image",
                { x: 50, opacity: 0 },
                {
                    x: 0, opacity: 1, duration: 1, ease: "power3.out",
                    scrollTrigger: { trigger: ".about-section", start: "top 75%" }
                }
            );

            gsap.fromTo(".footer-col",
                { y: 30, opacity: 0 },
                {
                    y: 0, opacity: 1, duration: 0.8, stagger: 0.2, ease: "power2.out",
                    scrollTrigger: { trigger: ".footer-section", start: "top 85%" }
                }
            );

            gsap.fromTo(".footer-bottom",
                { opacity: 0 },
                {
                    y: 0, opacity: 1, duration: 1, delay: 0.5, ease: "power2.out",
                    scrollTrigger: { trigger: ".footer-section", start: "top 95%" }
                }
            );
        });
    </script>
</body>
</html>