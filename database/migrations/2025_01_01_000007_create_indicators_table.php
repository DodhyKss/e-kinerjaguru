<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dimension_id')->constrained('dimensions')->cascadeOnDelete();
            $table->string('kode', 10);
            $table->string('nama');
            $table->text('deskripsi');
            $table->integer('urutan');
            $table->boolean('has_observasi')->default(true);
            $table->boolean('has_telaah_dokumen')->default(true);
            $table->boolean('has_wawancara')->default(true);
            $table->timestamps();

            $table->unique(['dimension_id', 'kode']);
        });

        Schema::create('achievement_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indicator_id')->constrained('indicators')->cascadeOnDelete();
            $table->tinyInteger('level');
            $table->text('deskripsi');
            $table->timestamps();

            $table->unique(['indicator_id', 'level']);
        });

        Schema::create('assessment_aspects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indicator_id')->constrained('indicators')->cascadeOnDelete();
            $table->enum('metode', ['observasi', 'telaah_dokumen', 'wawancara']);
            $table->tinyInteger('nomor');
            $table->text('aspek');
            $table->string('nama_dokumen')->nullable();
            $table->timestamps();

            $table->unique(['indicator_id', 'metode', 'nomor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_aspects');
        Schema::dropIfExists('achievement_levels');
        Schema::dropIfExists('indicators');
    }
};
