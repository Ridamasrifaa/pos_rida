<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory;
    protected $table = 'produks';
    protected $fillable = ['user_id','jenis_id', 'foto', 'nama', 'harga_beli', 'harga_jual', 'stok'];

    public function itemPenjualans()
    {
        return $this->hasMany(ItemPenjualan::class, 'produks_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jenis()
    {
        return $this->belongsTo(Jenis::class, 'jenis_id');
    }
}
