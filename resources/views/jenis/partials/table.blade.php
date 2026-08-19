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