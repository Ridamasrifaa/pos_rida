@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8 font-sans">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-bold text-slate-800">Daftar Jenis Produk</h1>
        <a href="{{ route('jenis.create') }}" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
            Tambah Jenis
        </a>
    </div>

    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-slate-600 uppercase">
                    <th class="p-4 font-bold w-16 text-center">No</th>
                    <th class="p-4 font-bold">Admin/Kasir</th>
                    <th class="p-4 font-bold">Nama Jenis</th>
                    <th class="p-4 font-bold text-center w-36">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
                @forelse($jenis as $item)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-4 text-center font-semibold text-slate-800">
                            {{ $loop->iteration + ($jenis->currentPage() - 1) * $jenis->perPage() }}
                        </td>
                        <td class="p-4 font-medium text-slate-800">
                            {{ $item->user->name ?? 'Tidak diketahui' }}
                        </td>
                        <td class="p-4 font-medium text-slate-800">{{ $item->nama_jenis }}</td>
                        <td class="p-4 text-center">
                            <div class="inline-flex items-center gap-1.5">
                                <a href="{{ route('jenis.edit', $item->id) }}" class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-600 font-semibold rounded-lg transition">Edit</a>
                                
                                <button type="button" onclick="openDeleteModal('delete-form-{{ $item->id }}')" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold rounded-lg transition">Hapus</button>
                                
                                <form id="delete-form-{{ $item->id }}" action="{{ route('jenis.destroy', $item->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-slate-400">Belum ada data jenis produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $jenis->links() }}
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs hidden">
    <div class="bg-white rounded-2xl p-6 max-w-sm w-full mx-4 shadow-xl border border-slate-100 transform transition-all">
        <div class="text-center">
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4 text-lg font-bold">
                !
            </div>
            <h3 class="text-base font-bold text-slate-800 mb-2">Hapus Jenis Produk?</h3>
            <p class="text-xs text-slate-500 mb-6">Tindakan ini tidak dapat dibatalkan. Data yang dihapus akan hilang secara permanen.</p>
            
            <div class="flex items-center justify-center gap-3">
                <button type="button" onclick="closeDeleteModal()" class="w-full px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs transition">
                    Tidak
                </button>
                <button type="button" id="confirmDeleteBtn" class="w-full px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold rounded-xl text-xs transition shadow-sm">
                    Ya, Hapus
                </button>
            </div>
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