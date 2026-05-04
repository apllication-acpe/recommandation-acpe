<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entreprises', function (Blueprint $table) {
            $table->id('id_entreprise');
            $table->string('raison_sociale');
            $table->string('forme_juridique')->nullable();
            $table->string('taille')->nullable();
            $table->text('adresse')->nullable();
            $table->string('email_contact')->nullable();
            $table->string('telephone')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('site_web')->nullable();
            $table->boolean('verifiee')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entreprises');
    }
};