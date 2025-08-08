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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom 'role' setelah kolom 'email'
            // Tipe enum membatasi nilai yang bisa dimasukkan, lebih aman.
            // Default 'maganger' agar user baru yang mendaftar otomatis menjadi maganger.
            $table->enum('role', ['superadmin', 'maganger'])->default('maganger')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Perintah untuk menghapus kolom jika migrasi di-rollback
            $table->dropColumn('role');
        });
    }
};
