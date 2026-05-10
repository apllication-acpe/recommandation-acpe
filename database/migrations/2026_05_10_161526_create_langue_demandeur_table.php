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
        Schema::create('langue_demandeur', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_demandeur');
            $table->unsignedBigInteger('id_langue');
            $table->string('niveau')->nullable(); // Ex: Débutant, Intermédiaire, Avancé
            $table->timestamps();

            $table->foreign('id_demandeur')->references('id_demandeur')->on('demandeurs')->onDelete('cascade');
            $table->foreign('id_langue')->references('id_langue')->on('langues')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('langue_demandeur');
    }
};
