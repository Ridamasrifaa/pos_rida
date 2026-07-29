<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('item_penjualans', function (Blueprint $table) {
    $table->id();
$table->foreignId('penjualans_id')->constrained('penjualans', 'id');
$table->foreignId('produks_id')->constrained('produks', 'id');
$table->integer('kuantitas');
$table->integer('harga_satuan');
$table->integer('subtotal');
$table->timestamps();
});
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_penjualans');
    }
};
