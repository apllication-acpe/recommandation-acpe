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
        return view('candidat.profil.edit', compact('demandeur'));
    }

    public function update(UpdateProfilRequest $request)
    {
        $demandeur = Auth::user()->demandeur;

        if (!$demandeur) {
            return redirect()->back()->with('error', 'Profil non trouvé.');
        }

        $data = $request->validated();

        // Gestion de la photo
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $data['photo_path'] = $path;
            
            // Synchroniser avec le modèle User pour la compatibilité
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $user->update(['avatar' => $path]);
        }

        // Gestion du CV
        if ($request->hasFile('cv')) {
            $path = $request->file('cv')->store('cvs', 'public');
            $data['cv_path'] = $path;
        }

        $demandeur->update($data);

        return redirect()->route('candidat.dashboard')->with('success', 'Votre profil a été mis à jour avec succès.');
    }
}
