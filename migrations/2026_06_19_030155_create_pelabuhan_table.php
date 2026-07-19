<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelabuhan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pelabuhan', 10)->unique();
            $table->string('nama_pelabuhan');
            $table->string('kota');
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelabuhan');
    }
};