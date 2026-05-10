<?php

namespace App\Observers;

use App\Models\Offre;
use App\Models\Alerte;
use App\Models\Message;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\JobAlerteMail;

class OffreObserver
{
    /**
     * Handle the Offre "created" event.
     */
    public function created(Offre $offre): void
    {
        // On ne traite que les offres actives
        if (!$offre->active) {
            return;
        }

        $this->notifierCandidats($offre);
    }

    /**
     * Handle the Offre "updated" event.
     */
    public function updated(Offre $offre): void
    {
        // Si l'offre vient d'être activée
        if ($offre->wasChanged('active') && $offre->active) {
            $this->notifierCandidats($offre);
        }
    }

    /**
     * Logique de notification des candidats ayant une alerte correspondante.
     */
    private function notifierCandidats(Offre $offre)
    {
        // Récupérer toutes les alertes actives
        $alertes = Alerte::where('active', true)->with('demandeur.user')->get();

        foreach ($alertes as $alerte) {
            $match = true;

            // 1. Vérification du secteur
            if ($alerte->id_sect_act && $alerte->id_sect_act != $offre->id_sect_act) {
                $match = false;
            }

            // 2. Vérification du lieu (simple recherche de texte)
            if ($match && $alerte->lieu && !Str::contains(Str::lower($offre->lieu ?? ''), Str::lower($alerte->lieu))) {
                // Vérifier aussi dans les localisations liées
                $hasLocalisation = $offre->localisations()->where('ville', 'like', '%' . $alerte->lieu . '%')->exists();
                if (!$hasLocalisation) {
                    $match = false;
                }
            }

            // 3. Vérification des mots-clés
            if ($match && $alerte->mots_cles) {
                $keywords = explode(',', $alerte->mots_cles);
                $found = false;
                foreach ($keywords as $kw) {
                    $kw = trim($kw);
                    if (Str::contains(Str::lower($offre->titre), Str::lower($kw)) || 
                        Str::contains(Str::lower($offre->description), Str::lower($kw))) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $match = false;
                }
            }

            // 4. Vérification du type de contrat
            if ($match && $alerte->id_type_cont && $alerte->id_type_cont != $offre->id_type_cont) {
                $match = false;
            }

            // Si c'est un match, on crée une notification et on envoie un mail
            if ($match) {
                // 1. Message interne
                Message::create([
                    'sender_id' => 1, // ID du système
                    'receiver_id' => $alerte->demandeur->user_id,
                    'objet' => '🔔 Alerte Emploi : Nouvelle offre disponible !',
                    'contenu' => "Bonne nouvelle ! Une nouvelle offre correspondant à votre alerte '{$alerte->titre}' vient d'être publiée : '{$offre->titre}' chez {$offre->entreprise->raison_sociale}. Découvrez-la vite !",
                    'id_offre' => $offre->id_offre,
                ]);

                // 2. Email réel
                try {
                    Mail::to($alerte->demandeur->user->email)->send(new JobAlerteMail($offre, $alerte));
                } catch (\Exception $e) {
                    // Log error but don't block
                    \Log::error("Erreur envoi mail alerte : " . $e->getMessage());
                }
            }
        }
    }
}
