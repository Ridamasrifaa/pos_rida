@extends('layouts.app')

@section('content')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes float3D {
        0%, 100% { transform: translateY(0px) rotateX(6deg) rotateY(-10deg); }
        50% { transform: translateY(-12px) rotateX(12deg) rotateY(-4deg); }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; }
    .delay-400 { animation-delay: 0.4s; }

    .perspective-container {
        perspective: 1200px;
    }

    .card-3d-floating {
        transform-style: preserve-3d;
        animation: float3D 6s ease-in-out infinite;
    }
</style>

<div class="max-w-6xl mx-auto px-4 py-8 space-y-8 font-sans">

    <!-- BAGIAN 1: PROFIL PENGEMBANG / DEVELOPER -->
    <div class="opacity-0 animate-fade-in-up bg-white border border-slate-200 shadow-sm rounded-3xl p-8 sm:p-10 relative overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            <div class="lg:col-span-7 space-y-5">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-rose-50 border border-rose-100 rounded-full text-rose-600 text-sm font-semibold">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                    Dokumentasi Resmi Pengembang Sistem
                </div>
                
                <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-normal leading-snug">
                    Mengenal Lebih Dekat <br>
                    <span class="text-rose-600">Pembuat Aplikasi TOKO GO</span>
                </h1>
                
                <p class="text-slate-600 text-base leading-relaxed text-justify">
                    Aplikasi Point of Sale (POS) <strong class="text-slate-900 font-semibold">TOKO GO</strong> ini dikembangkan secara mandiri sebagai wujud implementasi nyata pembelajaran rekayasa perangkat lunak berbasis web. Proyek ini dirancang untuk menjawab tantangan otomatisasi kasir ritel modern, mulai dari pencatatan inventaris barang hingga rekapitulasi data transaksi keuangan harian yang akurat.
                </p>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-4 border-t border-slate-100">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-1">
                        <div class="text-xs uppercase tracking-wider font-bold text-slate-500">Nama Lengkap</div>
                        <div class="text-sm sm:text-base font-bold text-slate-900">Rida Masrifa</div>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-1">
                        <div class="text-xs uppercase tracking-wider font-bold text-slate-500">Jenjang / Kelas</div>
                        <div class="text-sm sm:text-base font-bold text-rose-600">Kelas 11 SMK</div>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-1 col-span-2 sm:col-span-1">
                        <div class="text-xs uppercase tracking-wider font-bold text-slate-500">Program Keahlian</div>
                        <div class="text-sm sm:text-base font-bold text-slate-900">PPLG (RPL)</div>
                    </div>
                </div>
            </div>

            <!-- FOTO DEVELOPER DENGAN EFEK 3D -->
            <div class="lg:col-span-5 flex justify-center perspective-container">
                <div class="card-3d-floating w-full max-w-xs bg-white rounded-3xl p-5 shadow-xl border border-slate-200 space-y-4">
                    <div class="w-full h-72 bg-slate-100 rounded-2xl overflow-hidden relative border border-slate-200 shadow-inner flex items-center justify-center">
                        <!-- 
                          CARA MEMASUKKAN FOTO KAMU:
                          Simpan foto di folder public/images/fotoku.jpg
                          Ubah src di bawah menjadi: src="{{ asset('images/fotoku.jpg') }}"
                        -->
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=500&auto=format&fit=crop&q=60" alt="Foto Rida Masrifa" class="w-full h-full object-cover">
                        
                        <div class="absolute bottom-3 left-3 bg-slate-900/80 backdrop-blur-md px-3.5 py-1.5 rounded-xl text-white text-xs font-semibold tracking-wide">
                            Lead Software Developer
                        </div>
                    </div>

                    <div class="text-center space-y-1">
                        <div class="font-bold text-slate-900 text-base">Rida Masrifa</div>
                        <div class="text-sm text-slate-500 font-medium">Pengembang Sistem TOKO GO</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- BAGIAN 2: PENJELASAN LENGKAP TENTANG SISTEM POS -->
    <div class="opacity-0 animate-fade-in-up delay-100 bg-white border border-slate-200 shadow-sm rounded-3xl p-8 sm:p-10 space-y-5">
        <div class="border-b border-slate-100 pb-4 space-y-1">
            <h2 class="text-2xl font-bold text-slate-900">Tinjauan Konseptual & Fungsi Utama Sistem POS</h2>
            <p class="text-base text-slate-500">Penjelasan komprehensif mengenai latar belakang teknis pembangunan aplikasi TOKO GO</p>
        </div>

        <div class="text-slate-600 text-base leading-relaxed space-y-4 text-justify">
            <p>
                Sistem Point of Sale (POS) <strong class="text-slate-900 font-semibold">TOKO GO</strong> dibangun untuk mengatasi permasalahan umum dalam pencatatan transaksi ritel tradisional yang seringkali rentan terhadap kesalahan manusia (human error), ketidakakuratan rekapitulasi stok barang, dan lambatnya pelaporan keuangan bulanan. Dengan mengadopsi arsitektur MVC (Model-View-Controller) pada framework Laravel, aplikasi ini menjamin pemisahan logika bisnis yang bersih dan keamanan data yang terenkripsi.
            </p>
            <p>
                Seluruh fitur di dalam aplikasi ini dirancang berdasarkan studi kasus operasional toko nyata, di mana setiap hak akses pengguna dibagi secara ketat antara peran <strong class="text-rose-600 font-semibold">Administrator</strong> dan <strong class="text-rose-600 font-semibold">Kasir</strong> guna menjaga integritas dan kerahasiaan data internal perusahaan.
            </p>
        </div>
    </div>

    <!-- BAGIAN 3: STRUKTUR HAK AKSES PENGGUNA (RBAC) SECARA DETAIL -->
    <div class="opacity-0 animate-fade-in-up delay-200 space-y-6">
        <div class="text-center space-y-2">
            <h2 class="text-2xl font-bold text-slate-900">Analisis Hak Akses & Pembagian Peran Pengguna</h2>
            <p class="text-base text-slate-500">Sistem keamanan otorisasi bertingkat untuk pembagian tugas operasional harian</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Administrator Detail -->
            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="font-bold text-slate-900 text-lg">Modul Administrator</h3>
                    <span class="px-3 py-1 bg-rose-50 text-rose-600 rounded-xl text-xs font-bold uppercase tracking-wide">Akses Penuh</span>
                </div>
                <div class="text-slate-600 text-base space-y-3.5 leading-relaxed">
                    <p>
                        <strong class="text-slate-900 font-semibold">1. Pengelolaan Akun Pengguna:</strong> Admin memiliki otoritas penuh untuk menambahkan akun pengguna baru (kasir/admin), mengubah kata sandi, atau menghapus akses pengguna yang sudah tidak aktif.
                    </p>
                    <p>
                        <strong class="text-slate-900 font-semibold">2. Pengaturan Kategori & Jenis Barang:</strong> Mengelompokkan jenis produk ke dalam kategori tertentu agar manajemen inventaris toko tertata dengan sistematis.
                    </p>
                    <p>
                        <strong class="text-slate-900 font-semibold">3. Audit Laporan Keuangan Bulanan:</strong> Memantau rekapitulasi total penjualan, grafik pemasukan, dan analisis produk terlaris secara periodik.
                    </p>
                </div>
            </div>

            <!-- Kasir Detail -->
            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="font-bold text-slate-900 text-lg">Modul Kasir & Operasional</h3>
                    <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wide">Akses Terbatas</span>
                </div>
                <div class="text-slate-600 text-base space-y-3.5 leading-relaxed">
                    <p>
                        <strong class="text-slate-900 font-semibold">1. Pemantauan Katalog & Stok:</strong> Memeriksa ketersediaan stok fisik barang secara real-time sebelum melakukan transaksi dengan pembeli.
                    </p>
                    <p>
                        <strong class="text-slate-900 font-semibold">2. Eksekusi Keranjang Penjualan:</strong> Memasukkan produk ke dalam daftar belanja, menghitung total harga secara otomatis, serta memproses pembayaran tunai.
                    </p>
                    <p>
                        <strong class="text-slate-900 font-semibold">3. Pencetakan Struk Pembayaran:</strong> Menghasilkan bukti transaksi sah yang langsung tercatat ke dalam database riwayat penjualan harian toko.
                    </p>
                </div>
            </div>

        </div>
    </div>

    <!-- BAGIAN 4: ALUR LOGIKA DATABASE DAN SINKRONISASI STOK -->
    <div class="opacity-0 animate-fade-in-up delay-300 bg-white border border-slate-200 shadow-sm rounded-3xl p-8 sm:p-10 space-y-6">
        <div class="border-b border-slate-100 pb-4 space-y-1">
            <h2 class="text-2xl font-bold text-slate-900">Alur Logika Basis Data & Pengurangan Stok Otomatis</h2>
            <p class="text-base text-slate-500">Mekanisme sinkronisasi data di balik layar ketika transaksi kasir dieksekusi</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-slate-600 text-base">
            
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-3">
                <div class="font-bold text-rose-600 uppercase tracking-wider text-xs">Tahap Inisialisasi</div>
                <h3 class="font-bold text-slate-900 text-lg">Registrasi Data Produk</h3>
                <p class="leading-relaxed text-justify">
                    Administrator mendaftarkan produk baru yang mencakup nama barang, kode SKU, harga beli, harga jual, dan jumlah stok awal. Data ini disimpan ke dalam tabel relasional database.
                </p>
            </div>

            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-3">
                <div class="font-bold text-rose-600 uppercase tracking-wider text-xs">Tahap Transaksi</div>
                <h3 class="font-bold text-slate-900 text-lg">Validasi Keranjang Kasir</h3>
                <p class="leading-relaxed text-justify">
                    Kasir memilih produk yang dibeli pembeli melalui antarmuka penjualan. Sistem melakukan validasi logika stok secara instan untuk mencegah kasir menjual barang yang jumlah stoknya sudah habis.
                </p>
            </div>

            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-3">
                <div class="font-bold text-rose-600 uppercase tracking-wider text-xs">Tahap Sinkronisasi</div>
                <h3 class="font-bold text-slate-900 text-lg">Commit & Pembaruan Stok</h3>
                <p class="leading-relaxed text-justify">
                    Setelah tombol pembayaran dikonfirmasi, sistem menyimpan data transaksi dan secara otomatis menjalankan perintah pengurangan kolom stok pada tabel produk secara real-time.
                </p>
            </div>

        </div>
    </div>

    <!-- BAGIAN 5: FOOTER SPESIFIKASI TEKNIS & LISENSI -->
    <div class="opacity-0 animate-fade-in-up delay-400 bg-slate-900 text-white rounded-3xl p-8 sm:p-10 flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="space-y-2 text-center sm:text-left">
            <h3 class="font-bold text-xl">TOKO GO Enterprise POS Engine</h3>
            <p class="text-base text-slate-300">
                Dikembangkan dengan penuh dedikasi oleh <strong class="text-white font-semibold">Rida Masrifa</strong> (Kelas 11 PPLG SMK) menggunakan Laravel, Tailwind CSS, dan DaisyUI.
            </p>
        </div>
        <div class="flex flex-wrap items-center justify-center gap-3">
            <span class="px-4 py-2 bg-slate-800 border border-slate-700 rounded-xl text-sm font-semibold text-rose-400">
                PHP & Laravel 10+
            </span>
            <span class="px-4 py-2 bg-rose-600 rounded-xl text-sm font-semibold text-white shadow-sm">
                Release 2026
            </span>
        </div>
    </div>

</div>
@endsection