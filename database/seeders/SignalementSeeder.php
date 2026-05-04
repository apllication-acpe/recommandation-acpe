<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SignalementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::all();
        $offres = \App\Models\Offre::all();

        if ($users->count() < 2 || $offres->count() == 0) return;

        // Signalement sur une offre
        \App\Models\Signalement::create([
            'user_id' => $users->random()->id,
            'signalable_type' => 'App\Models\Offre',
            'signalable_id' => $offres->first()->id_offre,
            'motif' => 'Arnaque / Offre trompeuse',
            'description' => "Cette offre demande de payer des frais d'inscription, ce qui ressemble à une arnaque au Congo.",
            'gravite' => 'haute',
            'statut' => 'en_attente',
        ]);

        // Signalement sur un utilisateur (Usurpation)
        \App\Models\Signalement::create([
            'user_id' => $users->random()->id,
            'signalable_type' => 'App\Models\User',
            'signalable_id' => $users->where('id', '!=', 1)->first()->id,
            'motif' => 'Usurpation d\'identité',
            'description' => "Ce compte utilise la photo de M. Makaya, un directeur connu à Brazzaville.",
            'gravite' => 'moyenne',
            'statut' => 'en_attente',
        ]);
    }
}
