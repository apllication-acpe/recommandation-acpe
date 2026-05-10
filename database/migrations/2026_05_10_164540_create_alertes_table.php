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
        Schema::create('alertes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_demandeur');
            $table->string('titre'); // Nom de l'alerte (ex: "Ma veille Dev")
            $table->string('mots_cles')->nullable();
            $table->unsignedBigInteger('id_sect_act')->nullable();
            $table->string('lieu')->nullable();
            $table->unsignedBigInteger('id_type_cont')->nullable();
            $table->enum('frequence', ['immediate', 'quotidienne', 'hebdomadaire'])->default('quotidienne');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('id_demandeur')->references('id_demandeur')->on('demandeurs')->onDelete('cascade');
            $table->foreign('id_sect_act')->references('id_sect_act')->on('secteur_activites')->onDelete('set null');
            $table->foreign('id_type_cont')->references('id_type_cont')->on('type_contrats')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertes');
    }
};
