<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Entreprise;
use App\Models\Demandeur;
use App\Models\SecteurActivite;
use App\Models\TypeContrat;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password123');

        // 1. Super Administrateur
        $admin = User::firstOrCreate(
            ['email' => 'admin@acpe.sn'],
            [
                'nom' => 'ADMIN',
                'prenom' => 'Super',
                'password' => $password,
                'telephone' => '770000000',
                'email_verified_at' => now(),
                'actif' => true
            ]
        );
        $admin->assignRole('admin');

        // 2. Entreprise de test
        $secteurDigital = SecteurActivite::where('libelle', 'LIKE', '%Digital%')->first();
        
        $entreprise = Entreprise::firstOrCreate(
            ['email_contact' => 'recrutement@techsn.com'],
            [
                'raison_sociale' => 'Sénégal Tech Solutions',
                'telephone' => '338000000',
                'adresse' => 'Dakar Plateau',
                'verifiee' => true,
            ]
        );

        // 3. Quelques offres d'emploi
        $cdi = TypeContrat::where('code', 'CDI')->first();
        $stage = TypeContrat::where('code', 'STAGE')->first();

        if (\App\Models\Offre::count() < 3) {
            \App\Models\Offre::create([
                'id_entreprise' => $entreprise->id_entreprise,
                'id_sect_act' => $secteurDigital->id_sect_act ?? null,
                'id_type_cont' => $cdi->id_type_cont ?? null,
                'titre' => 'Développeur Laravel Senior',
                'description' => 'Recherche expert PHP/Laravel pour projet de grande envergure.',
                'mission' => 'Développement API, Optimisation DB',
                'profil_recherche' => '5 ans exp, Laravel, VueJS',
                'salaire_min' => 800000,
                'salaire_max' => 1200000,
                'date_publication' => now(),
                'date_expiration' => now()->addDays(30),
                'active' => true,
                'debutant_accepte' => false,
                'permis_b_requis' => false,
            ]);

            \App\Models\Offre::create([
                'id_entreprise' => $entreprise->id_entreprise,
                'id_sect_act' => $secteurDigital->id_sect_act ?? null,
                'id_type_cont' => $stage->id_type_cont ?? null,
                'titre' => 'Stagiaire Community Manager',
                'description' => 'Gestion des réseaux sociaux ACPE.',
                'date_publication' => now(),
                'date_expiration' => now()->addDays(20),
                'active' => true,
                'debutant_accepte' => true,
            ]);
        }

        // 4. Utilisateurs Candidats
        $candidats = [
            [
                'email' => 'moussa@test.sn',
                'nom' => 'DIOP',
                'prenom' => 'Moussa',
                'telephone' => '771234567'
            ],
            [
                'email' => 'fatou@test.sn',
                'nom' => 'NDIAYE',
                'prenom' => 'Fatou',
                'telephone' => '778889900'
            ]
        ];

        foreach ($candidats as $cData) {
            $user = User::firstOrCreate(
                ['email' => $cData['email']],
                array_merge($cData, [
                    'password' => $password,
                    'email_verified_at' => now(),
                    'actif' => true
                ])
            );
            $user->assignRole('demandeur');

            $demandeur = Demandeur::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'date_naissance' => '1998-01-01',
                    'adresse' => 'Parcelles Assainies',
                    'disponibilite' => 'immediatement',
                ]
            );

            // Ajouter une compétence par défaut
            $comp = \App\Models\Competence::first();
            // 5. Simuler des candidatures
            $offres = \App\Models\Offre::all();
            foreach ($offres as $index => $o) {
                \App\Models\Candidature::firstOrCreate(
                    ['id_offre' => $o->id_offre, 'id_demandeur' => $demandeur->id_demandeur],
                    [
                        'date_candidature' => now()->subDays($index + 1),
                        'statut' => $index % 2 == 0 ? 'en_attente' : 'acceptee',
                        'message_motivation' => "Je suis très motivé par cette offre chez " . $o->entreprise->raison_sociale
                    ]
                );
            }

            // 6. Simuler un ticket de support et sa réponse
            if (\App\Models\SupportTicket::count() == 0) {
                $ticket = \App\Models\SupportTicket::create([
                    'user_id' => $user->id,
                    'reference' => 'TKT-' . strtoupper(bin2hex(random_bytes(4))),
                    'sujet' => 'Problème de téléchargement CV',
                    'description' => 'Bonjour, je n\'arrive pas à uploader mon CV en format PDF.',
                    'priorite' => 'haute',
                    'statut' => 'en_attente'
                ]);

                // Réponse de l'admin
                $ticket->messages()->create([
                    'user_id' => $admin->id,
                    'message' => 'Bonjour Moussa, nous avons bien reçu votre ticket. Veuillez réessayer, nous avons mis à jour le serveur.'
                ]);
            }

            // 7. Simuler une discussion dans la messagerie
            if (\App\Models\Message::count() == 0) {
                \App\Models\Message::create([
                    'sender_id' => $user->id,
                    'receiver_id' => $admin->id,
                    'objet' => 'Demande d\'information',
                    'contenu' => 'Bonjour Monsieur l\'administrateur, comment se passe le processus de validation ?',
                    'lu_at' => now()
                ]);

                \App\Models\Message::create([
                    'sender_id' => $admin->id,
                    'receiver_id' => $user->id,
                    'objet' => 'RE: Demande d\'information',
                    'contenu' => 'Bonjour ! Nous traitons votre demande, vous recevrez une notification bientôt.',
                ]);
            }

            // 8. CV détaillé (Diplômes & Expériences)
            $licence = \App\Models\Diplome::where('libelle', 'LIKE', '%Licence%')->first();
            if ($licence) {
                $demandeur->diplomes()->sync([$licence->id_diplome]);
            }

            if ($demandeur->experiences()->count() == 0) {
                $demandeur->experiences()->create([
                    'poste_occupe' => 'Développeur Stagiaire',
                    'entreprise' => 'Digital Congo',
                    'date_debut' => '2022-01-01',
                    'date_fin' => '2022-06-01',
                    'description' => 'Apprentissage de PHP et SQL.'
                ]);
            }
        }
    }
}
