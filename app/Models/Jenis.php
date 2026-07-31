<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jenis extends Model
{
    use HasFactory;

    protected $table = 'jenis';
    protected $fillable = ['user_id','nama_jenis'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function produks()
    {
        return $this->hasMany(Produk::class, 'jenis_id');
    }
}