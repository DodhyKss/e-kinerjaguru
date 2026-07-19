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
        Schema::create('jenis_dokumens', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jenis_dokumen');
            $table->timestamps();
        });

        Schema::table('assessment_aspects', function (Blueprint $table) {
            $table->foreignId('jenis_dokumen_id')->nullable()->constrained('jenis_dokumens')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_aspects', function (Blueprint $table) {
            $table->dropForeign(['jenis_dokumen_id']);
            $table->dropColumn('jenis_dokumen_id');
        });

        Schema::dropIfExists('jenis_dokumens');
    }
};
