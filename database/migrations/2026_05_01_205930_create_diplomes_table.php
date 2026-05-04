<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diplomes', function (Blueprint $table) {
            $table->id('id_diplome');
            $table->string('libelle');
            $table->string('niveau'); // Bac, Bac+2, Bac+3, Bac+5, etc.
            $table->string('specialite');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diplomes');
    }
};