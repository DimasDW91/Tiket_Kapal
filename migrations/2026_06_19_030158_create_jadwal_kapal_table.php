<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_kapal', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kapal_id')
                ->constrained('kapal')
                ->cascadeOnDelete();

            $table->foreignId('pelabuhan_asal_id')
                ->constrained('pelabuhan')
                ->cascadeOnDelete();

            $table->foreignId('pelabuhan_tujuan_id')
                ->constrained('pelabuhan')
                ->cascadeOnDelete();

            $table->dateTime('tanggal_berangkat');
            $table->dateTime('tanggal_tiba');

            $table->decimal('harga_tiket', 12, 2);
            $table->integer('kuota');

            $table->enum('status', [
                'tersedia',
                'penuh',
                'dibatalkan'
            ])->default('tersedia');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_kapal');
    }
};