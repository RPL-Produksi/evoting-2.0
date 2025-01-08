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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('fullname');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('unencrypted_password');
            $table->enum('role', ['admin', 'siswa', 'guru', 'caraka'])->default('siswa');
            $table->foreignUuid('kelas_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('profile_picture')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
