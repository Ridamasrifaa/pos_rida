<?php

namespace App\Http\Controllers;

use App\Models\Produk; 
use App\Models\Jenis;
use Illuminate\Http\Request;
use App\Http\Requests\Produk\StoreRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Produk\UpdateRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $products = Produk::with('jenis', 'user')
            ->when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%");
            })
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->appends(['search' => $search]);
            
        if ($request->ajax()) {
            $html = '';
            $isAdmin = Auth::user() && Auth::user()->role->name === 'admin';

            if ($products->count() > 0) {
                foreach ($products as $index => $product) {
                    // Menghitung nomor urut asli di database agar posisi nomor produk tetap konsisten meski di-search
                    $no = Produk::where('id', '<=', $product->id)->count();
                    
                    $fotoUrl = null;
                    if ($product->foto) {
                        if (Str::startsWith($product->foto, ['http://', 'https://'])) {
                            $fotoUrl = $product->foto;
                        } elseif (Str::startsWith($product->foto, 'storage/')) {
                            $fotoUrl = asset($product->foto);
                        } elseif (Str::startsWith($product->foto, '/')) {
                            $fotoUrl = asset('storage' . $product->foto);
                        } else {
                            $fotoUrl = asset('storage/' . $product->foto);
                        }
                    }

                    $stokClass = $product->stok > 5 ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800';
                    $namaJenis = $product->jenis->nama_jenis ?? '-';
                    $namaUser = $product->user->name ?? 'Admin';
                    
                    $html .= '<tr class="transition-colors duration-150 hover:bg-rose-50/40">
                        <td class="py-4 px-4 font-semibold text-slate-900">' . $no . '</td>
                        <td class="py-4 px-4 min-w-[100px]">';
                    
                    if ($fotoUrl) {
                        $html .= '<img src="' . $fotoUrl . '" alt="' . $product->nama . '" class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-2xl border border-slate-200 shadow-sm flex-shrink-0">';
                    } else {
                        $html .= '<div class="w-16 h-16 sm:w-20 sm:h-20 bg-slate-100 text-slate-500 rounded-2xl flex items-center justify-center text-xs font-bold border border-slate-200 flex-shrink-0">No</div>';
                    }
                    
                    $html .= '</td>
                        <td class="py-4 px-4"><div class="font-semibold text-slate-900">' . $product->nama . '</div></td>
                        <td class="py-4 px-4"><span class="inline-block px-3 py-1 bg-slate-100 text-slate-800 rounded-xl text-xs font-semibold whitespace-nowrap">' . $namaJenis . '</span></td>
                        <td class="py-4 px-4"><span class="font-medium text-slate-800">' . $namaUser . '</span></td>
                        <td class="py-4 px-4 text-slate-700 font-medium whitespace-nowrap">Rp ' . number_format($product->harga_beli ?? 0, 0, ',', '.') . '</td>
                        <td class="py-4 px-4 text-slate-900 font-semibold whitespace-nowrap">Rp ' . number_format($product->harga_jual ?? 0, 0, ',', '.') . '</td>
                        <td class="py-4 px-4 whitespace-nowrap"><span class="inline-block px-3 py-1 rounded-xl text-xs font-semibold ' . $stokClass . '">' . $product->stok . ' Pcs</span></td>
                        <td class="py-4 px-4 text-center space-x-1.5 whitespace-nowrap">
                            <a href="' . route('produk.show', $product->id) . '" class="inline-block px-3.5 py-1.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">Detail</a>';
                    
                    if ($isAdmin) {
                        $html .= '<a href="' . route('produk.edit', $product->id) . '" class="inline-block px-3.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-semibold shadow-sm transition">Edit</a>
                            <button type="button" onclick="openDeleteModal(\'' . route('produk.destroy', $product->id) . '\', \'' . $product->nama . '\')" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold shadow-sm transition inline-block">Hapus</button>';
                    }

                    $html .= '</td></tr>';
                }
            } else {
                $html .= '<tr><td colspan="9" class="text-center py-10 text-slate-500 font-normal text-sm">Data produk tidak tersedia.</td></tr>';
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

 public function store(Request $request)
{
    // Validasi input dengan 'foto' wajib diisi (required)
    $request->validate([
        'nama' => 'required|string|max:255',
        'jenis_id' => 'required|exists:jenis,id',
        'harga_beli' => 'required|numeric|min:0',
        'harga_jual' => 'required|numeric|min:0',
        'stok' => 'required|integer|min:0',
        'foto' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Wajib diisi & harus gambar
    ], [
        'foto.required' => 'Foto produk wajib diunggah!',
        'foto.image' => 'File harus berupa gambar.',
        'foto.mimes' => 'Format foto harus berjenis: jpeg, png, jpg, atau webp.',
        'foto.max' => 'Ukuran foto maksimal adalah 2MB.',
    ]);

    $data = $request->except('foto');
    $data['user_id'] = Auth::id();

    // Karena sudah divalidasi 'required', bagian ini pasti ada filenya
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
            'user_id'    => Auth::id(), 
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