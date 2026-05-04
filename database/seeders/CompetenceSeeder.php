<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Competence;
use App\Models\Demandeur;
use Illuminate\Support\Facades\DB;

class CompetenceSeeder extends Seeder
{
    public function run(): void
    {
        $competences = [
            'PHP / Laravel',
            'JavaScript / Vue.js',
            'Gestion de projet',
            'SQL / MySQL',
            'UI/UX Design',
            'Communication',
            'Analyse de données',
            'Docker',
        ];

        foreach ($competences as $libelle) {
            $comp = Competence::create(['libelle' => $libelle]);
            
            // Lier au demandeur ID 1 (celui du test)
            DB::table('competence_demandeur')->insert([
                'id_demandeur' => 1,
                'id_competence' => $comp->id_competence,
                'niveau' => rand(1, 5),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
