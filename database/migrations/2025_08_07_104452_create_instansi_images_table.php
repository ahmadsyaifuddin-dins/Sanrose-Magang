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
        Schema::create('instansi_images', function (Blueprint $table) {
            $table->id();
            // onDelete('cascade') akan menghapus gambar jika instansi induknya dihapus
            $table->foreignId('instansi_id')->constrained('instansis')->onDelete('cascade');
            $table->string('path_gambar'); // Path relatif dari folder public
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instansi_images');
    }
};
