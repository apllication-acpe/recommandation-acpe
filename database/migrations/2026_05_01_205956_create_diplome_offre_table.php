<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diplome_offre', function (Blueprint $table) {
            $table->id();
            $table->boolean('obligatoire');
            $table->integer('poids');
            $table->foreignId('id_offre')->constrained('offres', 'id_offre')->onDelete('cascade');
            $table->foreignId('id_diplome')->constrained('diplomes', 'id_diplome')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diplome_offre');
    }
};