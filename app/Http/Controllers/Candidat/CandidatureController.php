<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Offre;
use App\Models\Candidature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidatureController extends Controller
{
    /**
     * Liste des candidatures du demandeur.
     */
    public function index()
    {
        $demandeur = Auth::user()->demandeur;
        $candidatures = $demandeur->candidatures()
            ->with(['offre.entreprise', 'offre.typeContrat'])
            ->latest('date_candidature')
            ->paginate(10);

        return view('candidat.candidatures.index', compact('candidatures'));
    }

    /**
     * Postuler à une offre.
     */
    public function postuler(Request $request, $id_offre)
    {
        $demandeur = Auth::user()->demandeur;
        
        if (!$demandeur) {
            return back()->with('error', 'Vous devez être un candidat pour postuler.');
        }

        $offre = Offre::findOrFail($id_offre);
        
        // Empêcher de postuler en interne à une offre externe (ACPE)
        if ($offre->source === 'acpe_scraping') {
            return redirect($offre->url_source)->with('info', 'Cette offre provient de l\'ACPE. Veuillez postuler directement sur leur site.');
        }

        // Vérifier si déjà postulé
        if ($demandeur->hasPostulated($offre)) {
            return back()->with('info', 'Vous avez déjà postulé à cette offre.');
        }

        // Créer la candidature
        Candidature::create([
            'id_demandeur' => $demandeur->id_demandeur,
            'id_offre' => $offre->id_offre,
            'statut' => 'en_attente',
            'date_candidature' => now(),
        ]);

        return back()->with('success', 'Votre candidature pour "' . $offre->titre . '" a été envoyée avec succès !');
    }

    /**
     * Annuler une candidature.
     */
    public function destroy($id_candidature)
    {
        $demandeur = Auth::user()->demandeur;
        $candidature = Candidature::where('id_candidature', $id_candidature)
            ->where('id_demandeur', $demandeur->id_demandeur)
            ->firstOrFail();

        if ($candidature->statut !== 'en_attente') {
            return back()->with('error', 'Vous ne pouvez plus annuler cette candidature car elle est déjà en cours de traitement.');
        }

        $candidature->delete();

        return back()->with('success', 'Candidature annulée avec succès.');
    }
}
