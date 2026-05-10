<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alerte extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_demandeur',
        'titre',
        'mots_cles',
        'id_sect_act',
        'lieu',
        'id_type_cont',
        'frequence',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Relation avec le demandeur.
     */
    public function demandeur(): BelongsTo
    {
        return $this->belongsTo(Demandeur::class, 'id_demandeur', 'id_demandeur');
    }

    /**
     * Relation avec le secteur d'activité.
     */
    public function secteur(): BelongsTo
    {
        return $this->belongsTo(SecteurActivite::class, 'id_sect_act', 'id_sect_act');
    }

    /**
     * Relation avec le type de contrat.
     */
    public function typeContrat(): BelongsTo
    {
        return $this->belongsTo(TypeContrat::class, 'id_type_cont', 'id_type_cont');
    }
}
