@forelse ($penjualans as $index => $penjualan)
    <tr class="hover:bg-slate-50/50 transition">
        <!-- Nomor ID Transaksi -->
        <td class="py-3.5 px-4 font-bold text-slate-700">
            {{ $penjualan->id }}
        </td>
        
        <td class="py-3.5 px-4 truncate max-w-[150px]">{{ $penjualan->user->name ?? 'Unknown' }}</td>
        <td class="py-3.5 px-4 font-semibold text-slate-800">Rp {{ number_format($penjualan->total_pembayaran, 0, ',', '.') }}</td>
        <td class="py-3.5 px-4">
            <span class="badge badge-ghost uppercase text-xs font-semibold">{{ $penjualan->metode_pembayaran }}</span>
        </td>
        <td class="py-3.5 px-4">
            <span class="badge {{ strtolower($penjualan->status) == 'completed' || strtolower($penjualan->status) == 'selesai' ? 'badge-success text-white' : 'badge-warning' }} text-xs font-semibold">
                {{ strtoupper($penjualan->status) }}
            </span>
        </td>
        <td class="py-3.5 px-4 text-slate-500 text-xs">{{ $penjualan->created_at->format('d M Y, H:i') }}</td>
        <td class="py-3.5 px-4 text-center">
            <div class="flex items-center justify-center gap-2">
                <a href="{{ route('penjualan.show', $penjualan->id) }}" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition">
                    Detail
                </a>

                @php
                    $isAdmin = auth()->check() && auth()->user()->role && (strtoupper(auth()->user()->role->name) === 'ADMIN' || strtoupper(auth()->user()->role->name) === 'Administrator');
                @endphp

                @if($isAdmin && strtoupper($penjualan->status) === 'OPEN')
                    <a href="{{ route('penjualan.edit', $penjualan->id) }}" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition">
                        Edit
                    </a>

                    <!-- Tombol Hapus memicu Card Modal Kustom -->
                    <button type="button" onclick="openDeleteModal('{{ route('penjualan.destroy', $penjualan->id) }}', '{{ $penjualan->id }}')" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition">
                        Hapus
                    </button>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center py-12 text-slate-400 font-medium text-sm">
            <div class="flex flex-col items-center justify-center space-y-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-slate-300">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <span>Data penjualan tidak tersedia atau tidak ditemukan.</span>
            </div>
        </td>
    </tr>
@endforelse