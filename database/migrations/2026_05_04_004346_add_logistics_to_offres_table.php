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
        Schema::table('offres', function (Blueprint $table) {
            $table->boolean('debutant_accepte')->default(false);
            $table->boolean('permis_b_requis')->default(false);
            $table->boolean('vehicule_requis')->default(false);
            $table->boolean('travail_nuit')->default(false);
            $table->boolean('travail_weekend')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offres', function (Blueprint $table) {
            $table->dropColumn([
                'debutant_accepte',
                'permis_b_requis',
                'vehicule_requis',
                'travail_nuit',
                'travail_weekend'
            ]);
        });
    }
};
