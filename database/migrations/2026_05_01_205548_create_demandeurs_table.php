<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demandeurs', function (Blueprint $table) {
            $table->id('id_demandeur');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();
            $table->date('date_naissance')->nullable();
            $table->foreignId('id_nationalite')->nullable()->constrained('nationalites', 'id_nationalite')->nullOnDelete();
            $table->text('adresse')->nullable();
            $table->string('cv_path')->nullable();
            $table->string('photo_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandeurs');
    }
};