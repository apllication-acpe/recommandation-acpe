<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\Offre;
use App\Models\Competence;

class CompetenceOffre extends Pivot
{
    use HasFactory;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'competence_offre';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'poids',
        'obligatoire',
        'niveau_minimum',
        'id_offre',
        'id_competence',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'poids' => 'integer',
        'obligatoire' => 'boolean',
        'niveau_minimum' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Accesseurs & Mutateurs
     */

    /**
     * Obtenir le libellé du niveau minimum.
     */
    public function getNiveauMinimumLibelleAttribute(): string
    {
        $niveaux = [
            1 => 'Débutant',
            2 => 'Intermédiaire',
            3 => 'Confirmé',
            4 => 'Avancé',
            5 => 'Expert',
        ];

        return $niveaux[$this->niveau_minimum] ?? 'Non spécifié';
    }

    /**
     * Obtenir la classe CSS pour le badge du niveau.
     */
    public function getNiveauMinimumBadgeClassAttribute(): string
    {
        $classes = [
            1 => 'badge-gray',
            2 => 'badge-info',
            3 => 'badge-blue',
            4 => 'badge-purple',
            5 => 'badge-success',
        ];

        return $classes[$this->niveau_minimum] ?? 'badge-secondary';
    }

    /**
     * Vérifier si la compétence est obligatoire.
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
            return 'Critique';
        } elseif ($this->poids >= 60) {
            return 'Très haute';
        } elseif ($this->poids >= 40) {
            return 'Haute';
        } elseif ($this->poids >= 20) {
            return 'Moyenne';
        }
        return 'Basse';
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
     * Obtenir l'icône selon le niveau.
     */
    public function getNiveauIconeAttribute(): string
    {
        $icones = [
            1 => '🌱',
            2 => '📘',
            3 => '⚡',
            4 => '🚀',
            5 => '🏆',
        ];

        return $icones[$this->niveau_minimum] ?? '❓';
    }

    /**
     * Vérifier si la compétence est critique.
     */
    public function getIsCritiqueAttribute(): bool
    {
        return $this->obligatoire && ($this->poids ?? 0) >= 70;
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
     * Relation avec la compétence.
     */
    public function competence(): BelongsTo
    {
        return $this->belongsTo(Competence::class, 'id_competence', 'id_competence');
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Vérifier si le niveau du candidat est suffisant.
     */
    public function isNiveauSuffisant(int $niveauCandidat): bool
    {
        if (!$this->niveau_minimum) {
            return true;
        }
        return $niveauCandidat >= $this->niveau_minimum;
    }

    /**
     * Calculer le score de correspondance pour un demandeur.
     */
    public function calculateMatchScore(Demandeur $demandeur): float
    {
        // Récupérer le niveau du demandeur pour cette compétence
        $niveauDemandeur = $this->getDemandeurNiveau($demandeur);
        $poids = $this->poids ?? 50;
        
        if (!$niveauDemandeur) {
            // Si la compétence est obligatoire mais non maîtrisée, score 0
            if ($this->obligatoire) {
                return 0;
            }
            // Si recommandée, score basé sur le poids (max 30%)
            return $poids * 0.3;
        }

        // Vérifier si le niveau est suffisant
        if ($this->niveau_minimum && $niveauDemandeur < $this->niveau_minimum) {
            // Niveau insuffisant, réduction du score
            $ratio = $niveauDemandeur / $this->niveau_minimum;
            return $poids * $ratio;
        }

        // Niveau suffisant, score basé sur le poids
        // Bonus si le niveau est supérieur au requis
        $bonus = 0;
        if ($this->niveau_minimum && $niveauDemandeur > $this->niveau_minimum) {
            $bonus = min(20, ($niveauDemandeur - $this->niveau_minimum) * 5);
        }
        
        return min(100, $poids + $bonus);
    }

    /**
     * Récupérer le niveau du demandeur pour cette compétence.
     */
    private function getDemandeurNiveau(Demandeur $demandeur): ?int
    {
        // Récupérer la compétence du demandeur
        $competenceDemandeur = $demandeur->competences()
            ->where('id_competence', $this->id_competence)
            ->first();
        
        return $competenceDemandeur ? $competenceDemandeur->pivot->niveau : null;
    }

    /**
     * Obtenir les niveaux possibles pour les formulaires.
     */
    public static function getNiveauxForSelect(): array
    {
        return [
            1 => '🌱 Débutant - Connaissances de base',
            2 => '📘 Intermédiaire - Pratique régulière',
            3 => '⚡ Confirmé - Autonome',
            4 => '🚀 Avancé - Maîtrise approfondie',
            5 => '🏆 Expert - Référence / Formation',
        ];
    }

    /**
     * Obtenir les poids possibles pour les formulaires.
     */
    public static function getPoidsForSelect(): array
    {
        return [
            20 => 'Faible - Compérience accessoire (20%)',
            40 => 'Moyenne - Compétence utile (40%)',
            60 => 'Importante - Compétence clé (60%)',
            80 => 'Très importante - Compétence majeure (80%)',
            100 => 'Critique - Indispensable (100%)',
        ];
    }

    /**
     * Scopes
     */

    /**
     * Scope pour les compétences obligatoires.
     */
    public function scopeObligatoire($query)
    {
        return $query->where('obligatoire', true);
    }

    /**
     * Scope pour les compétences recommandées.
     */
    public function scopeRecommande($query)
    {
        return $query->where('obligatoire', false);
    }

    /**
     * Scope pour les compétences avec niveau minimum élevé.
     */
    public function scopeNiveauEleve($query, int $seuil = 4)
    {
        return $query->where('niveau_minimum', '>=', $seuil);
    }

    /**
     * Scope pour les compétences avec poids élevé.
     */
    public function scopePoidsEleve($query, int $seuil = 70)
    {
        return $query->where('poids', '>=', $seuil);
    }

    /**
     * Scope pour les compétences critiques (obligatoires + poids élevé).
     */
    public function scopeCritique($query)
    {
        return $query->where('obligatoire', true)
            ->where('poids', '>=', 70);
    }

    /**
     * Scope pour les compétences par ordre de priorité.
     */
    public function scopeParPriorite($query)
    {
        return $query->orderBy('obligatoire', 'desc')
            ->orderBy('poids', 'desc')
            ->orderBy('niveau_minimum', 'desc');
    }
}