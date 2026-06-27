<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guru_penilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penilai_id')->constrained('penilais')->cascadeOnDelete();
            $table->foreignId('guru_id')->constrained('gurus')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['penilai_id', 'guru_id']);
        });

        // Migrasikan relasi penilai dan guru dari tabel evaluations jika ada
        if (Schema::hasTable('evaluations')) {
            $evaluations = DB::table('evaluations')
                ->select('penilai_id', 'guru_id')
                ->whereNotNull('penilai_id')
                ->whereNotNull('guru_id')
                ->distinct()
                ->get();

            foreach ($evaluations as $eval) {
                DB::table('guru_penilai')->insertOrIgnore([
                    'penilai_id' => $eval->penilai_id,
                    'guru_id' => $eval->guru_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('guru_penilai');
    }
};
