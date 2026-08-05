@extends('layouts.app')

@section('title', 'Manajemen Jenis Produk - POS Rida')

@section('content')
<div class="w-full max-w-6xl mx-auto space-y-6 font-sans py-6 px-4 animate-fadeIn">

    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl shadow-md border border-slate-200 transition-all duration-300 hover:shadow-lg">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-xl shadow-inner transition-transform duration-300 hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Manajemen Jenis Produk</h1>
                <p class="text-sm font-normal text-slate-600 mt-0.5">Daftar seluruh kategori atau jenis produk barang toko.</p>
            </div>
        </div>
        
        <div>
            <a href="{{ route('jenis.create') }}" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-semibold shadow-md shadow-rose-600/30 transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Jenis Baru
            </a>
        </div>
    </div>

    <!-- Tabel Data Jenis Produk -->
    <div class="bg-white rounded-3xl shadow-md border border-slate-200 overflow-hidden transition-all duration-300 hover:shadow-lg">
        <div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white">
            <div>
                <h3 class="font-bold text-lg text-slate-900">Tabel Jenis Produk</h3>
                <span class="text-xs font-semibold text-slate-500">Total: <span class="text-slate-800">{{ $jenis->total() ?? count($jenis) }}</span> Jenis</span>
            </div>
        </div>

        <!-- Wrapper Tabel -->
        <div class="overflow-x-auto p-2 relative">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="text-slate-700 font-semibold text-xs uppercase tracking-wider border-b border-slate-200 bg-white">
                        <th scope="col" class="py-4 px-4 w-16 text-center">No</th>
                        <th scope="col" class="py-4 px-4">Admin / Kasir</th>
                        <th scope="col" class="py-4 px-4">Nama Jenis</th>
                        <th scope="col" class="py-4 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 font-normal divide-y divide-slate-100">
                    @forelse($jenis as $item)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-4 text-center font-normal text-slate-700">
                                {{ $loop->iteration + ($jenis->currentPage() - 1) * $jenis->perPage() }}
                            </td>
                            <td class="py-4 px-4 font-normal text-slate-700">
                                {{ $item->user->name ?? 'Tidak diketahui' }}
                            </td>
                            <td class="py-4 px-4 font-normal text-slate-800">{{ $item->nama_jenis }}</td>
                            <td class="py-4 px-4 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('jenis.edit', $item->id) }}" class="px-3.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-full shadow-xs transition-all active:scale-95">Edit</a>
                                    
                                    <button type="button" onclick="openDeleteModal('delete-form-{{ $item->id }}')" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-full shadow-xs transition-all active:scale-95">Hapus</button>
                                    
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('jenis.destroy', $item->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-500 font-normal">Belum ada data jenis produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 bg-white">
            {{ $jenis->links() }}
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

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/30 backdrop-blur-xs hidden p-4">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-sm w-full shadow-2xl border border-slate-100 text-center space-y-5 transform transition-all">
        <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mx-auto shadow-inner">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
            </svg>
        </div>
        <div>
            <h3 class="font-bold text-lg text-slate-800 tracking-tight">Hapus Jenis Produk?</h3>
            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Tindakan ini tidak dapat dibatalkan. Data yang dihapus akan hilang secara permanen.</p>
        </div>
        
        <div class="grid grid-cols-2 gap-3 pt-2">
            <button type="button" onclick="closeDeleteModal()" class="py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-xs transition active:scale-95">
                Batal
            </button>
            <button type="button" id="confirmDeleteBtn" class="py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl font-bold text-xs shadow-sm transition active:scale-95">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<script>
    let activeFormId = null;

    function openDeleteModal(formId) {
        activeFormId = formId;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        activeFormId = null;
        document.getElementById('deleteModal').classList.add('hidden');
    }

    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (activeFormId) {
            document.getElementById(activeFormId).submit();
        }
    });
</script>
@endsection