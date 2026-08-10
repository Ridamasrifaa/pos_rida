<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JenisController extends Controller
{
    public function index()
    {
        
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

        $jenis->update([
            'user_id' => Auth::id(),
            'nama_jenis' => $request->nama_jenis,
        ]);

        return redirect()->route('jenis.index')->with('success', 'Jenis produk berhasil diperbarui!');
    }

    public function destroy(Jenis $jenis)
{
    
    if ($jenis->produks()->count() > 0) {
        return redirect()->route('jenis.index')->with('error', 'Jenis produk tidak bisa dihapus karena masih digunakan oleh produk!');
    }

    $jenis->delete();
    return redirect()->route('jenis.index')->with('success', 'Jenis produk berhasil dihapus!');
}
}