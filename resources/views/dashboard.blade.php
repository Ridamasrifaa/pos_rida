@extends('layouts.app')

@section('title', 'Dashboard - POS Rida')

@section('content')
<div class="w-full max-w-6xl mx-auto space-y-8 font-sans pb-16 animate-fadeIn">

    <!-- HEADER UTAMA -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-md border border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-6 transition-all duration-300 hover:shadow-lg">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-rose-600 text-white flex items-center justify-center font-bold text-2xl shadow-lg shadow-rose-600/30 transition-transform duration-300 hover:scale-105">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div>
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Halo, {{ Auth::user()->name }}!</h1>
                    <span class="text-xs font-semibold text-rose-700 bg-rose-100 px-3 py-1.5 rounded-xl uppercase tracking-wider">
                        {{ ucfirst(Auth::user()->role->name ?? 'Kasir') }}
                    </span>
                </div>
                <p class="text-sm font-normal text-slate-600 mt-1">Akun: <span class="font-semibold text-slate-800">{{ Auth::user()->email }}</span></p>
            </div>
        </div>
        
        <div class="flex items-center gap-3 px-5 py-3.5 bg-slate-100 rounded-2xl border border-slate-200 shadow-sm self-start md:self-auto transition-colors duration-200 hover:bg-slate-200/60">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-rose-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
            </svg>
            <span class="text-sm font-semibold text-slate-900">
                {{ $tanggalHariIni->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </div>

    <!-- HAK AKSES ADMIN: KARTU STATISTIK -->
    @if(optional(Auth::user()->role)->name === 'admin')

        <div class="space-y-4">
            <h2 class="text-lg font-bold text-slate-900 px-1 flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-rose-600 animate-pulse"></span>
                Ringkasan Penjualan Hari Ini
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                
                <div class="bg-white p-6 rounded-3xl shadow-md border border-slate-200 flex items-center gap-5 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center font-semibold transition-transform duration-300 hover:rotate-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wider text-slate-500">Total Nilai Penjualan</p>
                        <h3 class="text-2xl font-bold text-slate-900 mt-1">Rp {{ number_format($ringkasan['total_penjualan'], 0, ',', '.') }}</h3>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-md border border-slate-200 flex items-center gap-5 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center font-semibold transition-transform duration-300 hover:rotate-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wider text-slate-500">Jumlah Transaksi Masuk</p>
                        <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $ringkasan['total_transaksi'] }} Transaksi</h3>
                    </div>
                </div>

            </div>
        </div>

        <div class="space-y-4">
            <h2 class="text-lg font-bold text-slate-900 px-1 flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-emerald-600 animate-pulse"></span>
                Status Metode Pembayaran
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                
                <div class="bg-white p-6 rounded-3xl shadow-md border border-slate-200 flex items-center gap-5 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-semibold transition-transform duration-300 hover:rotate-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.75 0M2.25 12h19.5m-19.5-6.75a60.07 60.07 0 0115.75 0M18 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wider text-slate-500">Total Pembayaran Tunai (Cash)</p>
                        <h3 class="text-2xl font-bold text-slate-900 mt-1">Rp {{ number_format($ringkasan['total_cash'], 0, ',', '.') }}</h3>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl shadow-md border border-slate-200 flex items-center gap-5 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-700 flex items-center justify-center font-semibold transition-transform duration-300 hover:rotate-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wider text-slate-500">Total Pembayaran Non-Tunai</p>
                        <h3 class="text-2xl font-bold text-slate-900 mt-1">Rp {{ number_format($ringkasan['total_non_tunai'], 0, ',', '.') }}</h3>
                    </div>
                </div>

            </div>
        </div>

    @endif

    <!-- TABEL PRODUK TERLARIS -->
    <div class="space-y-4">
        <h2 class="text-lg font-bold text-slate-900 px-1 flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-violet-600"></span>
            Daftar Produk Terlaris
        </h2>
        <div class="bg-white rounded-3xl shadow-md border border-slate-200 overflow-hidden transition-all duration-300 hover:shadow-lg">
            <div class="p-6 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Barang Favorit Pelanggan</h3>
                    <p class="text-sm font-normal text-slate-600 mt-0.5">Performa penjualan tertinggi berdasarkan unit.</p>
                </div>
            </div>
            
            <div class="overflow-x-auto p-2">
                <table class="table w-full text-sm">
                    <thead>
                        <tr class="text-slate-600 font-semibold text-sm uppercase tracking-wider border-b border-slate-200">
                            <th scope="col" class="bg-transparent py-4 px-6 w-20">No</th>
                            <th scope="col" class="bg-transparent py-4 px-6">Nama Produk</th>
                            <th scope="col" class="bg-transparent py-4 px-6">Sisa Stok</th>
                            <th scope="col" class="bg-transparent py-4 px-6">Unit Terjual</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 font-normal">
                        @forelse ($produkTerlaris as $index => $produk)
                            <tr class="border-b border-slate-100 transition-colors duration-150 hover:bg-rose-50/50">
                                <td class="font-medium text-slate-900 px-6 py-4">{{ $index + 1 }}</td>
                                <td class="font-medium text-slate-900 px-6 py-4">{{ $produk->nama }}</td>
                                <td class="px-6 py-4"><span class="font-medium text-slate-700">{{ $produk->stok }} Unit</span></td>
                                <td class="px-6 py-4"><span class="font-medium text-slate-700">{{ $produk->total_terjual }} Unit</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-10 text-slate-500 font-normal">Belum ada data penjualan produk hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TABEL INVENTARIS KRITIS (STOK RENDAH & HABIS) -->
    <div class="space-y-4">
        <h2 class="text-lg font-bold text-slate-900 px-1 flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-amber-600"></span>
            Status Inventaris Kritis
        </h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Stok Rendah -->
            <div class="bg-white rounded-3xl shadow-md border border-slate-200 overflow-hidden flex flex-col justify-between transition-all duration-300 hover:shadow-lg">
                <div>
                    <div class="p-6 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Peringatan Stok Menipis</h3>
                            <p class="text-sm font-normal text-slate-600 mt-0.5">Produk yang hampir habis di gudang.</p>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto p-2">
                        <table class="table w-full text-sm">
                            <thead>
                                <tr class="text-slate-600 font-semibold text-sm uppercase tracking-wider border-b border-slate-200">
                                    <th scope="col" class="bg-transparent py-4 px-4 w-16">No</th>
                                    <th scope="col" class="bg-transparent py-4 px-4">Nama Produk</th>
                                    <th scope="col" class="bg-transparent py-4 px-4">Sisa Stok</th>
                                </tr>
                            </thead>
                            <tbody class="text-slate-700 font-normal">
                                @forelse ($produkStokRendah as $index => $produk)
                                    <tr class="border-b border-slate-100 transition-colors duration-150 hover:bg-amber-50/50">
                                        <td class="font-medium text-slate-900 px-4 py-4">{{ $produkStokRendah->firstItem() + $index }}</td>
                                        <td class="px-4 py-4 font-medium text-slate-900">{{ $produk->nama }}</td>
                                        <td class="px-4 py-4"><span class="font-medium text-slate-700">{{ $produk->stok }} Unit</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-10 text-slate-500 font-normal">Seluruh produk dalam kondisi stok aman.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="p-4 border-t border-slate-200 bg-white">
                    {{ $produkStokRendah->links() }}
                </div>
            </div>

            <!-- Produk Habis -->
            <div class="bg-white rounded-3xl shadow-md border border-slate-200 overflow-hidden flex flex-col justify-between transition-all duration-300 hover:shadow-lg">
                <div>
                    <div class="p-6 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Peringatan Stok Kosong</h3>
                            <p class="text-sm font-normal text-slate-600 mt-0.5">Produk yang sudah habis total.</p>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto p-2">
                        <table class="table w-full text-sm">
                            <thead>
                                <tr class="text-slate-600 font-semibold text-sm uppercase tracking-wider border-b border-slate-200">
                                    <th scope="col" class="bg-transparent py-4 px-4 w-16">No</th>
                                    <th scope="col" class="bg-transparent py-4 px-4">Nama Produk</th>
                                    <th scope="col" class="bg-transparent py-4 px-4">Sisa Stok</th>
                                </tr>
                            </thead>
                            <tbody class="text-slate-700 font-normal">
                                @forelse ($produkStokHabis as $index => $produk)
                                    <tr class="border-b border-slate-100 transition-colors duration-150 hover:bg-rose-50/50">
                                        <td class="font-medium text-slate-900 px-4 py-4">{{ $produkStokHabis->firstItem() + $index }}</td>
                                        <td class="px-4 py-4 font-medium text-slate-900">{{ $produk->nama }}</td>
                                        <td class="px-4 py-4"><span class="font-medium text-slate-700">{{ $produk->stok }} Unit</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-10 text-slate-500 font-normal">Tidak ada produk yang habis stok.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="p-4 border-t border-slate-200 bg-white">
                    {{ $produkStokHabis->links() }}
                </div>
            </div>

        </div>
    </div>

</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.4s ease-out forwards;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const scrollPosition = sessionStorage.getItem('dashboardScrollPos');
        if (scrollPosition) {
            window.scrollTo(0, parseInt(scrollPosition));
            sessionStorage.removeItem('dashboardScrollPos');
        }

        const paginationLinks = document.querySelectorAll('.pagination a, nav[role="navigation"] a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function () {
                sessionStorage.setItem('dashboardScrollPos', window.scrollY);
            });
        });
    });
</script>
@endsection