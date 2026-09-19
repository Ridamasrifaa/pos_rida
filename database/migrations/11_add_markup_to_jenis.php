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
        Schema::table('jenis', function (Blueprint $table) {
            $table->decimal('markup_percentage', 5, 2)->default(15.00)->after('nama_jenis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis', function (Blueprint $table) {
            $table->dropColumn('markup_percentage');
        });
    }
};
