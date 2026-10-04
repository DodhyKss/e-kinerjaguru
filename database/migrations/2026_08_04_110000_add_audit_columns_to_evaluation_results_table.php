<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jejak audit penyuntingan pembuktian kinerja oleh Admin Pusat.
     *
     * updated_by_name disimpan sebagai snapshot agar jejaknya tetap terbaca
     * walaupun akun penyunting dinonaktifkan atau dihapus nanti.
     */
    public function up(): void
    {
        Schema::table('evaluation_results', function (Blueprint $table) {
            $table->foreignId('updated_by_user_id')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->string('updated_by_name')->nullable()->after('updated_by_user_id');
            $table->timestamp('edited_at')->nullable()->after('updated_by_name');
        });
    }

    public function down(): void
    {
        Schema::table('evaluation_results', function (Blueprint $table) {
            $table->dropForeign(['updated_by_user_id']);
            $table->dropColumn(['updated_by_user_id', 'updated_by_name', 'edited_at']);
        });
    }
};
