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
        Schema::table('penilais', function (Blueprint $table) {
            $table->foreignId('pangkat_golongan_id')->nullable()->constrained('pangkat_golongans')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilais', function (Blueprint $table) {
            $table->dropForeign(['pangkat_golongan_id']);
            $table->dropColumn('pangkat_golongan_id');
        });
    }
};
