@extends('layouts.app')

@section('title', 'Tambah User Baru - POS Rida')

@section('content')

<div class="w-full max-w-4xl mx-auto space-y-6 font-sans py-6 px-4 animate-fadeIn">

    <!-- Header Halaman -->
    <div class="bg-white p-6 rounded-3xl shadow-md border border-slate-200 transition-all duration-300 hover:shadow-lg">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Tambah User Baru</h1>
            <p class="text-sm font-normal text-slate-600 mt-0.5">Formulir pendaftaran akun pengguna baru ke dalam sistem.</p>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-3xl shadow-md border border-slate-200 p-8 transition-all duration-300 hover:shadow-lg">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @include('users._form')
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