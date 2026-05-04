<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfilRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->hasRole('demandeur');
    }

    public function rules()
    {
        return [
            'sexe' => 'required|in:M,F',
            'date_naissance' => 'required|date|before:-16 years',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string|max:255',
            'disponibilite' => 'nullable|string',
            'permis_b' => 'nullable|boolean',
            'vehicule_personnel' => 'nullable|boolean',
            'travail_nuit' => 'nullable|boolean',
            'travail_weekend' => 'nullable|boolean',
            'mobilite_rayon_km' => 'nullable|integer|min:0',
            'description_profil' => 'nullable|string',
            'annees_experience' => 'required|integer|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cv' => 'nullable|mimes:pdf|max:10240',
        ];
    }
}
