<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JenisController extends Controller
{
    public function index()
    {
        // Muat relasi user agar bisa menampilkan nama/info admin atau kasir
        $jenis = Jenis::with('user')->latest()->paginate(10);
        return view('jenis.index', compact('jenis'));
    }

    public function create()
    {
        return view('jenis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:jenis,nama_jenis',
        ]);

        // Tambahkan user_id menggunakan ID user yang sedang login
        Jenis::create([
            'user_id' => Auth::id(),
            'nama_jenis' => $request->nama_jenis,
        ]);

        return redirect()->route('jenis.index')->with('success', 'Jenis produk berhasil ditambahkan!');
    }

    public function edit(Jenis $jenis)
    {
        return view('jenis.edit', compact('jenis'));
    }

    public function update(Request $request, Jenis $jenis)
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:jenis,nama_jenis,' . $jenis->id,
        ]);

        // Perbarui data termasuk user_id jika ingin diubah ke user yang sedang login, atau biarkan tetap
        $jenis->update([
            'user_id' => Auth::id(),
            'nama_jenis' => $request->nama_jenis,
        ]);

        return redirect()->route('jenis.index')->with('success', 'Jenis produk berhasil diperbarui!');
    }

    public function destroy(Jenis $jenis)
    {
        $jenis->delete();

        return redirect()->route('jenis.index')->with('success', 'Jenis produk berhasil dihapus!');
    }
}