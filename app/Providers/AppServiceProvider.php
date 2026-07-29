<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider AS ServiceProvider;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use App\Models\User;
use App\Policies\DashboardPolicy;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\ItemPenjualan;
use App\Polcies\PenjualanPolicy;
use App\Polcies\ItemPenjualanPolicy;
use App\Polcies\ProdukPolicy;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class  => DashboardPolicy::class,
        Produk::class => ProdukPolicy::class,
        Penjualan::class => PenjualanPolicy::class,
        ItemPenjualan::class => ItemPenjualanPolicy::class

    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    
        Paginator::useTailwind(); // <-- Aktifkan style Tailwind di sini
           Carbon::setLocale('id');
           $this->registerPolicies();
    }
}
