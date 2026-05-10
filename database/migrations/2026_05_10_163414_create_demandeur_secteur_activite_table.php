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
        Schema::create('demandeur_secteur_activite', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_demandeur');
            $table->unsignedBigInteger('id_sect_act');
            $table->timestamps();

            $table->foreign('id_demandeur')->references('id_demandeur')->on('demandeurs')->onDelete('cascade');
            $table->foreign('id_sect_act')->references('id_sect_act')->on('secteur_activites')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandeur_secteur_activite');
    }
};
