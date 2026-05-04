<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historique_recommandations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_demandeur')->constrained('demandeurs', 'id_demandeur')->onDelete('cascade');
            $table->foreignId('id_offre')->constrained('offres', 'id_offre')->onDelete('cascade');
            $table->float('score_calculated');
            $table->integer('rang');
            $table->boolean('a_ete_clique')->default(false);
            $table->boolean('a_ete_postule')->default(false);
            $table->datetime('date_recommandation');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historique_recommandations');
    }
};