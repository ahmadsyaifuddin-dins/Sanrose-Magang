<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instansi_images', function (Blueprint $table) {
            // Tambahkan kolom is_default setelah path_gambar, dengan nilai default false
            $table->boolean('is_default')->default(false)->after('path_gambar');
        });
    }

    public function down(): void
    {
        Schema::table('instansi_images', function (Blueprint $table) {
            $table->dropColumn('is_default');
        });
    }
};
