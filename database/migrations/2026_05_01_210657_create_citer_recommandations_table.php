<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citer_recommandations', function (Blueprint $table) {
            $table->id();
            $table->string('citer_recherche')->nullable();
            $table->foreignId('id_recommandation')->constrained('recommandations', 'id_recommandation')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citer_recommandations');
    }
};