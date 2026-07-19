<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id();

            $table->string('kode_booking', 20)->unique();

            // nullable: pelanggan walk-in tidak wajib punya akun
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // kasir yang melayani transaksi
            $table->foreignId('kasir_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('jadwal_id')
                ->constrained('jadwal_kapal')
                ->cascadeOnDelete();

            // data pemesan walk-in (tanpa akun)
            $table->string('nama_pemesan', 100)->nullable();
            $table->string('no_hp_pemesan', 20)->nullable();

            // channel transaksi
            $table->enum('channel', ['online', 'kasir'])->default('kasir');

            $table->dateTime('tanggal_pesan');
            $table->integer('jumlah_tiket');
            $table->decimal('total_harga', 12, 2);

            $table->enum('status_pemesanan', [
                'pending',
                'dibayar',
                'dibatalkan',
                'selesai'
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
