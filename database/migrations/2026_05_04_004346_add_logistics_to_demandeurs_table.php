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
        Schema::table('demandeurs', function (Blueprint $table) {
            $table->boolean('permis_b')->default(false);
            $table->boolean('vehicule_personnel')->default(false);
            $table->string('disponibilite')->nullable(); // immediatement, 1_mois, etc.
            $table->boolean('travail_nuit')->default(false);
            $table->boolean('travail_weekend')->default(false);
            $table->integer('mobilite_rayon_km')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demandeurs', function (Blueprint $table) {
            $table->dropColumn([
                'permis_b',
                'vehicule_personnel',
                'disponibilite',
                'travail_nuit',
                'travail_weekend',
                'mobilite_rayon_km'
            ]);
        });
    }
};
