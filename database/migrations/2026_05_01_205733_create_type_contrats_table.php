<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('type_contrats', function (Blueprint $table) {
            $table->id('id_type_cont');
            $table->string('libelle');
            $table->string('code')->unique();
            $table->string('duree')->nullable();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('type_contrats');
    }
};