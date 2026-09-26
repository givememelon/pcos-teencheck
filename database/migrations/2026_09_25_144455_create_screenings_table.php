<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('screenings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('gynecological_age');
            $table->boolean('has_severe_acne')->default(false);
            $table->boolean('has_hirsutism')->default(false);
            $table->boolean('has_hair_thinning')->default(false);
            $table->enum('result_status', ['data_kurang', 'normal', 'pemantauan', 'perlu_tinjauan_dokter']);
            $table->json('explanation_points'); // Explainable AI tags
            $table->text('recommendation');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('screenings');
    }
};