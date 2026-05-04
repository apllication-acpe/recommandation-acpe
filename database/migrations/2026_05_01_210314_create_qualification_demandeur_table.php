<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qualification_demandeur', function (Blueprint $table) {
            $table->id();
            $table->date('date_obtention')->nullable();
            $table->string('organisme')->nullable();
            $table->string('niveau_atteint')->nullable();
            $table->date('date_expiration')->nullable();
            $table->string('numero_reference')->nullable();
            $table->foreignId('id_demandeur')->constrained('demandeurs', 'id_demandeur')->onDelete('cascade');
            $table->foreignId('id_qualification')->constrained('qualifications', 'id_qualification')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qualification_demandeur');
    }
};