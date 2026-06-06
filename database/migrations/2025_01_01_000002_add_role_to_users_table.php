<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'kepala_sekolah', 'penilai', 'guru'])->default('guru')->after('email');
            $table->foreignId('school_id')->nullable()->after('role')->constrained('schools')->nullOnDelete();
            $table->string('avatar')->nullable()->after('school_id');
            $table->boolean('is_active')->default(true)->after('avatar');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn(['role', 'school_id', 'avatar', 'is_active']);
        });
    }
};
