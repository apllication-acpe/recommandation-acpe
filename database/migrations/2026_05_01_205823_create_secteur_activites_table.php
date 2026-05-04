<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('secteur_activites', function (Blueprint $table) {
            $table->id('id_sect_act');
            $table->string('libelle');
            $table->text('code_secteur_description')->nullable();
            $table->string('statut')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('secteur_activites');
    }
};