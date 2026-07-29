@extends('layouts.app')

@section('title', 'Data Penjualan - POS Rida')

@section('content')
<div class="w-full max-w-6xl mx-auto space-y-6 font-sans py-6 px-4">

    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xl shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.66 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.66m-5.801 0c-.37.025-.74.062-1.102.108-1.131.094-1.976 1.057-1.976 2.218v14.132a2.25 2.25 0 002.25 2.25h1.5M6.75 16.5H4.875c-.621 0-1.125-.504-1.125-1.125V4.875c0-.621.504-1.125 1.125-1.125h1.875M6.75 16.5v-10.5m0 10.5h3.75" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-800">Manajemen Penjualan</h1>
                <p class="text-sm text-slate-500 mt-0.5">Daftar seluruh riwayat transaksi penjualan kasir.</p>
            </div>
        </div>
        
        <div>
            <a href="{{ route('penjualan.create') }}" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-semibold shadow-sm transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Transaksi Baru
            </a>
        </div>
    </div>

 

    <!-- Tabel Data Penjualan -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg text-slate-800">Riwayat Transaksi</h3>
                <span class="text-xs text-slate-400 font-medium">Total: <span id="total-penjualan">{{ $penjualans->total() ?? 0 }}</span> Transaksi</span>
            </div>

            <!-- Form Pencarian Realtime -->
            <div class="flex items-center gap-2">
                <div class="relative">
                    <input type="text" id="search-input" value="{{ request('search') }}" placeholder="Cari ID, Kasir, Status..." class="w-full sm:w-64 px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-rose-500">
                </div>
                <button type="button" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-sm font-semibold transition">
                    Cari
                </button>
            </div>
        </div>

        <div class="overflow-x-auto p-2">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="text-slate-400 text-xs uppercase tracking-wider border-b border-slate-100">
                        <th scope="col" class="py-3 px-4">#ID</th>
                        <th scope="col" class="py-3 px-4">Kasir / User</th>
                        <th scope="col" class="py-3 px-4">Total Pembayaran</th>
                        <th scope="col" class="py-3 px-4">Metode</th>
                        <th scope="col" class="py-3 px-4">Status</th>
                        <th scope="col" class="py-3 px-4">Waktu Transaksi</th>
                        <th scope="col" class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="penjualan-list" class="text-slate-600 divide-y divide-slate-50">
                    @include('penjualan.partials.table-rows', ['penjualans' => $penjualans])
                </tbody>
            </table>
        </div>

        <div id="pagination-container" class="p-4 border-t border-slate-100 bg-white">
            {{ $penjualans->links() }}
        </div>
    </div>
</div>

<!-- Script Live Search Realtime Otomatis AJAX -->
<script>
    document.getElementById('search-input').addEventListener('input', function() {
        let query = this.value;

        fetch(`{{ route('penjualan.index') }}?search=${encodeURIComponent(query)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('penjualan-list').innerHTML = data.html;
            document.getElementById('total-penjualan').innerText = data.total;
            document.getElementById('pagination-container').innerHTML = data.pagination;
        })
        .catch(error => console.error('Error:', error));
    });
</script>
@endsection