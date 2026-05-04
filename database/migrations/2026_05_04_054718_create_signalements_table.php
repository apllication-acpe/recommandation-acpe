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
        Schema::create('signalements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('signalable_type');
            $table->unsignedBigInteger('signalable_id');
            $table->string('motif');
            $table->text('description')->nullable();
            $table->enum('gravite', ['basse', 'moyenne', 'haute'])->default('moyenne');
            $table->enum('statut', ['en_attente', 'traite', 'rejete'])->default('en_attente');
            $table->timestamps();

            $table->index(['signalable_type', 'signalable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signalements');
    }
};
