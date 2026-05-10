<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Offre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriController extends Controller
{
    /**
     * Affiche la liste des favoris du candidat.
     */
    public function index()
    {
        $demandeur = Auth::user()->demandeur;
        $favoris = $demandeur->favoris()->with(['entreprise', 'typeContrat', 'secteurActivite'])->latest()->get();
        
        return view('candidat.favoris.index', compact('favoris'));
    }

    /**
     * Ajoute ou retire une offre des favoris (Toggle).
     */
    public function toggle(Offre $offre)
    {
        try {
            $user = Auth::user();
            $demandeur = $user->demandeur;
            
            if (!$demandeur) {
                // Si le profil demandeur n'existe pas, on le cherche par user_id manuellement
                $demandeur = \App\Models\Demandeur::where('user_id', $user->id)->first();
            }

            if (!$demandeur) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil demandeur introuvable. Veuillez compléter votre profil.'
                ], 404);
            }

            $result = $demandeur->favoris()->toggle($offre->id_offre);
            $isFavori = count($result['attached']) > 0;

            return response()->json([
                'success' => true,
                'isFavori' => $isFavori,
                'count' => $demandeur->favoris()->count(),
                'message' => $isFavori ? 'Offre ajoutée aux favoris' : 'Offre retirée des favoris'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur technique : ' . $e->getMessage()
            ], 500);
        }
    }
}
