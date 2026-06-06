<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_period_id')->constrained('evaluation_periods')->cascadeOnDelete();
            $table->foreignId('guru_id')->constrained('gurus')->cascadeOnDelete();
            $table->foreignId('penilai_id')->constrained('penilais')->cascadeOnDelete();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['draft', 'in_progress', 'completed', 'approved', 'rejected'])->default('draft');
            $table->decimal('total_skor', 5, 2)->nullable();
            $table->decimal('rata_rata', 3, 2)->nullable();
            $table->text('catatan_penilai')->nullable();
            $table->text('catatan_kepala_sekolah')->nullable();
            $table->timestamps();

            $table->unique(['evaluation_period_id', 'guru_id']);
        });

        Schema::create('evaluation_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('evaluations')->cascadeOnDelete();
            $table->foreignId('indicator_id')->constrained('indicators')->cascadeOnDelete();
            $table->tinyInteger('level_capaian')->nullable();
            $table->text('kesimpulan')->nullable();
            $table->enum('status', ['belum', 'draft', 'selesai'])->default('belum');
            $table->timestamps();

            $table->unique(['evaluation_id', 'indicator_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_results');
        Schema::dropIfExists('evaluations');
    }
};
