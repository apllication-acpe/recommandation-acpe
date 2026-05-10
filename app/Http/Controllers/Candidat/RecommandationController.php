<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RecommandationController extends Controller
{
    public function criteres(\Illuminate\Http\Request $request)
    {
        $demandeur = Auth::user()->demandeur;
        
        $query = \App\Models\Offre::where('active', true)
            ->with(['entreprise', 'typeContrat', 'secteurActivite', 'localisations']);

        // Filtre Localisation
        if ($request->filled('localisation')) {
            $query->whereHas('localisations', function($q) use ($request) {
                $q->where('ville', 'LIKE', '%' . $request->localisation . '%');
            });
        }

        // Filtre Type de Contrat
        if ($request->filled('types')) {
            $query->whereHas('typeContrat', function($q) use ($request) {
                $q->whereIn('code', (array) $request->types);
            });
        }

        // Filtre Salaire
        if ($request->filled('salaire')) {
            $query->where('salaire_min', '>=', $request->salaire * 1000); // Conversion K en unité
        }

        $offres = $query->get();

        $recommandations = collect();

        foreach ($offres as $offre) {
            $scoreLogistique = 0;
            $maxLogistique = 0;
            $justifications = [];

            // Débutant accepté
            if ($offre->debutant_accepte) {
                $scoreLogistique += 30;
                $maxLogistique += 30;
                $justifications[] = "Idéal pour débuter : aucune expérience requise.";
            }

            // Permis B
            if ($offre->permis_b_requis) {
                $maxLogistique += 20;
                if ($demandeur->permis_b) {
                    $scoreLogistique += 20;
                    $justifications[] = "Vous avez le permis B (Requis).";
                }
            } else if ($demandeur->permis_b) {
                // Bonus
                $scoreLogistique += 5;
                $maxLogistique += 5;
                $justifications[] = "Votre permis B est un atout.";
            }

            // Nuit
            if ($offre->travail_nuit) {
                $maxLogistique += 25;
                if ($demandeur->travail_nuit) {
                    $scoreLogistique += 25;
                    $justifications[] = "Vos horaires correspondent (Travail de nuit).";
                }
            }

            // Weekend
            if ($offre->travail_weekend) {
                $maxLogistique += 25;
                if ($demandeur->travail_weekend) {
                    $scoreLogistique += 25;
                    $justifications[] = "Vos horaires correspondent (Travail le week-end).";
                }
            }

            // Disponibilité
            if ($demandeur->disponibilite === 'immediatement') {
                $scoreLogistique += 10;
                $maxLogistique += 10;
                $justifications[] = "Votre disponibilité immédiate est un gros avantage.";
            }

            // Si l'offre n'a aucune contrainte logistique particulière et qu'on n'est pas débutant
            if ($maxLogistique === 0) {
                $scoreGlobal = 50; // Score neutre
            } else {
                $scoreGlobal = ($scoreLogistique / $maxLogistique) * 100;
            }

            $recommandations->push([
                'offre' => $offre,
                'score_global' => round($scoreGlobal),
                'details' => [
                    'logistique' => round($scoreGlobal),
                ],
                'justifications' => $justifications
            ]);
        }

        // Trier par score décroissant et prendre les meilleures
        $recommandations = $recommandations->sortByDesc('score_global')->take(10)->values();
        
        return view('candidat.recommandations.criteres', compact('demandeur', 'recommandations'));
    }

    public function professionnelle()
    {
        $demandeur = Auth::user()->demandeur;
        
        // Récupérer toutes les offres actives
        $offres = \App\Models\Offre::where('active', true)
            ->with(['entreprise', 'typeContrat', 'secteurActivite', 'localisations'])
            ->get();

        $recommandations = collect();

        foreach ($offres as $offre) {
            $scorePro = 0;
            $maxPro = 0;
            $justifications = [];

            // 1. Secteur d'activité
            if ($offre->id_sect_act) {
                $maxPro += 30;
                // Vérifier si le demandeur a une expérience dans ce secteur (Approximation: on vérifie si l'offre partage le même secteur qu'une offre déjà postulée, ou on se base sur les mots-clés)
                // Pour faire simple, on va vérifier si l'intitulé du poste de ses expériences correspond
                $secteurMatch = false;
                if ($offre->secteurActivite) {
                    $secteurWords = explode(' ', strtolower($offre->secteurActivite->libelle));
                    foreach ($demandeur->experiences as $exp) {
                        foreach ($secteurWords as $word) {
                            if (strlen($word) > 3 && stripos($exp->poste_occupe, $word) !== false) {
                                $secteurMatch = true;
                                break 2;
                            }
                        }
                    }
                }
                
                if ($secteurMatch) {
                    $scorePro += 30;
                    $justifications[] = "Secteur d'activité pertinent avec vos expériences.";
                }
            }

            // 2. Années d'expérience
            // Admettons que l'offre a un champ 'annees_experience_requises' ou qu'on compare avec la séniorité
            $annees_demandeur = $demandeur->getTotalYearsExperience();
            $maxPro += 40;
            if ($annees_demandeur > 0) {
                if ($annees_demandeur >= 5) {
                    $scorePro += 40;
                    $justifications[] = "Profil Senior reconnu ({$annees_demandeur} ans d'expérience).";
                } elseif ($annees_demandeur >= 2) {
                    $scorePro += 30;
                    $justifications[] = "Profil Intermédiaire ({$annees_demandeur} ans d'expérience).";
                } else {
                    $scorePro += 15;
                    $justifications[] = "Premières expériences valorisées.";
                }
            }

            // 3. Type de contrat souhaité vs Offre (Si le demandeur cherche un CDI et c'est un CDI)
            $maxPro += 30;
            if ($offre->typeContrat && stripos($offre->typeContrat->code, 'CDI') !== false) {
                $scorePro += 30;
                $justifications[] = "Opportunité stable (CDI).";
            } else {
                $scorePro += 15; // Point moyen pour les autres contrats
            }

            if ($maxPro === 0) {
                $scoreGlobal = 50;
            } else {
                $scoreGlobal = ($scorePro / $maxPro) * 100;
            }

            if ($scoreGlobal > 0) {
                $recommandations->push([
                    'offre' => $offre,
                    'score_global' => round($scoreGlobal),
                    'details' => [
                        'poste' => round(($scorePro / $maxPro) * 100),
                    ],
                    'justifications' => empty($justifications) ? ["Profil intéressant."] : $justifications
                ]);
            }
        }

        // Trier par score décroissant et prendre les meilleures
        $recommandations = $recommandations->sortByDesc('score_global')->take(10)->values();
        
        return view('candidat.recommandations.professionnelle', compact('demandeur', 'recommandations'));
    }
}
