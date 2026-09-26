<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['pasien', 'dokter'])->default('pasien');
            $table->date('birth_date')->nullable();
            $table->integer('menarche_age')->nullable(); // usia haid pertama
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'birth_date', 'menarche_age']);
        });
    }
};