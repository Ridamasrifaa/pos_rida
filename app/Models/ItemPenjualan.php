<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ItemPenjualan extends Model
{
    use HasFactory;
    protected $table = 'item_penjualans';
    
protected $fillable = ['penjualans_id', 'produks_id', 'kuantitas', 'harga_satuan', 'subtotal'];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualans_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produks_id');
    }
}
