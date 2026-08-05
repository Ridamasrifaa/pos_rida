@extends('layouts.app')



@section('title', 'Manajemen Users - POS Rida')



@section('content')

<div class="w-full max-w-6xl mx-auto space-y-6 font-sans py-6 px-4 animate-fadeIn relative" x-data="usersApp()">



    <!-- Header Halaman & Tombol Tambah -->

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl shadow-md border border-slate-200 transition-all duration-300 hover:shadow-lg">

        <div class="flex items-center gap-4">

            <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-xl shadow-inner transition-transform duration-300 hover:scale-105">

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">

                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />

                </svg>

            </div>

            <div>

                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Manajemen Users</h1>

                <p class="text-sm font-normal text-slate-600 mt-0.5">Daftar seluruh pengguna sistem, hak akses, dan informasi akun.</p>

            </div>

        </div>

       

        <div>

            <a href="{{ route('admin.users.create') }}" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-semibold shadow-md shadow-rose-600/30 transition-all duration-200 hover:-translate-y-0.5 flex items-center gap-2">

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">

                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />

                </svg>

                Tambah User Baru

            </a>

        </div>

    </div>



 



    <!-- Tabel Daftar User dengan Alpine.js -->

    <div class="bg-white rounded-3xl shadow-md border border-slate-200 overflow-hidden transition-all duration-300 hover:shadow-lg">

        <div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white">

            <div>

                <h3 class="font-bold text-lg text-slate-900">Tabel Pengguna</h3>

                <span class="text-xs font-semibold text-slate-500" x-text="'Total: ' + filteredUsers.length + ' Akun'"></span>

            </div>



            <!-- Input Pencarian -->

            <div class="flex items-center w-full sm:w-auto">

                <div class="relative w-full sm:w-72">

                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">

                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />

                        </svg>

                    </span>

                    <input type="text" x-model="search" placeholder="Cari nama, email, role..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-300 rounded-2xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:border-rose-600 focus:ring-2 focus:ring-rose-600/20 transition-all duration-200 shadow-sm">

                </div>

            </div>

        </div>



        <div class="overflow-x-auto p-2">

            <table class="w-full text-sm text-left">

                <thead>

                    <tr class="text-slate-700 font-semibold text-xs uppercase tracking-wider border-b border-slate-200 bg-white">

                        <th scope="col" class="py-4 px-4 w-16 text-center">No</th>

                        <th scope="col" class="py-4 px-4">Nama</th>

                        <th scope="col" class="py-4 px-4">Email</th>

                        <th scope="col" class="py-4 px-4">Role / Hak Akses</th>

                        <th scope="col" class="py-4 px-4 text-center">Aksi</th>

                    </tr>

                </thead>

                <tbody class="text-slate-700 font-normal divide-y divide-slate-100">

                    <template x-for="(user, index) in filteredUsers" :key="user.id">

                        <tr class="transition-colors duration-150 hover:bg-slate-50/50">

                            <td class="py-4 px-4 text-center font-normal text-slate-700" x-text="user.originalIndex"></td>

                            <td class="py-4 px-4">

                                <div class="font-normal text-slate-800" x-text="user.name"></div>

                            </td>

                            <td class="py-4 px-4 text-slate-700 font-normal" x-text="user.email"></td>

                            <td class="py-4 px-4">

                                <template x-if="user.roleName.toLowerCase() === 'admin'">

                                    <span class="inline-block px-3 py-1 font-semibold text-rose-800 bg-rose-100 rounded-xl text-xs whitespace-nowrap" x-text="user.roleName"></span>

                                </template>

                                <template x-if="user.roleName.toLowerCase() !== 'admin'">

                                    <span class="inline-block px-3 py-1 font-semibold text-blue-800 bg-blue-100 rounded-xl text-xs whitespace-nowrap" x-text="user.roleName"></span>

                                </template>

                            </td>

                            <td class="py-4 px-4 text-center whitespace-nowrap">

                                <div class="inline-flex items-center justify-center gap-2">

                                    <a :href="'{{ url('admin/users/edit') }}/' + user.id" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition">Edit</a>



                                    <button type="button" @click="openDeleteModal(user)" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition cursor-pointer">

                                        Hapus

                                    </button>

                                </div>

                            </td>

                        </tr>

                    </template>

                    <template x-if="filteredUsers.length === 0">

                        <tr>

                            <td colspan="5" class="text-center py-12 text-slate-400 font-medium text-sm">

                                <div class="flex flex-col items-center justify-center space-y-2">

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-slate-300">

                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />

                                    </svg>

                                    <span>Belum ada data pengguna yang terdaftar atau cocok dengan pencarian.</span>

                                </div>

                            </td>

                        </tr>

                    </template>

                </tbody>

            </table>

        </div>

    </div>



    <!-- MODAL CARD BERTAHAP (DI POSISIKAN PAS DI TENGAH LAYAR) -->

    <template x-teleport="body">

        <div x-show="showModal"

             class="fixed inset-0 z-[999999] grid place-items-center p-4 bg-slate-900/40 backdrop-blur-xs"

             style="display: none;">

           

            <div class="bg-white rounded-3xl p-8 max-w-sm w-full shadow-2xl border border-slate-100 space-y-6 text-center transform transition-all animate-fadeIn"

                 @click.away="closeModal()">

               

                <!-- TAHAP 1: KONFIRMASI AWAL HAPUS -->

                <template x-if="step === 'confirm'">

                    <div class="space-y-6">

                        <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold mx-auto shadow-sm">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">

                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />

                            </svg>

                        </div>

                       

                        <div class="space-y-2">

                            <h3 class="font-bold text-lg text-slate-900">Hapus Akun?</h3>

                            <p class="text-xs font-normal text-slate-600 leading-relaxed px-2">

                                Apakah Anda yakin ingin menghapus akun <span class="font-semibold text-slate-800" x-text="selectedUser ? selectedUser.name : ''"></span>?

                            </p>

                        </div>



                        <!-- Tombol Batal & Ya Hapus -->

                        <div class="flex items-center justify-center gap-2.5 w-full pt-1">

                            <button type="button" @click="closeModal()" class="px-5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition cursor-pointer">

                                Batal

                            </button>

                           

                            <form :action="'{{ url('admin/users/delete') }}/' + (selectedUser ? selectedUser.id : '')" method="POST" class="inline-block">

                                @csrf

                                @method('DELETE')

                                <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold shadow-sm transition cursor-pointer">

                                    Ya, Hapus

                                </button>

                            </form>

                        </div>

                    </div>

                </template>



                <!-- TAHAP 2: CARD PERINGATAN (RESTRIKSI) -->

                <template x-if="step === 'restricted'">

                    <div class="space-y-6">

                        <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center font-bold mx-auto shadow-sm">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">

                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />

                            </svg>

                        </div>

                       

                        <div class="space-y-2">

                            <h3 class="font-bold text-lg text-slate-900">Tidak Dapat Menghapus</h3>

                            <p class="text-xs font-normal text-slate-600 leading-relaxed px-2">

                                User tidak dapat dihapus karena memiliki riwayat transaksi!

                            </p>

                        </div>



                        <div class="pt-1">

                            <button type="button" @click="closeModal()" class="w-full py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold shadow-sm transition cursor-pointer">

                                Mengerti

                            </button>

                        </div>

                    </div>

                </template>



            </div>

        </div>

    </template>



</div>



<style>

    @keyframes fadeIn {

        from { opacity: 0; transform: translateY(10px); }

        to { opacity: 1; transform: translateY(0); }

    }

    .animate-fadeIn {

        animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;

    }

</style>



<!-- Script Alpine.js -->

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>

    function usersApp() {

        const rawUsers = @json($users->items());

        const hasSessionError = @json(session()->has('error'));



        return {

            search: '',

            showModal: hasSessionError,

            selectedUser: null,

            step: hasSessionError ? 'restricted' : 'confirm',

           

            openDeleteModal(user) {

                this.selectedUser = user;

                this.step = 'confirm';

                this.showModal = true;

            },



            closeModal() {

                this.showModal = false;

                setTimeout(() => {

                    this.step = 'confirm';

                }, 200);

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