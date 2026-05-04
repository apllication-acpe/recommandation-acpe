<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOffreRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->hasRole('admin');
    }

    public function rules()
    {
        return [
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'mission' => 'required|string',
            'profil_recherche' => 'required|string',
            'salaire_min' => 'nullable|numeric|min:0',
            'salaire_max' => 'nullable|numeric|min:0',
            'statut_salaire' => 'nullable|string|max:100',
            'date_publication' => 'nullable|date',
            'date_expiration' => 'required|date|after_or_equal:today',
            'id_sect_act' => 'required|exists:secteur_activites,id_sect_act',
            'id_type_cont' => 'required|exists:type_contrats,id_type_cont',
            'active' => 'nullable|boolean',
        ];
    }
}
