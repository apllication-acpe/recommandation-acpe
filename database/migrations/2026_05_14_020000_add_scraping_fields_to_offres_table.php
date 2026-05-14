<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offres', function (Blueprint $table) {
            // Identifiant de l'offre sur le site ACPE.CG
            $table->integer('acpe_id')->nullable()->unique()->comment('ID de l\'offre sur acpe.cg');
            // URL source de l'offre
            $table->string('url_source')->nullable()->comment('URL complète sur acpe.cg');
            // Source de l'offre (interne ou scrapée)
            $table->enum('source', ['interne', 'acpe_scraping'])->default('interne');
            // Qualification requise (ex: Frigoriste, Agent de maîtrise)
            $table->string('qualification_requise')->nullable();
            // Département
            $table->string('departement')->nullable();
            // Date du dernier scraping
            $table->timestamp('derniere_synchro')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('offres', function (Blueprint $table) {
            $table->dropColumn([
                'acpe_id',
                'url_source',
                'source',
                'qualification_requise',
                'departement',
                'derniere_synchro',
            ]);
        });
    }
};
