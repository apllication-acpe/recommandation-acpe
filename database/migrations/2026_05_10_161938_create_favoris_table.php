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
        Schema::create('favoris', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_demandeur');
            $table->unsignedBigInteger('id_offre');
            $table->timestamps();

            $table->foreign('id_demandeur')->references('id_demandeur')->on('demandeurs')->onDelete('cascade');
            $table->foreign('id_offre')->references('id_offre')->on('offres')->onDelete('cascade');
            
            // Un demandeur ne peut liker une offre qu'une seule fois
            $table->unique(['id_demandeur', 'id_offre']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favoris');
    }
};
