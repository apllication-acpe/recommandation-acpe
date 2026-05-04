<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('langue_offre', function (Blueprint $table) {
            $table->id();
            $table->string('niveau_exige')->nullable(); // A1, A2, B1, B2, C1, C2, Natif
            $table->integer('poids')->nullable();
            $table->boolean('obligatoire')->default(false);
            $table->foreignId('id_offre')->constrained('offres', 'id_offre')->onDelete('cascade');
            $table->foreignId('id_langue')->constrained('langues', 'id_langue')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('langue_offre');
    }
};