<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offres', function (Blueprint $table) {
            $table->id('id_offre');
            $table->string('titre');
            $table->text('description');
            $table->text('mission')->nullable();
            $table->text('profil_recherche')->nullable();
            $table->date('date_publication');
            $table->date('date_expiration');
            $table->string('salaire_min')->nullable();
            $table->string('salaire_max')->nullable();
            $table->string('statut_salaire')->nullable(); // brut, net, à négocier
            $table->boolean('active')->default(true);
            $table->foreignId('id_entreprise')->constrained('entreprises', 'id_entreprise')->onDelete('cascade');
            $table->foreignId('id_type_cont')->constrained('type_contrats', 'id_type_cont')->onDelete('cascade');
            $table->foreignId('id_sect_act')->constrained('secteur_activites', 'id_sect_act')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offres');
    }
};