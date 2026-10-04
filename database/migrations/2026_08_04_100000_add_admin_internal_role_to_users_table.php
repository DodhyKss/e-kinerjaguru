<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan role 'admin_internal' (admin internal sekolah).
     *
     * Catatan: pada MySQL kolom ini berupa ENUM sehingga wajib di-MODIFY.
     * Pada SQLite (dipakai phpunit.xml) enum disimpan sebagai varchar + CHECK,
     * dan ->change() akan membangun ulang tabel dengan definisi baru.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'admin_internal', 'kepala_sekolah', 'penilai', 'guru'])
                ->default('guru')
                ->change();
        });
    }

    public function down(): void
    {
        // Cegah kehilangan data: admin_internal tidak boleh menggantung tanpa role valid.
        DB::table('users')->where('role', 'admin_internal')->update(['role' => 'admin']);

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'kepala_sekolah', 'penilai', 'guru'])
                ->default('guru')
                ->change();
        });
    }
};
