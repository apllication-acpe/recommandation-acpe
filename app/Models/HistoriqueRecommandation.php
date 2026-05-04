<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Demandeur;
use App\Models\Offre;

class HistoriqueRecommandation extends Model
{
    use HasFactory;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'historique_recommandations';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_demandeur',
        'id_offre',
        'score_calculated',
        'rang',
        'a_ete_clique',
        'a_ete_postule',
        'date_recommandation',
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
        'score_calculated' => 'float',
        'rang' => 'integer',
        'a_ete_clique' => 'boolean',
        'a_ete_postule' => 'boolean',
        'date_recommandation' => 'datetime',
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
        return number_format($this->score_calculated, 1, ',', ' ') . '/100';
    }

    /**
     * Obtenir le score arrondi.
     */
    public function getScoreArrondiAttribute(): int
    {
        return (int) round($this->score_calculated);
    }

    /**
     * Obtenir le niveau de correspondance.
     */
    public function getNiveauCorrespondanceAttribute(): string
    {
        if ($this->score_calculated >= 85) {
            return 'Excellent';
        } elseif ($this->score_calculated >= 70) {
            return 'Très bon';
        } elseif ($this->score_calculated >= 55) {
            return 'Bon';
        } elseif ($this->score_calculated >= 40) {
            return 'Moyen';
        } else {
            return 'Faible';
        }
    }

    /**
     * Obtenir la classe CSS pour le badge de niveau.
     */
    public function getNiveauBadgeClassAttribute(): string
    {
        $classes = [
            'Excellent' => 'badge-success',
            'Très bon' => 'badge-info',
            'Bon' => 'badge-blue',
            'Moyen' => 'badge-warning',
            'Faible' => 'badge-danger',
        ];

        return $classes[$this->niveau_correspondance] ?? 'badge-secondary';
    }

    /**
     * Obtenir la couleur selon le score.
     */
    public function getScoreCouleurAttribute(): string
    {
        if ($this->score_calculated >= 75) {
            return '#10B981';
        } elseif ($this->score_calculated >= 60) {
            return '#3B82F6';
        } elseif ($this->score_calculated >= 45) {
            return '#F59E0B';
        } elseif ($this->score_calculated >= 30) {
            return '#F97316';
        }
        return '#EF4444';
    }

    /**
     * Obtenir le rang avec suffixe.
     */
    public function getRangFormateAttribute(): string
    {
        $suffixe = $this->rang == 1 ? 'er' : 'ème';
        return $this->rang . $suffixe;
    }

    /**
     * Vérifier si la recommandation a été cliquée.
     */
    public function getACliqueAttribute(): bool
    {
        return $this->a_ete_clique;
    }

    /**
     * Vérifier si la recommandation a été postulée.
     */
    public function getAPostuleAttribute(): bool
    {
        return $this->a_ete_postule;
    }

    /**
     * Obtenir le statut d'interaction.
     */
    public function getStatutInteractionAttribute(): string
    {
        if ($this->a_ete_postule) {
            return '✅ Postulé';
        } elseif ($this->a_ete_clique) {
            return '👁️ Consulté';
        }
        return '⏳ Non consulté';
    }

    /**
     * Obtenir la classe CSS pour le badge d'interaction.
     */
    public function getInteractionBadgeClassAttribute(): string
    {
        $classes = [
            '✅ Postulé' => 'badge-success',
            '👁️ Consulté' => 'badge-info',
            '⏳ Non consulté' => 'badge-warning',
        ];

        return $classes[$this->statut_interaction] ?? 'badge-secondary';
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
     * Relation avec l'offre.
     */
    public function offre(): BelongsTo
    {
        return $this->belongsTo(Offre::class, 'id_offre', 'id_offre');
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Marquer la recommandation comme cliquée.
     */
    public function marquerClique(): bool
    {
        if (!$this->a_ete_clique) {
            return $this->update(['a_ete_clique' => true]);
        }
        return false;
    }

    /**
     * Marquer la recommandation comme postulée.
     */
    public function marquerPostule(): bool
    {
        if (!$this->a_ete_postule) {
            return $this->update([
                'a_ete_postule' => true,
                'a_ete_clique' => true,
            ]);
        }
        return false;
    }

    /**
     * Obtenir l'historique pour un demandeur.
     */
    public static function getForDemandeur(int $idDemandeur, ?int $limit = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = self::with(['offre.entreprise', 'offre.typeContrat'])
            ->where('id_demandeur', $idDemandeur)
            ->orderBy('date_recommandation', 'desc')
            ->orderBy('rang');
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }

    /**
     * Obtenir l'historique pour une offre.
     */
    public static function getForOffre(int $idOffre): \Illuminate\Database\Eloquent\Collection
    {
        return self::with(['demandeur.user'])
            ->where('id_offre', $idOffre)
            ->orderBy('score_calculated', 'desc')
            ->get();
    }

    /**
     * Obtenir les statistiques de clics et postulations.
     */
    public static function getInteractionStats(): array
    {
        $total = self::count();
        $cliques = self::where('a_ete_clique', true)->count();
        $postules = self::where('a_ete_postule', true)->count();
        
        return [
            'total_recommandations' => $total,
            'clics' => $cliques,
            'postulations' => $postules,
            'taux_clics' => $total > 0 ? round(($cliques / $total) * 100, 2) : 0,
            'taux_conversion' => $total > 0 ? round(($postules / $total) * 100, 2) : 0,
            'taux_transformation_clic_to_postule' => $cliques > 0 ? round(($postules / $cliques) * 100, 2) : 0,
        ];
    }

    /**
     * Obtenir les meilleures recommandations (par score).
     */
    public static function getTopRecommandations(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return self::with(['offre.entreprise', 'demandeur.user'])
            ->orderBy('score_calculated', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les recommandations les plus cliquées.
     */
    public static function getMostClicked(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return self::with(['offre.entreprise', 'demandeur.user'])
            ->where('a_ete_clique', true)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir le score moyen des recommandations par demandeur.
     */
    public static function getScoreMoyenParDemandeur(int $idDemandeur): float
    {
        return self::where('id_demandeur', $idDemandeur)
            ->avg('score_calculated') ?? 0;
    }

    /**
     * Obtenir la performance de l'algorithme de recommandation.
     */
    public static function getAlgorithmPerformance(): array
    {
        $total = self::count();
        
        if ($total === 0) {
            return [
                'score_moyen' => 0,
                'precision_@1' => 0,
                'precision_@3' => 0,
                'precision_@5' => 0,
            ];
        }
        
        // Calcul de la précision du rang 1 (top recommandation postulée ?)
        $topRecommandations = self::where('rang', 1)->get();
        $topPostulees = $topRecommandations->where('a_ete_postule', true)->count();
        $precisionAt1 = $topRecommandations->count() > 0 
            ? round(($topPostulees / $topRecommandations->count()) * 100, 2) 
            : 0;
        
        // Précision du top 3
        $top3Recommandations = self::whereIn('rang', [1, 2, 3])->get();
        $top3Postulees = $top3Recommandations->where('a_ete_postule', true)->count();
        $precisionAt3 = $top3Recommandations->count() > 0 
            ? round(($top3Postulees / $top3Recommandations->count()) * 100, 2) 
            : 0;
        
        // Précision du top 5
        $top5Recommandations = self::whereIn('rang', [1, 2, 3, 4, 5])->get();
        $top5Postulees = $top5Recommandations->where('a_ete_postule', true)->count();
        $precisionAt5 = $top5Recommandations->count() > 0 
            ? round(($top5Postulees / $top5Recommandations->count()) * 100, 2) 
            : 0;
        
        return [
            'score_moyen' => round(self::avg('score_calculated') ?? 0, 2),
            'precision_@1' => $precisionAt1,
            'precision_@3' => $precisionAt3,
            'precision_@5' => $precisionAt5,
        ];
    }

    /**
     * Scopes
     */

    /**
     * Scope pour les recommandations cliquées.
     */
    public function scopeClique($query)
    {
        return $query->where('a_ete_clique', true);
    }

    /**
     * Scope pour les recommandations non cliquées.
     */
    public function scopeNonClique($query)
    {
        return $query->where('a_ete_clique', false);
    }

    /**
     * Scope pour les recommandations postulées.
     */
    public function scopePostule($query)
    {
        return $query->where('a_ete_postule', true);
    }

    /**
     * Scope pour les recommandations non postulées.
     */
    public function scopeNonPostule($query)
    {
        return $query->where('a_ete_postule', false);
    }

    /**
     * Scope pour les recommandations avec score élevé.
     */
    public function scopeScoreEleve($query, int $seuil = 70)
    {
        return $query->where('score_calculated', '>=', $seuil);
    }

    /**
     * Scope pour les top recommandations (rang <= N).
     */
    public function scopeTop($query, int $rang = 10)
    {
        return $query->where('rang', '<=', $rang);
    }

    /**
     * Scope pour les recommandations récentes.
     */
    public function scopeRecentes($query, int $jours = 30)
    {
        return $query->where('date_recommandation', '>=', now()->subDays($jours));
    }

    /**
     * Scope pour un demandeur spécifique.
     */
    public function scopePourDemandeur($query, int $idDemandeur)
    {
        return $query->where('id_demandeur', $idDemandeur);
    }

    /**
     * Scope pour une offre spécifique.
     */
    public function scopePourOffre($query, int $idOffre)
    {
        return $query->where('id_offre', $idOffre);
    }

    /**
     * Scope pour trier par score.
     */
    public function scopeMeilleurScore($query)
    {
        return $query->orderBy('score_calculated', 'desc');
    }

    /**
     * Scope pour trier par date.
     */
    public function scopePlusRecentes($query)
    {
        return $query->orderBy('date_recommandation', 'desc');
    }
}