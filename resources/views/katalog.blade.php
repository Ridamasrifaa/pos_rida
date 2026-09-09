<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Toko Go</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome CDN untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body { font-family: 'Poppins', sans-serif; }
        /* Animasi float bawaan CSS */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .animate-float { animation: float 4s ease-in-out infinite; }
        
        /* Mencegah elemen terlihat sebelum GSAP berjalan */
        .gsap-hidden { opacity: 0; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased overflow-x-hidden">

    <!-- NAVBAR -->
    <nav class="gsap-nav bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-2xl font-extrabold text-rose-600 tracking-tight">Toko Go</span>
            </div>
            <div>
                <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-semibold text-white bg-rose-600 rounded-full hover:bg-rose-700 transition shadow-md shadow-rose-600/20">
                    Sign In
                </a>
            </div>
        </div>
    </nav>

    <!-- 1. HERO SECTION -->
    <section class="relative overflow-hidden bg-gradient-to-b from-rose-50/50 to-slate-50 py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="hero-content space-y-6 text-center lg:text-left gsap-hidden">
                <span class="inline-block px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-rose-600 bg-rose-100/80 rounded-full">
                    Selamat Datang di Toko Go ✨
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-tight">
                    Belanja Mudah, Cepat & <span class="text-rose-600">Terpercaya</span>
                </h1>
                <p class="text-slate-600 text-base sm:text-lg">
                    Nikmati pengalaman melihat pilihan produk berkualitas terbaik kami langsung dari genggaman Anda.
                </p>
            </div>
            <div class="hero-image flex justify-center gsap-hidden">
                <img src="https://images.unsplash.com/photo-1556742049-0a67d553625a?auto=format&fit=crop&q=80&w=700" alt="Ilustrasi Toko" class="w-full max-w-md rounded-3xl shadow-2xl animate-float object-cover h-80">
            </div>
        </div>
    </section>

    <!-- 2. KATALOG PRODUK -->
    <section class="katalog-section max-w-7xl mx-auto px-6 py-16">
        <div class="katalog-header flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-4 gsap-hidden">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900">Katalog Produk Pilihan</h2>
                <p class="text-slate-500 text-sm mt-1">Pilih produk favorit Anda yang tersedia hari ini.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($produks as $item)
            <div class="product-card gsap-hidden bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 overflow-hidden flex flex-col justify-between group">
                <div>
                    <div class="w-full h-52 bg-slate-100 overflow-hidden relative">
                        @if(isset($item->foto) && $item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs font-medium">Foto Produk</div>
                        @endif
                    </div>
                    <div class="p-5 space-y-2">
                        <h3 class="font-bold text-slate-800 text-lg line-clamp-1">{{ $item->nama ?? $item->nama_produk ?? 'Nama Produk' }}</h3>
                        <p class="text-rose-600 font-bold text-lg">Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="px-5 pb-5">
                    <span class="inline-block text-xs font-medium bg-slate-100 text-slate-600 px-3 py-1 rounded-full">
                        Stok: {{ $item->stok ?? 0 }} Tersedia
                    </span>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16 bg-white rounded-2xl border border-slate-100">
                <p class="text-slate-500 font-medium">Belum ada produk yang tersedia di katalog saat ini.</p>
            </div>
            @endforelse
        </div>
    </section>

    <!-- 3. TENTANG KAMI -->
 <!-- 3. TENTANG KAMI -->
<section class="about-section bg-white py-20 border-t border-slate-100 overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="about-text space-y-4 gsap-hidden">
            <h2 class="text-3xl font-extrabold text-slate-900">Tentang Toko Go</h2>
            <p class="text-slate-600 leading-relaxed">
                Toko Go hadir untuk memberikan pelayanan terbaik bagi pelanggan. Kami menyediakan berbagai macam produk berkualitas tinggi yang siap memenuhi kebutuhan harian Anda dengan cepat, mudah, dan terpercaya. Komitmen kami adalah kepuasan Anda dalam berbelanja.
            </p>
        </div>
        <div class="about-image gsap-hidden">
            <!-- Bagian yang diubah -->
            <div class="w-full h-72 bg-slate-100 rounded-3xl overflow-hidden shadow-inner">
                <img src="{{ asset('images/download.jpg') }}" alt="Suasana Toko" class="w-full h-full object-cover">
            </div>
        </div>
    </div>
</section>

    <!-- 4. FOOTER -->
    <footer class="footer-section bg-slate-900 text-slate-400 py-16">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-10">
            <div class="footer-col space-y-4 md:col-span-2 gsap-hidden">
                <h3 class="text-white text-2xl font-bold tracking-tight">Toko Go</h3>
                <p class="text-sm leading-relaxed max-w-sm">
                    Solusi belanja praktis dan modern untuk kebutuhan harian Anda. Selalu mengutamakan kualitas dan pelayanan terbaik.
                </p>
            </div>
            <div class="footer-col space-y-3 gsap-hidden">
                <h4 class="text-white font-semibold text-sm tracking-wider uppercase">Menu Utama</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-white hover:translate-x-1 inline-block transition-transform duration-300">Beranda</a></li>
                    <li><a href="#" class="hover:text-white hover:translate-x-1 inline-block transition-transform duration-300">Katalog Produk</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-white hover:translate-x-1 inline-block transition-transform duration-300">Sign In Staff</a></li>
                </ul>
            </div>
            <div class="footer-col space-y-3 gsap-hidden">
                <h4 class="text-white font-semibold text-sm tracking-wider uppercase">Media Sosial</h4>
                <div class="flex space-x-4 pt-1">
                    <!-- Ikon Sosial Media FontAwesome -->
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-white hover:bg-rose-600 hover:-translate-y-1 transition-all duration-300 shadow-lg">
                        <i class="fa-brands fa-instagram text-lg"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-white hover:bg-rose-600 hover:-translate-y-1 transition-all duration-300 shadow-lg">
                        <i class="fa-brands fa-tiktok text-lg"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-white hover:bg-rose-600 hover:-translate-y-1 transition-all duration-300 shadow-lg">
                        <i class="fa-brands fa-facebook-f text-lg"></i>
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

            // 1. Animasi Navbar (Turun dari atas)
            gsap.from(".gsap-nav", {
                y: -100,
                opacity: 0,
                duration: 1,
                ease: "power3.out"
            });

            // 2. Animasi Hero Section (Teks dan Gambar)
            const heroTl = gsap.timeline();
            heroTl.fromTo(".hero-content", 
                { y: 50, opacity: 0 },
                { y: 0, opacity: 1, duration: 1, ease: "power3.out", delay: 0.2 }
            )
            .fromTo(".hero-image",
                { x: 50, opacity: 0, scale: 0.9 },
                { x: 0, opacity: 1, scale: 1, duration: 1, ease: "power3.out" },
                "-=0.6" // Mulai sedikit lebih awal sebelum hero-content selesai
            );

            // 3. Animasi Katalog Produk (Muncul bergantian saat discroll)
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

            // 4. Animasi Tentang Kami (Masuk dari kiri dan kanan)
            gsap.fromTo(".about-text",
                { x: -50, opacity: 0 },
                {
                    x: 0, opacity: 1, duration: 1, ease: "power3.out",
                    scrollTrigger: { trigger: ".about-section", start: "top 75%" }
                }
            );
            
            gsap.fromTo(".about-image",
                { x: 50, opacity: 0 },
                {
                    x: 0, opacity: 1, duration: 1, ease: "power3.out",
                    scrollTrigger: { trigger: ".about-section", start: "top 75%" }
                }
            );

            // 5. Animasi Footer
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
                    opacity: 1, duration: 1, delay: 0.5, ease: "power2.out",
                    scrollTrigger: { trigger: ".footer-section", start: "top 95%" }
                }
            );
        });
    </script>
</body>
</html>