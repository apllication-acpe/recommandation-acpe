<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('critere_demandeur', function (Blueprint $table) {
            $table->id();
            $table->float('score')->nullable();
            $table->text('justification')->nullable();
            $table->foreignId('id_demandeur')->constrained('demandeurs', 'id_demandeur')->onDelete('cascade');
            $table->foreignId('id_critere')->constrained('criteres', 'id_critere')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('critere_demandeur');
    }
};