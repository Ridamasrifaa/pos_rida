<?php

namespace Database\Factories;

use App\Models\Produk;
use App\Models\User;
use App\Models\Jenis;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProdukFactory extends Factory
{
    public function definition(): array
    {
        $hargaBeli = $this->faker->numberBetween(10_000, 50_000);

        // Ambil ID dari user acak yang ada di database
        $userId = User::inRandomOrder()->value('id') ?? User::factory();

        // Cari jenis yang ada, atau buat baru jika belum ada
        $jenisId = Jenis::inRandomOrder()->value('id') 
            ?? Jenis::create([
                'nama_jenis' => 'Umum',
                'user_id'    => is_numeric($userId) ? $userId : 1,
            ])->id;

        return [
            'user_id'    => $userId,
            'jenis_id'   => $jenisId,
            'foto'       => '/produk/' . $this->faker->uuid() . '.jpg',
            'nama'       => $this->faker->words(3, true),
            'harga_beli' => $hargaBeli,
            'harga_jual' => $hargaBeli + $this->faker->numberBetween(5_000, 100_000),
            'stok'       => $this->faker->numberBetween(1, 500),
        ];
    }
}