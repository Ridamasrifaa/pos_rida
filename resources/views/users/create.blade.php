@extends('layouts.app')

@section('title', 'Tambah User Baru - POS Rida')

@section('content')



<div class="w-full max-w-3xl mx-auto space-y-6 font-sans py-6 px-4">

    <!-- Header Halaman -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xl shadow-inner">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">Tambah User Baru</h1>
            <p class="text-sm text-slate-500 mt-0.5">Formulir pendaftaran akun pengguna baru ke dalam sistem.</p>
        </div>
    </div>

    

    <!-- Form Container -->
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @include('users._form')
            
        </form>
    </div>

</div>

@endsection