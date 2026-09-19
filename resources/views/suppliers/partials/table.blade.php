@forelse($suppliers as $index => $supplier)
    <tr class="hover:bg-slate-50/50 transition-colors">
        <td class="py-4 px-4 text-center font-normal text-slate-700">
            {{ $index + 1 }}
        </td>
        <td class="py-4 px-4 font-semibold text-slate-800">{{ $supplier->name }}</td>
        <td class="py-4 px-4 font-normal text-slate-600">{{ $supplier->phone ?? '-' }}</td>
        <td class="py-4 px-4 font-normal text-slate-600">{{ $supplier->address ?? '-' }}</td>
        <td class="py-4 px-4 text-center">
            <div class="inline-flex items-center gap-2">
                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="px-3.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-full shadow-xs transition-all active:scale-95">Edit</a>
                
                <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus suplier ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-full shadow-xs transition-all active:scale-95">Hapus</button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="py-8 text-center text-slate-500 font-normal">Belum ada data suplier yang ditemukan.</td>
    </tr>
@endforelse