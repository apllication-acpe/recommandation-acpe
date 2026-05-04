<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\Demandeur;
use App\Models\Diplome;
use App\Models\Offre;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiplomeOffre extends Pivot
{
    use HasFactory;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'diplome_offre';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'obligatoire',
        'poids',
        'id_offre',
        'id_diplome',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'obligatoire' => 'boolean',
        'poids' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Accesseurs & Mutateurs
     */

    /**
     * Vérifier si le diplôme est obligatoire.
     */
    public function getEstObligatoireAttribute(): bool
    {
        return $this->obligatoire;
    }

    /**
     * Obtenir le libellé du niveau d'obligation.
     */
    public function getObligationLabelAttribute(): string
    {
        return $this->obligatoire ? 'Obligatoire' : 'Recommandé';
    }

    /**
     * Obtenir la classe CSS pour le badge d'obligation.
     */
    public function getObligationBadgeClassAttribute(): string
    {
        return $this->obligatoire ? 'badge-danger' : 'badge-info';
    }

    /**
     * Obtenir le niveau de priorité basé sur le poids.
     */
    public function getPrioriteAttribute(): string
    {
        if ($this->poids >= 80) {
            return 'Très haute';
        } elseif ($this->poids >= 60) {
            return 'Haute';
        } elseif ($this->poids >= 40) {
            return 'Moyenne';
        } elseif ($this->poids >= 20) {
            return 'Basse';
        }
        return 'Très basse';
    }

    /**
     * Obtenir la couleur selon le poids.
     */
    public function getPoidsCouleurAttribute(): string
    {
        if ($this->poids >= 80) {
            return '#EF4444';
        } elseif ($this->poids >= 60) {
            return '#F59E0B';
        } elseif ($this->poids >= 40) {
            return '#10B981';
        } elseif ($this->poids >= 20) {
            return '#3B82F6';
        }
        return '#6B7280';
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
     * Relation avec le diplôme.
     */
    public function diplome(): BelongsTo
    {
        return $this->belongsTo(Diplome::class, 'id_diplome', 'id_diplome');
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Vérifier si le diplôme est obligatoire et avec un poids élevé.
     */
    public function isCritique(): bool
    {
        return $this->obligatoire && $this->poids >= 70;
    }

    /**
     * Calculer le score de correspondance pour un demandeur.
     */
    public function calculateMatchScore(Demandeur $demandeur): float
    {
        // Vérifier si le demandeur possède ce diplôme
        $possedeDiplome = $demandeur->qualifications()
            ->where('id_qualification', $this->id_diplome)
            ->exists();

        if (!$possedeDiplome) {
            // Si le diplôme est obligatoire, score 0
            if ($this->obligatoire) {
                return 0;
            }
            // Sinon, score basé sur le poids (max 30% si non requis)
            return $this->poids * 0.3;
        }

        // Score basé sur le poids
        return $this->poids;
    }

    /**
     * Obtenir le score normalisé (0-1).
     */
    public function getNormalizedScoreAttribute(): float
    {
        return $this->poids / 100;
    }

    /**
     * Scopes
     */

    /**
     * Scope pour les diplômes obligatoires.
     */
    public function scopeObligatoire($query)
    {
        return $query->where('obligatoire', true);
    }

    /**
     * Scope pour les diplômes recommandés.
     */
    public function scopeRecommande($query)
    {
        return $query->where('obligatoire', false);
    }

    /**
     * Scope pour les diplômes avec poids élevé.
     */
    public function scopePoidsEleve($query, int $seuil = 70)
    {
        return $query->where('poids', '>=', $seuil);
    }

    /**
     * Scope pour les diplômes par ordre de priorité.
     */
    public function scopeParPriorite($query)
    {
        return $query->orderBy('poids', 'desc')->orderBy('obligatoire', 'desc');
    }
}