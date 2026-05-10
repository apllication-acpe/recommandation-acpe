<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Demandeur;
use App\Models\Offre;

class Langue extends Model
{
    use HasFactory;

    /**
     * La clé primaire de la table.
     *
     * @var string
     */
    protected $primaryKey = 'id_langue';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'libelle',
        'code_iso',
    ];

    /**
     * Les attributs qui doivent être cachés pour la sérialisation.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Accesseurs & Mutateurs
     */

    /**
     * Obtenir le libellé en majuscules.
     */
    public function getLibelleMajusculeAttribute(): string
    {
        return strtoupper($this->libelle);
    }

    /**
     * Relations
     */

    /**
     * Relation avec les offres d'emploi (langues requises).
     */
    public function offres(): BelongsToMany
    {
        return $this->belongsToMany(
            Offre::class,
            'langue_offre',
            'id_langue',
            'id_offre'
        )->withPivot('niveau_exige', 'poids', 'obligatoire')->withTimestamps();
    }

    /**
     * Relation avec les demandeurs (candidats).
     */
    public function demandeurs(): BelongsToMany
    {
        return $this->belongsToMany(
            Demandeur::class,
            'langue_demandeur',
            'id_langue',
            'id_demandeur'
        )->withPivot('niveau')->withTimestamps();
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Vérifier si la langue est utilisée dans au moins une offre.
     */
    public function isUsed(): bool
    {
        return $this->offres()->exists();
    }

    /**
     * Obtenir toutes les langues sous forme de tableau pour les selects.
     */
    public static function getForSelect(): array
    {
        return self::orderBy('libelle')
            ->get()
            ->mapWithKeys(fn($langue) => [
                $langue->id_langue => $langue->libelle
            ])
            ->toArray();
    }

    /**
     * Scopes
     */

    /**
     * Scope pour la recherche textuelle.
     */
    public function scopeRecherche($query, string $terme)
    {
        return $query->where('libelle', 'LIKE', "%{$terme}%");
    }
}