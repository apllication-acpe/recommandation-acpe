<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\Offre;
use App\Models\Localisation;

class OffreLocalisation extends Pivot
{
    use HasFactory;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'offre_localisation';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'est_principale',
        'teletravail_possible',
        'id_offre',
        'id_localisation',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'est_principale' => 'boolean',
        'teletravail_possible' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Accesseurs & Mutateurs
     */

    /**
     * Vérifier si c'est la localisation principale.
     */
    public function getEstPrincipaleAttribute(): bool
    {
        return $this->est_principale;
    }

    /**
     * Obtenir le libellé du type de localisation.
     */
    public function getTypeLocalisationAttribute(): string
    {
        if ($this->est_principale && $this->teletravail_possible) {
            return 'Mixte (présentiel + télétravail)';
        } elseif ($this->est_principale) {
            return 'Présentiel';
        } elseif ($this->teletravail_possible) {
            return 'Télétravail possible';
        }
        return 'Secondaire';
    }

    /**
     * Obtenir la classe CSS pour le badge du type.
     */
    public function getTypeBadgeClassAttribute(): string
    {
        $classes = [
            'Présentiel' => 'badge-primary',
            'Télétravail possible' => 'badge-success',
            'Mixte (présentiel + télétravail)' => 'badge-info',
            'Secondaire' => 'badge-secondary',
        ];

        return $classes[$this->type_localisation] ?? 'badge-gray';
    }

    /**
     * Obtenir l'icône du type de localisation.
     */
    public function getTypeIconeAttribute(): string
    {
        if ($this->est_principale && $this->teletravail_possible) {
            return '🏢💻';
        } elseif ($this->est_principale) {
            return '🏢';
        } elseif ($this->teletravail_possible) {
            return '💻';
        }
        return '📍';
    }

    /**
     * Vérifier si le télétravail est autorisé.
     */
    public function getTeletravailAutoriseAttribute(): bool
    {
        return $this->teletravail_possible;
    }

    /**
     * Obtenir le libellé du télétravail.
     */
    public function getTeletravailLabelAttribute(): string
    {
        return $this->teletravail_possible ? 'Télétravail possible' : 'Télétravail non disponible';
    }

    /**
     * Obtenir la classe CSS pour le badge de télétravail.
     */
    public function getTeletravailBadgeClassAttribute(): string
    {
        return $this->teletravail_possible ? 'badge-success' : 'badge-gray';
    }

    /**
     * Relations
     */

    /**
     * Relation avec l'offre.
     */
    public function offre(): BelongsTo
    {
        return $this->belongsTo(Offre::class, 'id_offre', 'id_offre');
    }

    /**
     * Relation avec la localisation.
     */
    public function localisation(): BelongsTo
    {
        return $this->belongsTo(Localisation::class, 'id_localisation', 'id_localisation');
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Vérifier si c'est une localisation valide pour l'offre.
     */
    public function isValid(): bool
    {
        return $this->offre && $this->localisation;
    }

    /**
     * Obtenir le libellé complet pour l'affichage.
     */
    public function getLibelleCompletAttribute(): string
    {
        $localisationStr = $this->localisation ? $this->localisation->libelle_complet : 'Localisation inconnue';
        
        if ($this->est_principale) {
            $localisationStr .= ' ⭐ (Principale)';
        }
        
        if ($this->teletravail_possible) {
            $localisationStr .= ' - Télétravail possible';
        }
        
        return $localisationStr;
    }

    /**
     * Obtenir la localisation principale d'une offre.
     */
    public static function getPrincipaleForOffre(int $idOffre): ?self
    {
        return self::where('id_offre', $idOffre)
            ->where('est_principale', true)
            ->with('localisation')
            ->first();
    }

    /**
     * Obtenir toutes les localisations d'une offre.
     */
    public static function getAllForOffre(int $idOffre): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('id_offre', $idOffre)
            ->with('localisation')
            ->orderBy('est_principale', 'desc')
            ->orderBy('created_at')
            ->get();
    }

    /**
     * Vérifier si une offre a une localisation spécifique.
     */
    public static function offreHasLocalisation(int $idOffre, int $idLocalisation): bool
    {
        return self::where('id_offre', $idOffre)
            ->where('id_localisation', $idLocalisation)
            ->exists();
    }

    /**
     * Compter le nombre d'offres par localisation.
     */
    public static function getStatsByLocalisation(): array
    {
        return self::select('id_localisation')
            ->selectRaw('COUNT(*) as total_offres')
            ->selectRaw('SUM(CASE WHEN est_principale = 1 THEN 1 ELSE 0 END) as offres_principales')
            ->selectRaw('SUM(CASE WHEN teletravail_possible = 1 THEN 1 ELSE 0 END) as offres_teletravail')
            ->groupBy('id_localisation')
            ->with('localisation')
            ->get()
            ->mapWithKeys(fn($stat) => [
                $stat->localisation->libelle_court => [
                    'total' => $stat->total_offres,
                    'principales' => $stat->offres_principales,
                    'teletravail' => $stat->offres_teletravail,
                ]
            ])
            ->toArray();
    }

    /**
     * Scopes
     */

    /**
     * Scope pour les localisations principales.
     */
    public function scopePrincipale($query)
    {
        return $query->where('est_principale', true);
    }

    /**
     * Scope pour les localisations secondaires.
     */
    public function scopeSecondaire($query)
    {
        return $query->where('est_principale', false);
    }

    /**
     * Scope pour les offres avec télétravail possible.
     */
    public function scopeTeletravailPossible($query)
    {
        return $query->where('teletravail_possible', true);
    }

    /**
     * Scope pour les offres sans télétravail.
     */
    public function scopeSansTeletravail($query)
    {
        return $query->where('teletravail_possible', false);
    }

    /**
     * Scope pour les offres en présentiel uniquement.
     */
    public function scopePresentiel($query)
    {
        return $query->where('est_principale', true)
            ->where('teletravail_possible', false);
    }

    /**
     * Scope pour une localisation spécifique.
     */
    public function scopePourLocalisation($query, int $idLocalisation)
    {
        return $query->where('id_localisation', $idLocalisation);
    }

    /**
     * Scope pour une offre spécifique.
     */
    public function scopePourOffre($query, int $idOffre)
    {
        return $query->where('id_offre', $idOffre);
    }
}