<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Alerte;
use App\Models\SecteurActivite;
use App\Models\TypeContrat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlerteController extends Controller
{
    /**
     * Liste des alertes du demandeur.
     */
    public function index()
    {
        $demandeur = Auth::user()->demandeur;
        $alertes = $demandeur->alertes()->with(['secteur', 'typeContrat'])->latest()->get();
        $secteurs = SecteurActivite::orderBy('libelle')->get();
        $typeContrats = TypeContrat::all();

        return view('candidat.alertes.index', compact('alertes', 'secteurs', 'typeContrats'));
    }

    /**
     * Enregistre une nouvelle alerte.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'mots_cles' => 'nullable|string|max:255',
            'id_sect_act' => 'nullable|exists:secteur_activites,id_sect_act',
            'lieu' => 'nullable|string|max:255',
            'id_type_cont' => 'nullable|exists:type_contrats,id_type_cont',
            'frequence' => 'required|in:immediate,quotidienne,hebdomadaire',
        ]);

        $demandeur = Auth::user()->demandeur;
        
        $demandeur->alertes()->create($request->all());

        return redirect()->back()->with('success', 'Alerte créée avec succès !');
    }

    /**
     * Active ou désactive une alerte.
     */
    public function toggle(Alerte $alerte)
    {
        // Vérifier que l'alerte appartient bien au demandeur connecté
        if ($alerte->id_demandeur !== Auth::user()->demandeur->id_demandeur) {
            return response()->json(['success' => false, 'message' => 'Accès refusé'], 403);
        }

        $alerte->update(['active' => !$alerte->active]);

        return response()->json([
            'success' => true,
            'active' => $alerte->active,
            'message' => $alerte->active ? 'Alerte activée' : 'Alerte désactivée'
        ]);
    }

    /**
     * Supprime une alerte.
     */
    public function destroy(Alerte $alerte)
    {
        if ($alerte->id_demandeur !== Auth::user()->demandeur->id_demandeur) {
            abort(403);
        }

        $alerte->delete();

        return redirect()->back()->with('success', 'Alerte supprimée.');
    }
}
