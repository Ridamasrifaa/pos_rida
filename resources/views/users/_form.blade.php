@csrf

<div class="space-y-5">
    <!-- Input Nama -->
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" placeholder="Masukkan nama lengkap..." class="input input-bordered w-full rounded-xl bg-slate-50/50 focus:bg-white text-slate-800 @error('name') input-error @enderror" required>
        @error('name')
            <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- Input Email -->
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" placeholder="nama@email.com" class="input input-bordered w-full rounded-xl bg-slate-50/50 focus:bg-white text-slate-800 @error('email') input-error @enderror" required>
        @error('email')
            <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- Input Password -->
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
        <input type="password" name="password" placeholder="••••••••" class="input input-bordered w-full rounded-xl bg-slate-50/50 focus:bg-white text-slate-800 @error('password') input-error @enderror" {{ isset($user) ? '' : 'required' }}>
        @if(isset($user))
            <span class="text-xs text-slate-400 mt-1 block">Kosongkan jika tidak ingin mengubah password.</span>
        @endif
        @error('password')
            <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
        @enderror
    </div>

 <!-- Select Role (Admin & Kasir) -->
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Role / Hak Akses</label>
        <select name="role_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 text-slate-800 text-sm transition @error('role_id') border-rose-500 @enderror" required>
            <option value="" disabled selected>-- Pilih Role --</option>
            <option value="1" {{ (old('role_id', $user->role_id ?? '') == 1) ? 'selected' : '' }}>Admin</option>
            <option value="2" {{ (old('role_id', $user->role_id ?? '') == 2) ? 'selected' : '' }}>Kasir</option>
        </select>
        @error('role_id')
            <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- Tombol Aksi -->
    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
        <button type="submit" class="btn bg-rose-600 hover:bg-rose-700 text-white border-none rounded-xl px-6 font-semibold shadow-sm">
            Simpan
        </button>
        <a href="{{ route('admin.users') }}" class="btn btn-ghost hover:bg-slate-100 text-slate-600 rounded-xl font-semibold">
            Kembali
        </a>
    </div>
</div>