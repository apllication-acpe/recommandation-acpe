<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appariements', function (Blueprint $table) {
            $table->id();
            $table->enum('statut', ['en_attente', 'valide', 'rejete'])->default('en_attente');
            $table->date('date_appariement');
            $table->text('commentaire')->nullable();
            $table->foreignId('id_offre')->constrained('offres', 'id_offre')->onDelete('cascade');
            $table->foreignId('id_demandeur')->constrained('demandeurs', 'id_demandeur')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appariements');
    }
};