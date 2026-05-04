<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Offre;
use App\Models\Candidature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OffreController extends Controller
{
    public function show(Offre $offre)
    {
        $offre->load(['entreprise', 'typeContrat', 'secteurActivite', 'competences', 'langues', 'diplomes']);
        $dejaPostule = Candidature::where('id_demandeur', Auth::user()->demandeur->id_demandeur)
            ->where('id_offre', $offre->id_offre)
            ->exists();

        return view('candidat.offres.show', compact('offre', 'dejaPostule'));
    }

    public function postuler(Request $request, Offre $offre)
    {
        $demandeur = Auth::user()->demandeur;

        // Vérifier si déjà postulé
        if (Candidature::where('id_demandeur', $demandeur->id_demandeur)->where('id_offre', $offre->id_offre)->exists()) {
            return redirect()->back()->with('error', 'Vous avez déjà postulé à cette offre.');
        }

        $request->validate([
            'message_motivation' => 'nullable|string|max:2000',
        ]);

        Candidature::create([
            'id_demandeur' => $demandeur->id_demandeur,
            'id_offre' => $offre->id_offre,
            'message_motivation' => $request->message_motivation,
            'statut' => 'en_attente',
            'date_candidature' => now(),
        ]);

        return redirect()->route('candidat.candidatures')->with('success', 'Votre candidature a été envoyée avec succès.');
    }
}
