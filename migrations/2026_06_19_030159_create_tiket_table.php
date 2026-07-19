<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tiket', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pemesanan_id')
                ->constrained('pemesanan')
                ->cascadeOnDelete();

            $table->string('kode_tiket', 20)->unique();

            $table->string('qr_code', 255)->nullable();

            $table->enum('status_tiket', [
                'aktif',
                'digunakan',
                'kadaluarsa'
            ])->default('aktif');

            // info cetak tiket fisik oleh kasir
            $table->boolean('dicetak')->default(false);
            $table->dateTime('waktu_cetak')->nullable();
            $table->foreignId('dicetak_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tiket');
    }
};
