@extends('layouts.app')

@section('title', 'Manajemen Users - POS Rida')

@section('content')
<div class="w-full max-w-6xl mx-auto space-y-6 font-sans py-6 px-4" x-data="usersApp()">

    <!-- Header Halaman & Tombol Tambah -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xl shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-800">Manajemen Users</h1>
                <p class="text-sm text-slate-500 mt-0.5">Daftar seluruh pengguna sistem, hak akses, dan informasi akun.</p>
            </div>
        </div>
        
        <div>
            <a href="{{ route('admin.users.create') }}" class="btn bg-rose-600 hover:bg-rose-700 text-white border-none rounded-xl text-sm font-semibold shadow-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah User Baru
            </a>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl text-sm font-medium">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-5 py-4 rounded-2xl text-sm font-medium">
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Tabel Daftar User dengan Alpine.js -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden space-y-4">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg text-slate-800">Tabel Pengguna</h3>
                <span class="text-xs text-slate-400 font-medium" x-text="'Total: ' + filteredUsers.length + ' Akun'"></span>
            </div>

            <!-- Input Pencarian & Tombol Cari -->
            <div class="flex items-center gap-2">
                <div class="relative w-full sm:w-64">
                    <input type="text" x-model="search" placeholder="Cari nama, email, role..." class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-rose-500 transition">
                </div>
                <button type="button" @click="search = search" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-sm font-semibold transition shrink-0">
                    Cari
                </button>
            </div>
        </div>

        <div class="overflow-x-auto px-6 pb-2">
            <table class="table w-full text-sm">
                <thead>
                    <tr class="text-slate-400 text-xs uppercase tracking-wider border-b border-slate-100">
                        <th scope="col" class="bg-transparent py-3">No</th>
                        <th scope="col" class="bg-transparent py-3">Nama</th>
                        <th scope="col" class="bg-transparent py-3">Email</th>
                        <th scope="col" class="bg-transparent py-3">Role / Hak Akses</th>
                        <th scope="col" class="bg-transparent py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600">
                    <template x-for="(user, index) in filteredUsers" :key="user.id">
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                            <td class="font-semibold text-slate-800" x-text="user.originalIndex"></td>
                            <td>
                                <div class="font-semibold text-slate-800" x-text="user.name"></div>
                            </td>
                            <td class="text-slate-500" x-text="user.email"></td>
                            <td>
                                <template x-if="user.roleName.toLowerCase() === 'admin'">
                                    <span class="badge badge-sm font-semibold text-rose-600 bg-rose-50 border-none px-3 py-2" x-text="user.roleName"></span>
                                </template>
                                <template x-if="user.roleName.toLowerCase() !== 'admin'">
                                    <span class="badge badge-sm font-semibold text-blue-600 bg-blue-50 border-none px-3 py-2" x-text="user.roleName"></span>
                                </template>
                            </td>
                            <td class="text-center space-x-2">
                                <a :href="'{{ url('admin/users/edit') }}/' + user.id" class="btn btn-sm bg-amber-500 hover:bg-amber-600 text-white border-none">Edit Akun</a>

                                <button type="button" @click="openDeleteModal(user)" class="btn btn-sm bg-rose-600 hover:bg-rose-700 text-white border-none">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    </template>
                    <template x-if="filteredUsers.length === 0">
                        <tr>
                            <td colspan="5" class="text-center py-8 text-slate-400 text-xs">Belum ada data pengguna yang terdaftar atau cocok dengan pencarian.</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL CARD KONFIRMASI HAPUS (Teks & Tombol Di Tengah, Tanpa Blur) -->
    <div x-show="showModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/30"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <div class="bg-white w-full max-w-md rounded-3xl shadow-xl border border-slate-100 p-6 space-y-6 text-center transform transition-all"
             @click.away="showModal = false">
            
            <div class="flex flex-col items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Konfirmasi Hapus Akun</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>

            <p class="text-sm text-slate-600">
                Apakah Anda yakin ingin menghapus akun pengguna <strong class="text-slate-800" x-text="selectedUser ? selectedUser.name : ''"></strong>?
            </p>

            <div class="flex items-center justify-center gap-3 pt-2">
                <button type="button" @click="showModal = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition">
                    Batal
                </button>
                
               <form :action="'{{ url('admin/users/delete') }}/' + (selectedUser ? selectedUser.id : '')" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold transition shadow-sm">
                        Ya, Hapus
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>

<!-- Script Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    function usersApp() {
        const rawUsers = @json($users->items());

        return {
            search: '',
            showModal: false,
            selectedUser: null,
            
            openDeleteModal(user) {
                this.selectedUser = user;
                this.showModal = true;
            },

            users: rawUsers.map((user, idx) => {
                let rName = 'Kasir';

                if (user.role && typeof user.role === 'object') {
                    rName = user.role.name || user.role.nama || 'Kasir';
                } else if (user.role_name) {
                    rName = user.role_name;
                }

                let finalName = 'Kasir';
                if (String(rName).toLowerCase().includes('admin')) {
                    finalName = 'Admin';
                } else {
                    finalName = 'Kasir';
                }

                return {
                    ...user,
                    originalIndex: {{ $users->firstItem() ?? 1 }} + idx,
                    roleName: finalName
                };
            }),

            get filteredUsers() {
                if (!this.search || this.search.trim() === '') return this.users;
                let query = this.search.toLowerCase().trim();
                return this.users.filter((user) => {
                    let rowString = (
                        user.originalIndex + ' ' + 
                        (user.name || '') + ' ' + 
                        (user.email || '') + ' ' + 
                        user.roleName
                    ).toLowerCase();

                    return rowString.includes(query);
                });
            }
        }
    }
</script>
@endsection