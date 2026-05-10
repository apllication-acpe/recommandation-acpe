<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfilRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfilController extends Controller
{
    public function edit()
    {
        $demandeur = Auth::user()->demandeur;
        
        $nationalites = \App\Models\Nationalite::orderBy('libelle')->get();
        $allQualifications = \App\Models\Qualification::orderBy('intitule')->get();
        $allDiplomes = \App\Models\Diplome::orderBy('libelle')->get();
        $allCompetences = \App\Models\Competence::all();
        $allLangues = \App\Models\Langue::all();
        $allTypeContrats = \App\Models\TypeContrat::all();
        $allSecteurs = \App\Models\SecteurActivite::all();

        return view('candidat.profil.edit', compact(
            'demandeur', 
            'nationalites', 
            'allQualifications', 
            'allDiplomes',
            'allCompetences', 
            'allLangues',
            'allTypeContrats',
            'allSecteurs'
        ));
    }

    public function update(UpdateProfilRequest $request)
    {
        $demandeur = Auth::user()->demandeur;

        if (!$demandeur) {
            return redirect()->back()->with('error', 'Profil non trouvé.');
        }

        $data = $request->validated();
        
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Mise à jour des infos utilisateur
        if ($request->has('telephone')) {
            $user->update(['telephone' => $request->telephone]);
        }

        // Gestion de la photo
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $data['photo_path'] = $path;
            $user->update(['avatar' => $path]);
        }

        // Gestion du CV
        if ($request->hasFile('cv')) {
            $path = $request->file('cv')->store('cvs', 'public');
            $data['cv_path'] = $path;
        }

        // Mise à jour du demandeur
        $demandeur->update($data);

        // Synchronisation des relations
        if ($request->has('qualifications')) {
            $demandeur->qualifications()->sync($request->qualifications);
        }
        if ($request->has('diplomes')) {
            $demandeur->diplomes()->sync($request->diplomes);
        }
        if ($request->has('competences')) {
            $demandeur->competences()->sync($request->competences);
        }
        if ($request->has('langues')) {
            $demandeur->langues()->sync($request->langues);
        }
        if ($request->has('types_contrat_preferes')) {
            $demandeur->typesContratPreferes()->sync($request->types_contrat_preferes);
        }
        if ($request->has('secteurs_preferes')) {
            $demandeur->secteursActivitePreferes()->sync($request->secteurs_preferes);
        }

        // Enregistrement des nouvelles expériences
        if ($request->filled('new_experiences')) {
            foreach ($request->new_experiences as $expData) {
                $demandeur->experiences()->create([
                    'poste_occupe' => $expData['poste'],
                    'entreprise' => $expData['entreprise'],
                    'date_debut' => $expData['date_debut'],
                    'date_fin' => $expData['date_fin'],
                    'description' => $expData['description'],
                ]);
            }
        }

        return redirect()->back()->with('success', 'Votre profil a été mis à jour avec succès.');
    }
}
