<?php

namespace Database\Seeders;

use App\Models\SecteurActivite;
use App\Models\TypeContrat;
use App\Models\Langue;
use App\Models\Nationalite;
use App\Models\Localisation;
use App\Models\Competence;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Secteurs d'activité (Recherche par libelle)
        $secteurs = [
            ['libelle' => 'Informatique & Digital'],
            ['libelle' => 'Banque & Assurance'],
            ['libelle' => 'BTP & Construction'],
            ['libelle' => 'Commerce & Vente'],
            ['libelle' => 'Hôtellerie & Restauration'],
            ['libelle' => 'Santé & Social'],
            ['libelle' => 'Transport & Logistique'],
            ['libelle' => 'Agriculture & Elevage'],
            ['libelle' => 'Industrie'],
            ['libelle' => 'Enseignement & Formation'],
        ];
        foreach ($secteurs as $s) SecteurActivite::updateOrCreate(['libelle' => $s['libelle']], $s);

        // 2. Types de contrat (Recherche par code)
        $types = [
            ['libelle' => 'CDI', 'code' => 'CDI'],
            ['libelle' => 'CDD', 'code' => 'CDD'],
            ['libelle' => 'Stage', 'code' => 'STAGE'],
            ['libelle' => 'Freelance / Indépendant', 'code' => 'FREE'],
            ['libelle' => 'Intérim', 'code' => 'INT'],
        ];
        foreach ($types as $t) TypeContrat::updateOrCreate(['code' => $t['code']], $t);

        // 3. Langues (Recherche par code_iso)
        $langues = [
            ['libelle' => 'Français', 'code_iso' => 'FR'],
            ['libelle' => 'Anglais', 'code_iso' => 'EN'],
            ['libelle' => 'Espagnol', 'code_iso' => 'ES'],
            ['libelle' => 'Arabe', 'code_iso' => 'AR'],
            ['libelle' => 'Wolof', 'code_iso' => 'WO'],
            ['libelle' => 'Lingala', 'code_iso' => 'LN'],
        ];
        foreach ($langues as $l) Langue::updateOrCreate(['code_iso' => $l['code_iso']], $l);

        // 4. Nationalités (Recherche par code_iso)
        $natios = [
            ['libelle' => 'Sénégalaise', 'code_iso' => 'SN'],
            ['libelle' => 'Congolaise (Brazzaville)', 'code_iso' => 'CG'],
            ['libelle' => 'Congolaise (RDC)', 'code_iso' => 'CD'],
            ['libelle' => 'Ivoirienne', 'code_iso' => 'CI'],
            ['libelle' => 'Gabonaise', 'code_iso' => 'GA'],
            ['libelle' => 'Française', 'code_iso' => 'FR'],
        ];
        foreach ($natios as $n) Nationalite::updateOrCreate(['code_iso' => $n['code_iso']], $n);

        // 5. Localisations (Recherche par ville et pays)
        $locs = [
            ['ville' => 'Dakar', 'pays' => 'Sénégal'],
            ['ville' => 'Brazzaville', 'pays' => 'Congo'],
            ['ville' => 'Pointe-Noire', 'pays' => 'Congo'],
            ['ville' => 'Abidjan', 'pays' => 'Côte d\'Ivoire'],
            ['ville' => 'Kinshasa', 'pays' => 'RDC'],
        ];
        foreach ($locs as $l) Localisation::updateOrCreate(['ville' => $l['ville'], 'pays' => $l['pays']], $l);

        // 6. Compétences (Recherche par libelle)
        $comps = [
            ['libelle' => 'Laravel', 'categorie' => 'Développement'],
            ['libelle' => 'PHP', 'categorie' => 'Développement'],
            ['libelle' => 'JavaScript', 'categorie' => 'Développement'],
            ['libelle' => 'Gestion de projet', 'categorie' => 'Management'],
            ['libelle' => 'Vente directe', 'categorie' => 'Commerce'],
            ['libelle' => 'Comptabilité générale', 'categorie' => 'Finance'],
            ['libelle' => 'Design Graphique', 'categorie' => 'Design'],
        ];
        // 7. Diplômes
        $diplomes = [
            ['libelle' => 'Baccalauréat', 'niveau' => 'Bac', 'specialite' => 'Général'],
            ['libelle' => 'Licence Informatique', 'niveau' => 'Bac+3', 'specialite' => 'Informatique'],
            ['libelle' => 'Master Management', 'niveau' => 'Bac+5', 'specialite' => 'Management'],
            ['libelle' => 'BTS Comptabilité', 'niveau' => 'Bac+2', 'specialite' => 'Finance'],
        ];
        foreach ($diplomes as $d) \App\Models\Diplome::updateOrCreate(['libelle' => $d['libelle']], $d);

        // 8. Qualifications
        $quals = [
            ['intitule' => 'Ingénieur Logiciel'],
            ['intitule' => 'Technicien de Surface'],
            ['intitule' => 'Chef de Chantier'],
            ['intitule' => 'Comptable Senior'],
        ];
        foreach ($quals as $q) \App\Models\Qualification::updateOrCreate(['intitule' => $q['intitule']], $q);
    }
}
