<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Data Observasi
        Schema::create('observation_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_result_id')->constrained('evaluation_results')->cascadeOnDelete();
            $table->foreignId('assessment_aspect_id')->constrained('assessment_aspects')->cascadeOnDelete();
            $table->text('hasil')->nullable();
            $table->timestamps();

            $table->unique(['evaluation_result_id', 'assessment_aspect_id'], 'obs_result_aspect_unique');
        });

        Schema::create('observation_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_result_id')->constrained('evaluation_results')->cascadeOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // Data Telaah Dokumen
        Schema::create('document_review_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_result_id')->constrained('evaluation_results')->cascadeOnDelete();
            $table->foreignId('assessment_aspect_id')->constrained('assessment_aspects')->cascadeOnDelete();
            $table->text('hasil')->nullable();
            $table->timestamps();

            $table->unique(['evaluation_result_id', 'assessment_aspect_id'], 'doc_result_aspect_unique');
        });

        Schema::create('document_review_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_result_id')->constrained('evaluation_results')->cascadeOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // Data Wawancara
        Schema::create('interview_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_result_id')->constrained('evaluation_results')->cascadeOnDelete();
            $table->foreignId('assessment_aspect_id')->constrained('assessment_aspects')->cascadeOnDelete();
            $table->enum('responden', ['kepala_wakil', 'kepala_kompetensi', 'guru', 'siswa']);
            $table->text('hasil')->nullable();
            $table->timestamps();

            $table->unique(['evaluation_result_id', 'assessment_aspect_id', 'responden'], 'int_result_aspect_resp_unique');
        });

        Schema::create('interview_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_result_id')->constrained('evaluation_results')->cascadeOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_notes');
        Schema::dropIfExists('interview_data');
        Schema::dropIfExists('document_review_notes');
        Schema::dropIfExists('document_review_data');
        Schema::dropIfExists('observation_notes');
        Schema::dropIfExists('observation_data');
    }
};
