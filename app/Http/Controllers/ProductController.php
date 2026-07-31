<?php

namespace App\Http\Controllers;

use App\Models\Produk; 
use App\Models\Jenis;
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

        $products = Produk::with('jenis')
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            $html = '';
            $isAdmin = Auth::user() && Auth::user()->role->name === 'admin';

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
                    $namaJenis = $product->jenis->nama_jenis ?? '-';
                    
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
                        <td class="py-3.5 px-4"><span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-semibold">' . $namaJenis . '</span></td>
                        <td class="py-3.5 px-4 text-slate-600 font-medium">Rp ' . number_format($product->harga_beli ?? 0, 0, ',', '.') . '</td>
                        <td class="py-3.5 px-4 text-slate-600 font-medium">Rp ' . number_format($product->harga_jual ?? 0, 0, ',', '.') . '</td>
                        <td class="py-3.5 px-4"><span class="px-2.5 py-1 rounded-lg text-xs font-semibold ' . $stokClass . '">' . $product->stok . ' Pcs</span></td>
                        <td class="py-3.5 px-4 text-center space-x-2">
                            <a href="' . route('produk.show', $product->id) . '" class="inline-block px-3 py-1.5 bg-sky-500 hover:bg-sky-600 text-white rounded-xl text-xs font-semibold transition">Detail</a>';
                    
                    // Tombol Edit & Hapus hanya muncul untuk Admin
                    if ($isAdmin) {
                        $html .= '<a href="' . route('produk.edit', $product->id) . '" class="inline-block px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-semibold transition">Edit</a>
                            <button type="button" onclick="openDeleteModal(\'' . route('produk.destroy', $product->id) . '\', \'' . $product->nama . '\')" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold transition inline-block">Hapus</button>';
                    }

                    $html .= '</td></tr>';
                }
            } else {
                $html .= '<tr><td colspan="8" class="text-center py-8 text-slate-400 text-xs">Data produk tidak tersedia.</td></tr>';
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
        if (Auth::user()->role->name !== 'admin') {
            return redirect()->route('produk')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk menambah produk.');
        }

        $data_jenis = Jenis::all(); 
        return view('produk.create', compact('data_jenis'));
    }

    public function store(StoreRequest $request)
    {
        if (Auth::user()->role->name !== 'admin') {
            return redirect()->route('produk')->with('error', 'Akses ditolak.');
        }

        $dataReq = $request->validated();

        $data = [
            'user_id'    => Auth::id(),
            'jenis_id'   => $dataReq['jenis_id'] ?? null,
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
        if (Auth::user()->role->name !== 'admin') {
            return redirect()->route('produk')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengubah produk.');
        }

        $data_jenis = Jenis::all(); 
        return view('produk.edit', compact('product', 'data_jenis'));
    }

    public function update(UpdateRequest $request, $id)
    {
        if (Auth::user()->role->name !== 'admin') {
            return redirect()->route('produk')->with('error', 'Akses ditolak.');
        }

        $product = Produk::findOrFail($id);

        $data = [
            'jenis_id'   => $request->jenis_id,
            'nama'       => $request->nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'stok'       => $request->stok,
        ];

        if ($request->hasFile('foto')) {
            if ($product->foto) {
                $oldPath = str_replace('storage/', '', $product->foto);
                Storage::disk('public')->delete($oldPath);
            }

            $data['foto'] = $request->file('foto')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('produk')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        if (Auth::user()->role->name !== 'admin') {
            return redirect()->route('produk')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk menghapus produk.');
        }

        $product = Produk::findOrFail($id);

        $isUsedInTransaction = \Illuminate\Support\Facades\DB::table('item_penjualans')
            ->where('produks_id', $id)
            ->exists();

        if ($isUsedInTransaction) {
            return redirect()->route('produk')->with('error', 'Produk ini tidak dapat dihapus karena sudah tercatat dalam riwayat transaksi penjualan.');
        }

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