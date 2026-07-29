<?php

namespace Database\Seeders;

use App\Models\Penjualan;
use App\Models\ItemPenjualan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            Penjualan::factory()
                ->count(50)
                ->create()
                ->each(function ($penjualan) {
                    
                    $items = ItemPenjualan::factory()
                        ->count(rand(1, 5))
                        ->make([
                            'penjualans_id' => $penjualan->id, // <-- Ubah jadi pakai 's'
                        ]);
                        
                    $total = $items->sum('subtotal');
                    
                    $penjualan->itemPenjualans()->saveMany($items); // <-- Ubah jadi pakai 's'
                    
                    $penjualan->update([
                        'total_pembayaran' => $total,
                    ]);
                }); 
        });
    }
}