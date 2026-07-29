@extends('layouts.app')

@section('title', 'Edit User - POS Rida')

@section('content')



<div class="w-full max-w-3xl mx-auto space-y-6 font-sans py-6 px-4">

    <!-- Header Halaman -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xl shadow-inner">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">Edit User</h1>
            <p class="text-sm text-slate-500 mt-0.5">Perbarui informasi data akun pengguna di dalam sistem.</p>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
        <!-- Arahkan action ke route update dengan method POST tapi diselipin @method('PUT') -->
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('users._form')
        </form>
    </div>

</div>

@endsection