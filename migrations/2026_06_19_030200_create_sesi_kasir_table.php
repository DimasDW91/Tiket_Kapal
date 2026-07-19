<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesi_kasir', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kasir_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->dateTime('waktu_buka');
            $table->dateTime('waktu_tutup')->nullable();

            $table->decimal('total_transaksi', 12, 2)->default(0);
            $table->integer('jumlah_transaksi')->default(0);

            $table->text('catatan')->nullable();

            $table->enum('status', ['buka', 'tutup'])->default('buka');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesi_kasir');
    }
};
