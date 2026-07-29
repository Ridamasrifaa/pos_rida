<?php

namespace App\Http\Controllers;

use App\Models\Produk; 
use Illuminate\Http\Request;
use App\Http\Requests\Produk\StoreRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Produk\UpdateRequest;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $products = Produk::when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            $html = '';
            if ($products->count() > 0) {
                foreach ($products as $index => $product) {
                    $no = $products->firstItem() + $index;
                    
                    $fotoUrl = null;
                    if ($product->foto) {
                        if (\Illuminate\Support\Str::startsWith($product->foto, ['http://', 'https://'])) {
                            $fotoUrl = $product->foto;
                        } elseif (\Illuminate\Support\Str::startsWith($product->foto, 'storage/')) {
                            $fotoUrl = asset($product->foto);
                        } elseif (\Illuminate\Support\Str::startsWith($product->foto, '/')) {
                            $fotoUrl = asset('storage' . $product->foto);
                        } else {
                            $fotoUrl = asset('storage/' . $product->foto);
                        }
                    }

                    $stokClass = $product->stok > 5 ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600';
                    
                    $html .= '<tr class="hover:bg-slate-50/50 transition">
                        <td class="py-3.5 px-4 font-semibold text-slate-800">' . $no . '</td>
                        <td class="py-3.5 px-4">';
                    if ($fotoUrl) {
                        $html .= '<img src="' . $fotoUrl . '" alt="' . $product->nama . '" class="w-10 h-10 object-cover rounded-xl border border-slate-100 shadow-sm">';
                    } else {
                        $html .= '<div class="w-10 h-10 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center text-xs font-bold">No</div>';
                    }
                    $html .= '</td>
                        <td class="py-3.5 px-4"><div class="font-semibold text-slate-800">' . $product->nama . '</div></td>
                        <td class="py-3.5 px-4 text-slate-600 font-medium">Rp ' . number_format($product->harga_beli ?? 0, 0, ',', '.') . '</td>
                        <td class="py-3.5 px-4 text-slate-600 font-medium">Rp ' . number_format($product->harga_jual ?? 0, 0, ',', '.') . '</td>
                        <td class="py-3.5 px-4"><span class="px-2.5 py-1 rounded-lg text-xs font-semibold ' . $stokClass . '">' . $product->stok . ' Pcs</span></td>
                        <td class="py-3.5 px-4 text-center space-x-2">
                            <a href="' . route('produk.show', $product->id) . '" class="inline-block px-3 py-1.5 bg-sky-500 hover:bg-sky-600 text-white rounded-xl text-xs font-semibold transition">Detail</a>
                            <a href="' . route('produk.edit', $product->id) . '" class="inline-block px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-semibold transition">Edit</a>
                            <form action="' . route('produk.destroy', $product->id) . '" method="POST" class="inline-block" onsubmit="return confirm(\'Apakah Anda yakin akan menghapus produk ini?\');">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold transition">Hapus</button>
                            </form>
                        </td>
                    </tr>';
                }
            } else {
                $html .= '<tr><td colspan="7" class="text-center py-8 text-slate-400 text-xs">Data produk tidak tersedia.</td></tr>';
            }

            return response()->json([
                'html' => $html,
                'total' => $products->total(),
                'pagination' => (string) $products->links()
            ]);
        }

        return view('produk.index', compact('products'));
    }

    public function create()
    {
        return view('produk.create');
    }

    public function store(StoreRequest $request)
    {
        $dataReq = $request->validated();

        $data = [
            'user_id'    => Auth::id(),
            'nama'       => $dataReq['nama'],
            'harga_beli' => $dataReq['harga_beli'],
            'harga_jual' => $dataReq['harga_jual'],
            'stok'       => $dataReq['stok'],
        ];

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('products', 'public');
        }

        Produk::create($data);

        return redirect()
            ->route('produk')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Produk $product)
    {
        return view('produk.edit', compact('product'));
    }

    public function update(UpdateRequest $request, $id)
    {
        $product = Produk::findOrFail($id);

        $data = [
            'nama'       => $request->nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'stok'       => $request->stok,
        ];

        if ($request->hasFile('foto')) {
            // Hapus file foto lama dari storage jika ada
            if ($product->foto) {
                $oldPath = str_replace('storage/', '', $product->foto);
                Storage::disk('public')->delete($oldPath);
            }

            // Simpan foto baru menggunakan store laravel (sama seperti method store)
            $data['foto'] = $request->file('foto')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('produk')->with('success', 'Produk berhasil diperbarui!');
    }

public function destroy(string $id)
    {
        $product = Produk::findOrFail($id);

        // CEK APAKAH PRODUK SUDAH PERNAH MASUK KE TABEL ITEM PENJUALAN
        $isUsedInTransaction = \Illuminate\Support\Facades\DB::table('item_penjualans')
            ->where('produks_id', $id)
            ->exists();

        if ($isUsedInTransaction) {
            return redirect()->route('produk')->with('error', 'Produk ini tidak dapat dihapus karena sudah tercatat dalam riwayat transaksi penjualan.');
        }

        // Hapus file foto terkait jika produk dihapus
        if ($product->foto) {
            $oldPath = str_replace('storage/', '', $product->foto);
            Storage::disk('public')->delete($oldPath);
        }

        $product->delete();

        return redirect()->route('produk')->with('success', 'Produk berhasil dihapus!');
    }

    public function show(Produk $product)
    {
        return view('produk.show', compact('product'));
    }
}