<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recommandations', function (Blueprint $table) {
            $table->id('id_recommandation');
            $table->float('score_final')->nullable();
            $table->integer('rang')->nullable();
            $table->datetime('date_recommandation');
            $table->string('statut')->default('en_attente'); // en_attente, acceptee, refusee
            $table->foreignId('id_offre')->constrained('offres', 'id_offre')->onDelete('cascade');
            $table->foreignId('id_demandeur')->constrained('demandeurs', 'id_demandeur')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommandations');
    }
};