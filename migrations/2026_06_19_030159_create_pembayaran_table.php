<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pemesanan_id')
                ->constrained('pemesanan')
                ->cascadeOnDelete();

            // metode pembayaran offline: tunai, debit, QRIS
            $table->enum('metode_pembayaran', [
                'tunai',
                'debit',
                'kredit',
                'qris',
                'transfer', // tetap ada untuk kompatibilitas online
            ]);

            $table->decimal('jumlah_bayar', 12, 2);

            // khusus transaksi tunai
            $table->decimal('jumlah_diterima', 12, 2)->nullable();
            $table->decimal('kembalian', 12, 2)->nullable();

            $table->string('nomor_struk', 50)->nullable();

            $table->dateTime('tanggal_bayar')->nullable();

            // bukti upload (online) atau null (kasir/tunai)
            $table->string('bukti_pembayaran', 255)->nullable();

            // status disederhanakan untuk kasir: langsung lunas di tempat
            $table->enum('status_pembayaran', [
                'menunggu',
                'lunas',
                'batal',
            ])->default('menunggu');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
