@extends('layouts.app')

@section('title', 'Data Penjualan - POS Rida')

@section('content')
<div class="w-full max-w-6xl mx-auto space-y-6 font-sans py-6 px-4 animate-fadeIn">

    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl shadow-md border border-slate-200 transition-all duration-300 hover:shadow-lg">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-xl shadow-inner transition-transform duration-300 hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.66 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.66m-5.801 0c-.37.025-.74.062-1.102.108-1.131.094-1.976 1.057-1.976 2.218v14.132a2.25 2.25 0 002.25 2.25h1.5M6.75 16.5H4.875c-.621 0-1.125-.504-1.125-1.125V4.875c0-.621.504-1.125 1.125-1.125h1.875M6.75 16.5v-10.5m0 10.5h3.75" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Manajemen Penjualan</h1>
                <p class="text-sm font-normal text-slate-600 mt-0.5">Daftar seluruh riwayat transaksi penjualan kasir.</p>
            </div>
        </div>
        
        <div>
            <a href="{{ route('penjualan.create') }}" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-semibold shadow-md shadow-rose-600/30 transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Transaksi Baru
            </a>
        </div>
    </div>

    <!-- Tabel Data Penjualan -->
    <div class="bg-white rounded-3xl shadow-md border border-slate-200 overflow-hidden transition-all duration-300 hover:shadow-lg">
        <div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white">
            <div>
                <h3 class="font-bold text-lg text-slate-900">Riwayat Transaksi</h3>
                <span class="text-xs font-semibold text-slate-500">Total: <span id="total-penjualan" class="text-slate-800">{{ $penjualans->total() ?? 0 }}</span> Transaksi</span>
            </div>

            <!-- Form Pencarian Realtime -->
            <div class="flex items-center w-full sm:w-auto">
                <div class="relative w-full sm:w-72">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </span>
                    <input type="text" id="search-input" value="{{ request('search') }}" placeholder="Cari ID, Kasir, Status..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-300 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-rose-600 focus:ring-2 focus:ring-rose-600/20 transition-all duration-200 shadow-sm">
                </div>
            </div>
        </div>

        <!-- Wrapper Tabel -->
        <div class="overflow-x-auto p-2 relative">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="text-slate-700 font-semibold text-xs uppercase tracking-wider border-b border-slate-200 bg-white">
                        <th scope="col" class="py-4 px-4 w-16">No</th>
                        <th scope="col" class="py-4 px-4">Kasir / User</th>
                        <th scope="col" class="py-4 px-4">Total Pembayaran</th>
                        <th scope="col" class="py-4 px-4">Metode</th>
                        <th scope="col" class="py-4 px-4">Status</th>
                        <th scope="col" class="py-4 px-4">Waktu Transaksi</th>
                        <th scope="col" class="py-4 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="penjualan-list" class="text-slate-700 font-normal divide-y divide-slate-100">
                    @include('penjualan.partials.table-rows', ['penjualans' => $penjualans])
                </tbody>
            </table>
        </div>

        <div id="pagination-container" class="p-4 border-t border-slate-200 bg-white">
            {{ $penjualans->links() }}
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL KONFIRMASI HAPUS PENJUALAN (CARD) -->
<!-- ========================================== -->
<div id="deleteModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4 bg-slate-900/70 hidden">
    <div class="bg-white rounded-3xl p-6 max-w-sm w-full shadow-2xl border border-slate-100 space-y-5 text-center">
        <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xl mx-auto shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
        </div>
        <div class="space-y-2">
            <h3 class="font-bold text-lg text-slate-900">Hapus Transaksi?</h3>
            <p class="text-sm font-normal text-slate-600 leading-relaxed">Apakah Anda yakin ingin menghapus data transaksi ID: <span id="penjualanIdTarget" class="font-semibold text-slate-800"></span>?</p>
        </div>
        <div class="flex items-center gap-2 pt-1">
            <button type="button" onclick="closeDeleteModal()" class="w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-sm font-semibold transition cursor-pointer">
                Batal
            </button>
            <form id="deleteFormTarget" method="POST" class="w-full">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl text-sm font-semibold shadow-md shadow-rose-600/20 transition cursor-pointer">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<!-- Script Live Search & Modal Control -->
<script>
    function openDeleteModal(deleteUrl, penjualanId) {
        document.getElementById('penjualanIdTarget').innerText = '#' + penjualanId;
        document.getElementById('deleteFormTarget').action = deleteUrl;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    function fetchPenjualans(url) {
        let query = document.getElementById('search-input').value;
        let targetUrl = url || "{{ route('penjualan.index') }}?search=" + encodeURIComponent(query);

        if (!targetUrl.includes('search=') && query) {
            let separator = targetUrl.includes('?') ? '&' : '?';
            targetUrl += `${separator}search=${encodeURIComponent(query)}`;
        }

        fetch(targetUrl, {
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
        .catch(error => {
            console.error('Error:', error);
        });
    }

    let searchTimeout;
    document.getElementById('search-input').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            fetchPenjualans();
        }, 300);
    });

    document.addEventListener('click', function(event) {
        let paginationLink = event.target.closest('#pagination-container a');
        if (paginationLink) {
            event.preventDefault();
            let url = paginationLink.href;
            fetchPenjualans(url);
        }
    });
</script>
@endsection