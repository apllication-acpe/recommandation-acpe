<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offre_localisation', function (Blueprint $table) {
            $table->id();
            $table->boolean('est_principale')->default(false);
            $table->boolean('teletravail_possible')->default(false);
            $table->foreignId('id_offre')->constrained('offres', 'id_offre')->onDelete('cascade');
            $table->foreignId('id_localisation')->constrained('localisations', 'id_localisation')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offre_localisation');
    }
};