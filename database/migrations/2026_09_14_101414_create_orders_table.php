<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->integer('nomor_antrean');
        $table->string('nama_pemesan');
        $table->text('detail_pesanan');
        $table->integer('total_harga');
        $table->string('metode_pembayaran')->default('Kasir'); // Baris baru
        $table->enum('status', ['Menunggu', 'Dipanggil', 'Selesai'])->default('Menunggu');
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
