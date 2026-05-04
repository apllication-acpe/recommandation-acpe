<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competence_offre', function (Blueprint $table) {
            $table->id();
            $table->integer('poids')->nullable();
            $table->boolean('obligatoire')->default(false);
            $table->integer('niveau_minimum')->nullable(); // 1 à 5
            $table->foreignId('id_offre')->constrained('offres', 'id_offre')->onDelete('cascade');
            $table->foreignId('id_competence')->constrained('competences', 'id_competence')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competence_offre');
    }
};