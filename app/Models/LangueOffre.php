<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\Langue;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LangueOffre extends Pivot
{
    use HasFactory;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'langue_offre';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'niveau_exige',
        'poids',
        'obligatoire',
        'id_offre',
        'id_langue',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'poids' => 'integer',
        'obligatoire' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Accesseurs & Mutateurs
     */

    /**
     * Obtenir le libellé du niveau exigé.
     */
    public function getNiveauLibelleAttribute(): string
    {
        $niveaux = [
            'A1' => 'Débutant (A1)',
            'A2' => 'Élémentaire (A2)',
            'B1' => 'Intermédiaire (B1)',
            'B2' => 'Intermédiaire avancé (B2)',
            'C1' => 'Autonome (C1)',
            'C2' => 'Maîtrise (C2)',
            'NATIF' => 'Langue maternelle',
        ];

        return $niveaux[$this->niveau_exige] ?? $this->niveau_exige ?? 'Non spécifié';
    }

    /**
     * Obtenir la classe CSS pour le badge du niveau.
     */
    public function getNiveauBadgeClassAttribute(): string
    {
        $classes = [
            'A1' => 'badge-gray',
            'A2' => 'badge-gray',
            'B1' => 'badge-info',
            'B2' => 'badge-info',
            'C1' => 'badge-success',
            'C2' => 'badge-success',
            'NATIF' => 'badge-purple',
        ];

        return $classes[$this->niveau_exige] ?? 'badge-secondary';
    }

    /**
     * Vérifier si la langue est obligatoire.
     */
    public function getEstObligatoireAttribute(): bool
    {
        return $this->obligatoire;
    }

    /**
     * Obtenir le libellé de l'obligation.
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
     * Obtenir le score normalisé (0-1).
     */
    public function getNormalizedScoreAttribute(): float
    {
        return $this->poids ? $this->poids / 100 : 0;
    }

    /**
     * Obtenir la valeur numérique du niveau (pour les calculs).
     */
    public function getNiveauValeurAttribute(): int
    {
        $valeurs = [
            'A1' => 1,
            'A2' => 2,
            'B1' => 3,
            'B2' => 4,
            'C1' => 5,
            'C2' => 6,
            'NATIF' => 7,
        ];

        return $valeurs[$this->niveau_exige] ?? 0;
    }

    /**
     * Relations
     */

    /**
     * Relation avec l'offre.
     */
    public function offre()
    {
        return $this->belongsTo(Offre::class, 'id_offre', 'id_offre');
    }

    /**
     * Relation avec la langue.
     */
    public function langue(): BelongsTo
    {
        return $this->belongsTo(Langue::class, 'id_langue', 'id_langue');
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Vérifier si la langue est critique (obligatoire + poids élevé).
     */
    public function isCritique(): bool
    {
        return $this->obligatoire && ($this->poids ?? 0) >= 70;
    }

    /**
     * Vérifier si le niveau est suffisant par rapport à un niveau donné.
     */
    public function isNiveauSuffisant(string $niveauCandidat): bool
    {
        $niveauRequis = $this->niveau_valeur;
        $niveauCandidatValeur = $this->getNiveauValeurFromString($niveauCandidat);
        
        return $niveauCandidatValeur >= $niveauRequis;
    }

    /**
     * Obtenir la valeur numérique d'un niveau à partir de son code.
     */
    private function getNiveauValeurFromString(string $niveau): int
    {
        $valeurs = [
            'A1' => 1,
            'A2' => 2,
            'B1' => 3,
            'B2' => 4,
            'C1' => 5,
            'C2' => 6,
            'NATIF' => 7,
        ];

        return $valeurs[$niveau] ?? 0;
    }

    /**
     * Calculer le score de correspondance pour un demandeur.
     */
    public function calculateMatchScore(Demandeur $demandeur): float
    {
        // Récupérer le niveau du demandeur pour cette langue
        $niveauDemandeur = $this->getDemandeurNiveau($demandeur);
        
        if (!$niveauDemandeur) {
            // Si la langue est obligatoire mais non maîtrisée, score 0
            if ($this->obligatoire) {
                return 0;
            }
            // Si recommandée, score basé sur le poids (max 30%)
            return ($this->poids ?? 50) * 0.3;
        }

        // Vérifier si le niveau est suffisant
        $niveauRequis = $this->niveau_valeur;
        $niveauDemandeurValeur = $this->getNiveauValeurFromString($niveauDemandeur);
        
        if ($niveauDemandeurValeur < $niveauRequis) {
            // Niveau insuffisant, réduction du score
            $ratio = $niveauDemandeurValeur / $niveauRequis;
            return ($this->poids ?? 50) * $ratio;
        }

        // Niveau suffisant, score basé sur le poids
        return $this->poids ?? 50;
    }

    /**
     * Récupérer le niveau du demandeur pour cette langue.
     */
    private function getDemandeurNiveau(Demandeur $demandeur): ?string
    {
        // Récupérer la compétence linguistique du demandeur
        $competenceLangue = $demandeur->langues()
            ->where('id_langue', $this->id_langue)
            ->first();
        
        return $competenceLangue ? $competenceLangue->pivot->niveau : null;
    }

    /**
     * Obtenir les niveaux de langue possibles pour les formulaires.
     */
    public static function getNiveauxForSelect(): array
    {
        return [
            'A1' => 'Débutant (A1)',
            'A2' => 'Élémentaire (A2)',
            'B1' => 'Intermédiaire (B1)',
            'B2' => 'Intermédiaire avancé (B2)',
            'C1' => 'Autonome (C1)',
            'C2' => 'Maîtrise (C2)',
            'NATIF' => 'Langue maternelle',
        ];
    }

    /**
     * Scopes
     */

    /**
     * Scope pour les langues obligatoires.
     */
    public function scopeObligatoire($query)
    {
        return $query->where('obligatoire', true);
    }

    /**
     * Scope pour les langues recommandées.
     */
    public function scopeRecommande($query)
    {
        return $query->where('obligatoire', false);
    }

    /**
     * Scope pour les langues avec niveau élevé (C1, C2, NATIF).
     */
    public function scopeNiveauEleve($query)
    {
        return $query->whereIn('niveau_exige', ['C1', 'C2', 'NATIF']);
    }

    /**
     * Scope pour les langues avec niveau intermédiaire (B1, B2).
     */
    public function scopeNiveauIntermédiaire($query)
    {
        return $query->whereIn('niveau_exige', ['B1', 'B2']);
    }

    /**
     * Scope pour les langues avec niveau débutant (A1, A2).
     */
    public function scopeNiveauDebutant($query)
    {
        return $query->whereIn('niveau_exige', ['A1', 'A2']);
    }

    /**
     * Scope pour les langues avec poids élevé.
     */
    public function scopePoidsEleve($query, int $seuil = 70)
    {
        return $query->where('poids', '>=', $seuil);
    }

    /**
     * Scope pour les langues par ordre de priorité.
     */
    public function scopeParPriorite($query)
    {
        return $query->orderBy('poids', 'desc')
            ->orderBy('obligatoire', 'desc')
            ->orderByRaw("FIELD(niveau_exige, 'NATIF', 'C2', 'C1', 'B2', 'B1', 'A2', 'A1')");
    }
}