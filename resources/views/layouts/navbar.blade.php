<div class="navbar bg-white border-b border-slate-100 shadow-sm px-4 lg:px-8 sticky top-0 z-40">
    <!-- Bagian Kiri (Logo & Menu Mobile) -->
    <div class="navbar-start">
        <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
            </div>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-white rounded-box w-52 border border-slate-100 font-medium">
                <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active bg-rose-50 text-rose-600' : '' }}">Dasbor</a></li>
                
                <!-- Menu Khusus Admin (Mobile) -->
                @if(Auth::user() && Auth::user()->role->name === 'admin')
                    <li><a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active bg-rose-50 text-rose-600' : '' }}">Pengguna</a></li>
                    <li><a href="{{ route('jenis.index') }}" class="{{ request()->routeIs('jenis*') ? 'active bg-rose-50 text-rose-600' : '' }}">Jenis</a></li>
                @endif

                <li><a href="{{ route('produk') }}" class="{{ request()->routeIs('produk*') ? 'active bg-rose-50 text-rose-600' : '' }}">Produk</a></li>
                <li><a href="{{ route('penjualan.index') }}" class="{{ request()->routeIs('penjualan*') ? 'active bg-rose-50 text-rose-600' : '' }}">Penjualan</a></li>
                
                @if(Auth::user() && Auth::user()->role->name === 'admin')
                    <li><a href="{{ route('admin.reports.monthly') }}" class="{{ request()->routeIs('admin.reports*') ? 'active bg-rose-50 text-rose-600' : '' }}">Laporan</a></li>
                @endif

                <!-- Menu Tentang (Mobile) -->
                <li><a href="{{ route('tentang') }}" class="{{ request()->routeIs('tentang*') ? 'active bg-rose-50 text-rose-600' : '' }}">Tentang</a></li>
            </ul>
        </div>
        <a href="{{ route('dashboard') }}" class="font-bold text-lg text-slate-800 tracking-wider">
            TOKO<span class="text-rose-600">GO</span>
        </a>
    </div>

    <!-- Bagian Tengah (Menu Desktop) -->
    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1 gap-1 font-semibold text-sm">
            <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-rose-50 text-rose-600' : 'text-slate-600 hover:bg-slate-50' }}">Dasbor</a></li>

            <!-- Menu Khusus Admin (Desktop) -->
            @if(Auth::user() && Auth::user()->role->name === 'admin')
                <li><a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'bg-rose-50 text-rose-600' : 'text-slate-600 hover:bg-slate-50' }}">Pengguna</a></li>
                <li><a href="{{ route('jenis.index') }}" class="{{ request()->routeIs('jenis*') ? 'bg-rose-50 text-rose-600' : 'text-slate-600 hover:bg-slate-50' }}">Jenis</a></li>
            @endif

            <li><a href="{{ route('produk') }}" class="{{ request()->routeIs('produk*') ? 'bg-rose-50 text-rose-600' : 'text-slate-600 hover:bg-slate-50' }}">Produk</a></li>
            <li><a href="{{ route('penjualan.index') }}" class="{{ request()->routeIs('penjualan*') ? 'bg-rose-50 text-rose-600' : 'text-slate-600 hover:bg-slate-50' }}">Penjualan</a></li>

            @if(Auth::user() && Auth::user()->role->name === 'admin')
                <li><a href="{{ route('admin.reports.monthly') }}" class="{{ request()->routeIs('admin.reports*') ? 'bg-rose-50 text-rose-600' : 'text-slate-600 hover:bg-slate-50' }}">Laporan</a></li>
            @endif

            <!-- Menu Tentang (Desktop) -->
            <li><a href="{{ route('tentang') }}" class="{{ request()->routeIs('tentang*') ? 'bg-rose-50 text-rose-600' : 'text-slate-600 hover:bg-slate-50' }}">Tentang</a></li>
        </ul>
    </div>

    <!-- Bagian Kanan (Profil & Keluar) -->
    <div class="navbar-end gap-3">
        <div class="text-right hidden sm:block">
            <div class="text-xs font-bold text-slate-800">{{ Auth::user()->name ?? 'User' }}</div>
            <div class="text-[10px] text-rose-600 font-semibold uppercase tracking-wider">{{ Auth::user()->role->name ?? '-' }}</div>
        </div>

        <button type="button" onclick="openLogoutModal()" class="btn btn-sm bg-slate-100 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 border-none text-slate-700 font-semibold rounded-xl">
            Keluar
        </button>
    </div>
</div>

<!-- Kartu Modal Konfirmasi Keluar -->
<div id="logoutModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs hidden transition-all duration-300">
    <div class="bg-white w-full max-w-sm p-6 rounded-3xl shadow-xl border border-slate-100 transform scale-95 transition-transform duration-300" id="logoutModalCard">
        <div class="text-center space-y-3">
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mx-auto font-bold text-lg shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                </svg>
            </div>
            <h3 class="font-bold text-lg text-slate-800">Konfirmasi Keluar</h3>
            <p class="text-xs text-slate-500">Apakah kamu yakin ingin keluar dari aplikasi TOKO GO?</p>
        </div>

        <div class="flex items-center gap-3 mt-6">
            <button type="button" onclick="closeLogoutModal()" class="w-1/2 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition">
                Tidak
            </button>
            <form method="POST" action="{{ route('logout') }}" class="w-1/2">
                @csrf
                <button type="submit" class="w-full py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                    Ya, Keluar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openLogoutModal() {
        const modal = document.getElementById('logoutModal');
        const card = document.getElementById('logoutModalCard');
        modal.classList.remove('hidden');
        setTimeout(() => {
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
        }, 10);
    }

    function closeLogoutModal() {
        const modal = document.getElementById('logoutModal');
        const card = document.getElementById('logoutModalCard');
        card.classList.remove('scale-100');
        card.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }
</script>