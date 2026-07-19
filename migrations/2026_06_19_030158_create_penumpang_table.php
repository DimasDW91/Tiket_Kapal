<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penumpang', function (Blueprint $table) {
            $table->id();

            // nullable: pelanggan walk-in tidak wajib punya akun
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('nik', 20)->unique();
            $table->string('nama_penumpang', 100);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penumpang');
    }
};
