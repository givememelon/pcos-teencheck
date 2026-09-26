<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('menstrual_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('cycle_length_days'); // jarak hari dari hari ke-1 haid sebelumnya
            $table->enum('flow_intensity', ['ringan', 'sedang', 'banyak'])->default('sedang');
            $table->integer('pain_level')->default(0); // 0 - 10
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('menstrual_logs');
    }
};