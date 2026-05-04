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
        Schema::create('competence_demandeur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_demandeur')->constrained('demandeurs', 'id_demandeur')->onDelete('cascade');
            $table->foreignId('id_competence')->constrained('competences', 'id_competence')->onDelete('cascade');
            $table->string('niveau')->nullable();
            $table->string('certification')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competence_demandeur');
    }
};
