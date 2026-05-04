<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\Critere;
use App\Models\Demandeur;

class CritereDemandeur extends Pivot
{
    use HasFactory;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'critere_demandeur';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'score',
        'justification',
        'id_demandeur',
        'id_critere',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'score' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Accesseurs & Mutateurs
     */

    /**
     * Obtenir le score formaté (sur 100 avec une décimale).
     */
    public function getScoreFormateAttribute(): string
    {
        if ($this->score === null) {
            return 'Non évalué';
        }
        return number_format($this->score, 1, ',', ' ') . '/100';
    }

    /**
     * Obtenir le score arrondi.
     */
    public function getScoreArrondiAttribute(): int
    {
        return (int) round($this->score ?? 0);
    }

    /**
     * Obtenir le niveau d'appréciation basé sur le score.
     */
    public function getAppreciationAttribute(): string
    {
        if ($this->score === null) {
            return 'Non évalué';
        }
        
        if ($this->score >= 90) {
            return 'Exceptionnel';
        } elseif ($this->score >= 75) {
            return 'Très bien';
        } elseif ($this->score >= 60) {
            return 'Bien';
        } elseif ($this->score >= 45) {
            return 'Moyen';
        } elseif ($this->score >= 30) {
            return 'Insuffisant';
        } else {
            return 'Faible';
        }
    }

    /**
     * Obtenir la classe CSS pour le badge d'appréciation.
     */
    public function getAppreciationBadgeClassAttribute(): string
    {
        $classes = [
            'Exceptionnel' => 'badge-purple',
            'Très bien' => 'badge-success',
            'Bien' => 'badge-info',
            'Moyen' => 'badge-warning',
            'Insuffisant' => 'badge-orange',
            'Faible' => 'badge-danger',
            'Non évalué' => 'badge-gray',
        ];

        return $classes[$this->appreciation] ?? 'badge-secondary';
    }

    /**
     * Obtenir la couleur associée au score.
     */
    public function getScoreCouleurAttribute(): string
    {
        if ($this->score === null) {
            return '#9CA3AF';
        }
        
        if ($this->score >= 75) {
            return '#10B981';
        } elseif ($this->score >= 50) {
            return '#F59E0B';
        } elseif ($this->score >= 25) {
            return '#F97316';
        }
        return '#EF4444';
    }

    /**
     * Obtenir le score pondéré (score * poids du critère / 100).
     */
    public function getScorePondereAttribute(): float
    {
        if ($this->score === null || !$this->critere) {
            return 0;
        }
        
        return ($this->score * ($this->critere->poids ?? 0)) / 100;
    }

    /**
     * Obtenir le score pondéré formaté.
     */
    public function getScorePondereFormateAttribute(): string
    {
        return number_format($this->score_pondere, 1, ',', ' ');
    }

    /**
     * Obtenir la mention correspondant au score.
     */
    public function getMentionAttribute(): ?string
    {
        if ($this->score === null) {
            return null;
        }
        
        if ($this->score >= 90) {
            return 'Excellent';
        } elseif ($this->score >= 80) {
            return 'Très Honorable';
        } elseif ($this->score >= 70) {
            return 'Honorable';
        } elseif ($this->score >= 60) {
            return 'Assez Bien';
        } elseif ($this->score >= 50) {
            return 'Passable';
        }
        return null;
    }

    /**
     * Vérifier si le score est validant (>= 60).
     */
    public function getEstValidantAttribute(): bool
    {
        return ($this->score ?? 0) >= 60;
    }

    /**
     * Vérifier si le score est excellent (>= 90).
     */
    public function getEstExcellentAttribute(): bool
    {
        return ($this->score ?? 0) >= 90;
    }

    /**
     * Vérifier si le score est éliminatoire (< 30).
     */
    public function getEstEliminatoireAttribute(): bool
    {
        return ($this->score ?? 0) < 30;
    }

    /**
     * Relations
     */

    /**
     * Relation avec le demandeur.
     */
    public function demandeur(): BelongsTo
    {
        return $this->belongsTo(Demandeur::class, 'id_demandeur', 'id_demandeur');
    }

    /**
     * Relation avec le critère.
     */
    public function critere(): BelongsTo
    {
        return $this->belongsTo(Critere::class, 'id_critere', 'id_critere');
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Calculer le score total pour un demandeur.
     */
    public static function calculateTotalScore(int $idDemandeur): float
    {
        $scores = self::with('critere')
            ->where('id_demandeur', $idDemandeur)
            ->get();
        
        $totalPondere = 0;
        $totalPoids = 0;
        
        foreach ($scores as $score) {
            if ($score->score !== null && $score->critere && $score->critere->poids) {
                $totalPondere += ($score->score * $score->critere->poids);
                $totalPoids += $score->critere->poids;
            }
        }
        
        if ($totalPoids === 0) {
            return 0;
        }
        
        return round($totalPondere / $totalPoids, 2);
    }

    /**
     * Obtenir le résumé des scores pour un demandeur.
     */
    public static function getResumeForDemandeur(int $idDemandeur): array
    {
        $scores = self::with('critere')
            ->where('id_demandeur', $idDemandeur)
            ->get();
        
        $totalScore = 0;
        $totalPoids = 0;
        $critiques = [];
        $faiblesses = [];
        
        foreach ($scores as $scoreItem) {
            if ($scoreItem->score !== null && $scoreItem->critere) {
                $scoreValue = $scoreItem->score;
                $poidsCritere = $scoreItem->critere->poids ?? 0;
                
                $totalScore += $scoreValue * $poidsCritere;
                $totalPoids += $poidsCritere;
                
                if ($scoreValue >= 75) {
                    $critiques[] = $scoreItem->critere->nom;
                } elseif ($scoreValue < 40) {
                    $faiblesses[] = $scoreItem->critere->nom;
                }
            }
        }
        
        return [
            'score_total' => $totalPoids > 0 ? round($totalScore / $totalPoids, 2) : 0,
            'total_criteres' => $scores->count(),
            'criteres_evalues' => $scores->whereNotNull('score')->count(),
            'points_forts' => $critiques,
            'points_faibles' => $faiblesses,
            'appreciation_globale' => self::getAppreciationGlobale($totalScore / max($totalPoids, 1)),
        ];
    }

    /**
     * Obtenir l'appréciation globale.
     */
    private static function getAppreciationGlobale(float $score): string
    {
        if ($score >= 80) {
            return 'Excellent candidat - Très recommandé';
        } elseif ($score >= 65) {
            return 'Bon candidat - Recommandé';
        } elseif ($score >= 50) {
            return 'Candidat acceptable - Peut convenir';
        } elseif ($score >= 35) {
            return 'Candidat à améliorer - À revoir';
        }
        return 'Candidat non recommandé';
    }

    /**
     * Obtenir le classement des meilleurs scores par critère.
     */
    public static function getClassementParCritere(int $idCritere, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('id_critere', $idCritere)
            ->whereNotNull('score')
            ->with('demandeur.user')
            ->orderBy('score', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les scores pour un demandeur sous forme de tableau.
     */
    public function toArrayWithDetails(): array
    {
        return [
            'id' => $this->id,
            'score' => $this->score,
            'score_formate' => $this->score_formate,
            'appreciation' => $this->appreciation,
            'mention' => $this->mention,
            'est_validant' => $this->est_validant,
            'est_excellent' => $this->est_excellent,
            'justification' => $this->justification,
            'critere' => $this->critere ? [
                'id' => $this->critere->id_critere,
                'nom' => $this->critere->nom,
                'poids' => $this->critere->poids,
                'priorite' => $this->critere->priorite,
            ] : null,
            'score_pondere' => $this->score_pondere,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Scopes
     */

    /**
     * Scope pour les scores validants (>= 60).
     */
    public function scopeValidant($query)
    {
        return $query->where('score', '>=', 60);
    }

    /**
     * Scope pour les scores excellents (>= 90).
     */
    public function scopeExcellent($query)
    {
        return $query->where('score', '>=', 90);
    }

    /**
     * Scope pour les scores insuffisants (< 40).
     */
    public function scopeInsuffisant($query)
    {
        return $query->where('score', '<', 40);
    }

    /**
     * Scope pour les scores avec justification.
     */
    public function scopeAvecJustification($query)
    {
        return $query->whereNotNull('justification')
            ->where('justification', '!=', '');
    }

    /**
     * Scope pour un demandeur spécifique.
     */
    public function scopePourDemandeur($query, int $idDemandeur)
    {
        return $query->where('id_demandeur', $idDemandeur);
    }

    /**
     * Scope pour un critère spécifique.
     */
    public function scopePourCritere($query, int $idCritere)
    {
        return $query->where('id_critere', $idCritere);
    }

    /**
     * Scope pour trier par score décroissant.
     */
    public function scopeMeilleursScores($query)
    {
        return $query->orderBy('score', 'desc');
    }

    /**
     * Scope pour trier par score croissant.
     */
    public function scopeMoinsBonsScores($query)
    {
        return $query->orderBy('score', 'asc');
    }
}