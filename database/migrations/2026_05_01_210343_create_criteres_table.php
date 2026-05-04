<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('criteres', function (Blueprint $table) {
            $table->id('id_critere');
            $table->string('nom');
            $table->text('description')->nullable();
            $table->integer('poids')->nullable();
            $table->integer('priorite')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('criteres');
    }
};