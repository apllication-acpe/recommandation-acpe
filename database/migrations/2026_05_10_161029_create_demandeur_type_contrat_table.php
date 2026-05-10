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
        Schema::create('demandeur_type_contrat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_demandeur');
            $table->unsignedBigInteger('id_type_cont');
            $table->timestamps();

            $table->foreign('id_demandeur')->references('id_demandeur')->on('demandeurs')->onDelete('cascade');
            $table->foreign('id_type_cont')->references('id_type_cont')->on('type_contrats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandeur_type_contrat');
    }
};
