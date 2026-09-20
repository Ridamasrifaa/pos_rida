@extends('layouts.app')

@section('title', 'Edit Suplier - POS Rida')

@section('content')

<div class="w-full max-w-4xl mx-auto space-y-6 font-sans py-6 px-4 animate-fadeIn">
    <div class="bg-white p-6 rounded-3xl shadow-md border border-slate-200 transition-all duration-300 hover:shadow-lg">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Data Suplier</h1>
            <p class="text-sm font-normal text-slate-600 mt-0.5">Perbarui informasi kontak atau alamat suplier.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-md border border-slate-200 p-8 transition-all duration-300 hover:shadow-lg">
        <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="space-y-2">
                <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">Nama Suplier / Toko / Sales</label>
                <input type="text" name="name" id="name" value="{{ old('name', $supplier->name) }}" required class="w-full px-4 py-3 bg-white border border-slate-300 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-rose-600 focus:ring-2 focus:ring-rose-600/20 transition-all duration-200 shadow-sm">
                @error('name')
                    <span class="text-xs text-rose-600 mt-1 block font-medium">{{ $message }}</span>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="phone" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">Nomor Telepon / WhatsApp</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $supplier->phone) }}" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-rose-600 focus:ring-2 focus:ring-rose-600/20 transition-all duration-200 shadow-sm">
                @error('phone')
                    <span class="text-xs text-rose-600 mt-1 block font-medium">{{ $message }}</span>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="address" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">Alamat Lengkap</label>
                <textarea name="address" id="address" rows="3" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-rose-600 focus:ring-2 focus:ring-rose-600/20 transition-all duration-200 shadow-sm">{{ old('address', $supplier->address) }}</textarea>
                @error('address')
                    <span class="text-xs text-rose-600 mt-1 block font-medium">{{ $message }}</span>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="status" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">Status Suplier</label>
                <select name="status" id="status" required class="w-full px-4 py-3 bg-white border border-slate-300 rounded-2xl text-sm font-medium text-slate-800 focus:outline-none focus:border-rose-600 focus:ring-2 focus:ring-rose-600/20 transition-all duration-200 shadow-sm">
                    <option value="aktif" {{ old('status', $supplier->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="non-aktif" {{ old('status', $supplier->status) == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
                @error('status')
                    <span class="text-xs text-rose-600 mt-1 block font-medium">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="flex items-center gap-3 pt-4">
                <a href="{{ route('suppliers.index') }}" class="w-1/2 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl text-sm font-semibold text-center transition shadow-xs active:scale-95">
                    Batal
                </a>
                <button type="submit" class="w-1/2 py-3 bg-rose-600 hover:bg-rose-700 text-white rounded-2xl text-sm font-semibold shadow-md shadow-rose-600/30 transition active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out forwards;
    }
</style>
@endsection