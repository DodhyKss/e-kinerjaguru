<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('nama');
            $table->string('nip', 30)->nullable();
            $table->string('nuptk', 30)->nullable();
            $table->string('mata_pelajaran')->nullable();
            $table->string('kompetensi_keahlian')->nullable();
            $table->string('pangkat_golongan')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('jenis_kelamin', 1)->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
