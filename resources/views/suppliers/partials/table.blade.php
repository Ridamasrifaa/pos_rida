@forelse($suppliers as $index => $supplier)
    <tr class="hover:bg-slate-50/50 transition-colors">
        <td class="py-4 px-4 text-center font-normal text-slate-700">
            {{ $suppliers->firstItem() ? $suppliers->firstItem() + $index : $index + 1 }}
        </td>
        <td class="py-4 px-4 font-semibold text-slate-800">{{ $supplier->name }}</td>
        <td class="py-4 px-4 font-normal text-slate-600">{{ $supplier->phone ?? '-' }}</td>
        <td class="py-4 px-4 font-normal text-slate-600">{{ $supplier->address ?? '-' }}</td>
        
        <td class="py-4 px-4">
            @if($supplier->status == 'aktif')
                <span class="px-3 py-1  text-emerald-700 rounded-full text-xs font-semibold">Aktif</span>
            @else
                <span class="px-3 py-1  text-slate-600 rounded-full text-xs font-semibold">Non-Aktif</span>
            @endif
        </td>

        <td class="py-4 px-4 text-center">
            <div class="inline-flex items-center gap-2">
                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="px-3.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-full shadow-xs transition-all active:scale-95">Edit</a>
                
                <!-- Tombol Hapus yang memicu Modal Kustom -->
                <button type="button" 
                    onclick="openDeleteModal('{{ route('suppliers.destroy', $supplier->id) }}', '{{ $supplier->name }}')"
                    class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-full shadow-xs transition-all active:scale-95">
                    Hapus
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="py-8 text-center text-slate-500 font-normal">Belum ada data suplier yang ditemukan.</td>
    </tr>
@endforelse