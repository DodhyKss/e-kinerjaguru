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
        Schema::table('gurus', function (Blueprint $table) {
            $table->foreignId('mata_pelajaran_id')->nullable()->constrained('mata_pelajarans')->nullOnDelete();
            $table->foreignId('kompetensi_keahlian_id')->nullable()->constrained('kompetensi_keahlians')->nullOnDelete();
            $table->foreignId('pangkat_golongan_id')->nullable()->constrained('pangkat_golongans')->nullOnDelete();
            $table->foreignId('jabatan_fungsional_id')->nullable()->constrained('jabatan_fungsionals')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->dropForeign(['mata_pelajaran_id']);
            $table->dropForeign(['kompetensi_keahlian_id']);
            $table->dropForeign(['pangkat_golongan_id']);
            $table->dropForeign(['jabatan_fungsional_id']);
            $table->dropColumn(['mata_pelajaran_id', 'kompetensi_keahlian_id', 'pangkat_golongan_id', 'jabatan_fungsional_id']);
        });
    }
};
