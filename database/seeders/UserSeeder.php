<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Entreprise;

use App\Models\Demandeur;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // On s'assure que le mot de passe par défaut est le même pour faciliter les tests
        $password = bcrypt('password');

        // 1. Utilisateur Admin (S'il n'a pas déjà été créé par RolePermissionSeeder)
        $admin = User::firstOrCreate(
            ['email' => 'admin@acpe.test'],
            [
                'nom' => 'Admin',
                'prenom' => 'ACPE',
                'password' => $password,
                'telephone' => '0000000000',
            ]
        );
        $admin->assignRole('admin');



        // 4. Utilisateur Candidat (Demandeur)
        $candidatUser = User::firstOrCreate(
            ['email' => 'candidat@acpe.test'],
            [
                'nom' => 'Martin',
                'prenom' => 'Sophie',
                'password' => $password,
                'telephone' => '0303030303',
            ]
        );
        $candidatUser->assignRole('demandeur');

        // Créer le profil demandeur
        $demandeur = Demandeur::firstOrCreate(
            ['user_id' => $candidatUser->id],
            [
                'date_naissance' => '1995-05-15',
                'adresse' => '456 Avenue de l\'Emploi, Libreville',
                'disponibilite' => 'immediatement',
                'permis_b' => true,
                'vehicule_personnel' => false,
                'travail_nuit' => true,
                'travail_weekend' => false,
                'mobilite_rayon_km' => 50,
            ]
        );

        // Seed basic qualifications / experiences / competences
        if ($demandeur->experiences()->count() == 0) {
            $demandeur->experiences()->create([
                'poste_occupe' => 'Développeur Web Laravel',
                'entreprise' => 'Tech Solutions',
                'description' => 'Développement d\'applications web avec Laravel et Vue.js.',
                'date_debut' => now()->subYears(3),
                'date_fin' => now()->subMonths(2),
            ]);
        }

        // Création de secteurs et types de contrat
        $secteurInfo = \App\Models\SecteurActivite::firstOrCreate(['libelle' => 'Informatique & Tech']);
        $secteurVente = \App\Models\SecteurActivite::firstOrCreate(['libelle' => 'Vente & Commerce']);
        
        $typeCDI = \App\Models\TypeContrat::firstOrCreate(['libelle' => 'Contrat à Durée Indéterminée', 'code' => 'CDI']);
        $typeCDD = \App\Models\TypeContrat::firstOrCreate(['libelle' => 'Contrat à Durée Déterminée', 'code' => 'CDD']);

        // Création d'une entreprise
        $entreprise = Entreprise::firstOrCreate(
            ['email_contact' => 'contact@techsolutions.com'],
            [
                'raison_sociale' => 'Tech Solutions',
                'telephone' => '0102030405',
                'verifiee' => true,
            ]
        );

        // Création d'offres
        if (\App\Models\Offre::count() == 0) {
            // Offre 1: Match parfait (Logistique & Pro)
            \App\Models\Offre::create([
                'id_entreprise' => $entreprise->id_entreprise,
                'id_sect_act' => $secteurInfo->id_sect_act,
                'id_type_cont' => $typeCDI->id_type_cont,
                'titre' => 'Développeur Fullstack Junior',
                'description' => 'Nous recherchons un dev Laravel. Travail de nuit occasionnel.',
                'salaire_min' => 500000,
                'date_publication' => now(),
                'date_expiration' => now()->addDays(30),
                'active' => true,
                'debutant_accepte' => true,
                'permis_b_requis' => false,
                'vehicule_requis' => false,
                'travail_nuit' => true,
                'travail_weekend' => false,
            ]);

            // Offre 2: Match partiel
            \App\Models\Offre::create([
                'id_entreprise' => $entreprise->id_entreprise,
                'id_sect_act' => $secteurVente->id_sect_act,
                'id_type_cont' => $typeCDD->id_type_cont,
                'titre' => 'Commercial Terrain',
                'description' => 'Besoin d\'un commercial avec voiture obligatoire.',
                'salaire_min' => 300000,
                'date_publication' => now(),
                'date_expiration' => now()->addDays(15),
                'active' => true,
                'debutant_accepte' => false,
                'permis_b_requis' => true,
                'vehicule_requis' => true, // Le candidat n'a pas de véhicule
                'travail_nuit' => false,
                'travail_weekend' => true, // Le candidat ne veut pas
            ]);
        }
    }
}
