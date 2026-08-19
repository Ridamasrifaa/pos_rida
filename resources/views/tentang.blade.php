@extends('layouts.app')

@section('title', 'Tentang Pengembang - TOKO GO')

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

    .perspective-container {
        perspective: 1200px;
    }

    .card-3d-floating {
        transform-style: preserve-3d;
        animation: float3D 6s ease-in-out infinite;
    }
</style>

<div class="max-w-6xl mx-auto px-4 py-8 space-y-8 font-sans">

    <!-- BAGIAN 1: PROFIL & TENTANG APLIKASI -->
    <div class="opacity-0 animate-fade-in-up bg-white border border-slate-200 shadow-sm rounded-3xl p-8 sm:p-10 relative overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

            <div class="lg:col-span-7 space-y-5">
               

                <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-normal leading-snug">
                    Kasir Modern yang Bikin <br>
                    <span class="text-rose-600">Urusan Toko Jadi Lebih Mudah</span>
                </h1>

                <p class="text-slate-600 text-base leading-relaxed">
                    <strong class="text-slate-900 font-semibold">TOKO GO</strong> adalah aplikasi kasir berbasis web yang dibuat biar kamu nggak perlu ribet lagi catat transaksi secara manual. Semua penjualan, stok barang, sampai laporan keuangan bisa dikelola dengan cepat dan akurat dalam satu tempat. Praktis, aman, dan pastinya hemat waktu!
                </p>
            </div>

            <!-- FOTO DEVELOPER DENGAN EFEK 3D -->
            <div class="lg:col-span-5 flex justify-center perspective-container">
                <div class="card-3d-floating w-full max-w-xs bg-white rounded-3xl p-5 shadow-xl border border-slate-200 space-y-4">
                    <div class="w-full h-72 bg-slate-100 rounded-2xl overflow-hidden relative border border-slate-200 shadow-inner flex items-center justify-center">
                        <img src="{{ asset('images/rid.jpeg') }}" alt="Foto Rida Masrifa" class="w-full h-full object-cover">
                        <div class="absolute bottom-3 left-3 bg-slate-900/80 backdrop-blur-md px-3.5 py-1.5 rounded-xl text-white text-xs font-semibold tracking-wide">
                            Developer
                        </div>
                    </div>

                    <div class="text-center space-y-1">
                        <div class="font-bold text-slate-900 text-base">Rida Masrifa</div>
                        <div class="text-sm text-slate-500 font-medium">Siswi PPLG • SMKN 4 Tasikmalaya</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- BAGIAN 2: FITUR & HAK AKSES UTAMA (RBAC) -->
    <div class="opacity-0 animate-fade-in-up delay-100 space-y-6">
        <div class="text-center space-y-2">
            <h2 class="text-2xl font-bold text-slate-900">Fitur Utama & Pembagian Peran</h2>
            <p class="text-base text-slate-500">Setiap orang punya akses yang pas sesuai kebutuhannya</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Administrator Detail -->
            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="font-bold text-slate-900 text-lg">Untuk Administrator</h3>
                    <span class="px-3 py-1 bg-rose-50 text-rose-600 rounded-xl text-xs font-bold uppercase tracking-wide">Akses Penuh</span>
                </div>
                <ul class="text-slate-600 text-base space-y-2.5 list-disc list-inside leading-relaxed">
                    <li>Kelola data pengguna (Admin & Kasir) dengan mudah</li>
                    <li>Atur kategori barang dan pantau stok inventaris</li>
                    <li>Lihat laporan keuangan dan rekapitulasi transaksi</li>
                </ul>
            </div>

            <!-- Kasir Detail -->
            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="font-bold text-slate-900 text-lg">Untuk Kasir</h3>
                    <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-wide">Operasional</span>
                </div>
                <ul class="text-slate-600 text-base space-y-2.5 list-disc list-inside leading-relaxed">
                    <li>Cek ketersediaan stok produk secara real-time</li>
                    <li>Proses transaksi dan keranjang belanja dengan cepat</li>
                    <li>Cetak struk pembayaran langsung untuk pelanggan</li>
                </ul>
            </div>

        </div>
    </div>

    <!-- BAGIAN 3: ALUR SINGKAT TRANSAKSI -->
    <div class="opacity-0 animate-fade-in-up delay-200 bg-white border border-slate-200 shadow-sm rounded-3xl p-8 sm:p-10 space-y-6">
        <div class="border-b border-slate-100 pb-4 space-y-1">
            <h2 class="text-2xl font-bold text-slate-900">Cara Kerja TOKO GO</h2>
            <p class="text-base text-slate-500">Simpel, cepat, dan otomatis — begini alurnya</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-slate-600 text-base">
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-2 hover:bg-rose-50/50 transition-colors">
                <div class="font-bold text-rose-600 text-xs uppercase">Langkah 1</div>
                <h3 class="font-bold text-slate-900 text-base">Daftarkan Produk</h3>
                <p class="text-sm leading-relaxed">Admin cukup input nama barang, harga, dan stok awal. Semua data langsung tersimpan rapi.</p>
            </div>
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-2 hover:bg-rose-50/50 transition-colors">
                <div class="font-bold text-rose-600 text-xs uppercase">Langkah 2</div>
                <h3 class="font-bold text-slate-900 text-base">Proses Transaksi</h3>
                <p class="text-sm leading-relaxed">Kasir pilih barang yang dibeli pelanggan. Sistem otomatis cek stok biar nggak kelebihan jual.</p>
            </div>
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 space-y-2 hover:bg-rose-50/50 transition-colors">
                <div class="font-bold text-rose-600 text-xs uppercase">Langkah 3</div>
                <h3 class="font-bold text-slate-900 text-base">Stok Otomatis Berkurang</h3>
                <p class="text-sm leading-relaxed">Setelah pembayaran selesai, stok langsung terpotong secara real-time. Nggak perlu hitung manual lagi!</p>
            </div>
        </div>
    </div>

    <!-- BAGIAN 4: FOOTER -->
    <div class="opacity-0 animate-fade-in-up delay-300 bg-slate-900 text-white rounded-3xl p-8 flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="space-y-1 text-center sm:text-left">
            <h3 class="font-bold text-lg">TOKO GO Web Application</h3>
            <p class="text-sm text-slate-300">
                Dibuat dengan sepenuh hati oleh <strong class="text-white">Rida Masrifa</strong>  
                (Siswi PPLG • SMKN 4 Tasikmalaya) menggunakan Laravel.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-3.5 py-1.5 bg-rose-600 rounded-xl text-xs font-semibold text-white shadow-sm">
                Rilis 2026
            </span>
        </div>
    </div>

</div>
@endsection