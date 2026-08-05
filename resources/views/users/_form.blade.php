@csrf

<div class="space-y-5">
    <!-- Input Nama -->
    <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Nama Lengkap</label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" placeholder="Masukkan nama lengkap..." class="w-full px-4 py-2.5 rounded-xl border @error('name') border-rose-500 @else border-slate-200 @enderror bg-slate-50/50 focus:bg-white focus:outline-none focus:border-rose-600 focus:ring-2 focus:ring-rose-600/20 text-sm text-slate-800 transition shadow-sm" required>
        @error('name')
            <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>
        @enderror
    </div>

    <!-- Input Email -->
    <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Alamat Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" placeholder="nama@email.com" class="w-full px-4 py-2.5 rounded-xl border @error('email') border-rose-500 @else border-slate-200 @enderror bg-slate-50/50 focus:bg-white focus:outline-none focus:border-rose-600 focus:ring-2 focus:ring-rose-600/20 text-sm text-slate-800 transition shadow-sm" required>
        @error('email')
            <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>
        @enderror
    </div>

    <!-- Input Password -->
    <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Password</label>
        <input type="password" name="password" placeholder="••••••••" class="w-full px-4 py-2.5 rounded-xl border @error('password') border-rose-500 @else border-slate-200 @enderror bg-slate-50/50 focus:bg-white focus:outline-none focus:border-rose-600 focus:ring-2 focus:ring-rose-600/20 text-sm text-slate-800 transition shadow-sm" {{ isset($user) ? '' : 'required' }}>
        @if(isset($user))
            <span class="text-xs text-slate-400 mt-1 block">Kosongkan jika tidak ingin mengubah password.</span>
        @endif
        @error('password')
            <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>
        @enderror
    </div>

    <!-- Select Role (Admin & Kasir) -->
    <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Role / Hak Akses</label>
        <div class="relative">
            <select name="role_id" class="w-full px-4 py-3 rounded-xl border @error('role_id') border-rose-500 @else border-slate-200 @enderror bg-slate-50/50 focus:bg-white focus:outline-none focus:border-rose-600 focus:ring-2 focus:ring-rose-600/20 text-sm text-slate-800 appearance-none transition cursor-pointer pr-10 shadow-sm" required>
                <option value="" disabled selected class="text-slate-400">Pilih hak akses...</option>
                <option value="1" {{ (old('role_id', $user->role_id ?? '') == 1) ? 'selected' : '' }} class="py-2 text-slate-800 bg-white">Admin</option>
                <option value="2" {{ (old('role_id', $user->role_id ?? '') == 2) ? 'selected' : '' }} class="py-2 text-slate-800 bg-white">Kasir</option>
            </select>
            <!-- Custom Chevron Icon -->
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </div>
        </div>
        @error('role_id')
            <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span>
        @enderror
    </div>

    <!-- Tombol Aksi -->
    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
        <a href="{{ route('admin.users') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition">
            Kembali
        </a>
        <button type="submit" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-semibold shadow-md shadow-rose-600/30 transition">
            Simpan
        </button>
    </div>
</div>