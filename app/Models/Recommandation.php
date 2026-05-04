<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Offre;
use App\Models\Demandeur;

class Recommandation extends Model
{
    use HasFactory;

    /**
     * La clé primaire de la table.
     *
     * @var string
     */
    protected $primaryKey = 'id_recommandation';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'score_final',
        'rang',
        'date_recommandation',
        'statut',
        'id_offre',
        'id_demandeur',
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
        'score_final' => 'float',
        'rang' => 'integer',
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
        if ($this->score_final === null) {
            return 'Non évalué';
        }
        return number_format($this->score_final, 1, ',', ' ') . '/100';
    }

    /**
     * Obtenir le score arrondi.
     */
    public function getScoreArrondiAttribute(): int
    {
        return (int) round($this->score_final ?? 0);
    }

    /**
     * Obtenir le niveau de correspondance.
     */
    public function getNiveauCorrespondanceAttribute(): string
    {
        if ($this->score_final === null) {
            return 'Non évalué';
        }
        
        if ($this->score_final >= 85) {
            return 'Excellent';
        } elseif ($this->score_final >= 70) {
            return 'Très bon';
        } elseif ($this->score_final >= 55) {
            return 'Bon';
        } elseif ($this->score_final >= 40) {
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
            'Non évalué' => 'badge-gray',
        ];

        return $classes[$this->niveau_correspondance] ?? 'badge-secondary';
    }

    /**
     * Obtenir la couleur selon le score.
     */
    public function getScoreCouleurAttribute(): string
    {
        if ($this->score_final === null) {
            return '#9CA3AF';
        }
        
        if ($this->score_final >= 75) {
            return '#10B981';
        } elseif ($this->score_final >= 60) {
            return '#3B82F6';
        } elseif ($this->score_final >= 45) {
            return '#F59E0B';
        } elseif ($this->score_final >= 30) {
            return '#F97316';
        }
        return '#EF4444';
    }

    /**
     * Obtenir le libellé du statut.
     */
    public function getStatutLibelleAttribute(): string
    {
        $statuts = [
            'en_attente' => 'En attente',
            'acceptee' => 'Acceptée',
            'refusee' => 'Refusée',
        ];

        return $statuts[$this->statut] ?? 'Inconnu';
    }

    /**
     * Obtenir la classe CSS pour le badge de statut.
     */
    public function getStatutBadgeClassAttribute(): string
    {
        $classes = [
            'en_attente' => 'badge-warning',
            'acceptee' => 'badge-success',
            'refusee' => 'badge-danger',
        ];

        return $classes[$this->statut] ?? 'badge-secondary';
    }

    /**
     * Vérifier si la recommandation est en attente.
     */
    public function getEstEnAttenteAttribute(): bool
    {
        return $this->statut === 'en_attente';
    }

    /**
     * Vérifier si la recommandation est acceptée.
     */
    public function getEstAccepteeAttribute(): bool
    {
        return $this->statut === 'acceptee';
    }

    /**
     * Vérifier si la recommandation est refusée.
     */
    public function getEstRefuseeAttribute(): bool
    {
        return $this->statut === 'refusee';
    }

    /**
     * Obtenir le rang avec suffixe.
     */
    public function getRangFormateAttribute(): string
    {
        if (!$this->rang) {
            return 'Non classé';
        }
        
        $suffixes = ['er', 'ème', 'ème', 'ème', 'ème'];
        $suffixe = $this->rang == 1 ? 'er' : 'ème';
        
        return $this->rang . $suffixe;
    }

    /**
     * Obtenir l'ancienneté de la recommandation (en jours).
     */
    public function getAncienneteJoursAttribute(): int
    {
        return $this->date_recommandation->diffInDays(now());
    }

    /**
     * Vérifier si la recommandation est récente (moins de 7 jours).
     */
    public function getEstRecenteAttribute(): bool
    {
        return $this->anciennete_jours <= 7;
    }

    /**
     * Vérifier si la recommandation est toujours valide (offre non expirée).
     */
    public function getEstValideAttribute(): bool
    {
        return $this->offre && !$this->offre->is_expiree && $this->offre->active;
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
     * Relation avec le demandeur.
     */
    public function demandeur(): BelongsTo
    {
        return $this->belongsTo(Demandeur::class, 'id_demandeur', 'id_demandeur');
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Accepter la recommandation.
     */
    public function accepter(): bool
    {
        return $this->update(['statut' => 'acceptee']);
    }

    /**
     * Refuser la recommandation.
     */
    public function refuser(): bool
    {
        return $this->update(['statut' => 'refusee']);
    }

    /**
     * Remettre en attente.
     */
    public function remettreEnAttente(): bool
    {
        return $this->update(['statut' => 'en_attente']);
    }

    /**
     * Obtenir les recommandations pour un demandeur.
     */
    public static function getForDemandeur(int $idDemandeur, ?string $statut = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = self::with(['offre.entreprise', 'offre.typeContrat'])
            ->where('id_demandeur', $idDemandeur)
            ->orderBy('score_final', 'desc')
            ->orderBy('rang');
        
        if ($statut) {
            $query->where('statut', $statut);
        }
        
        return $query->get();
    }

    /**
     * Obtenir les meilleures recommandations (top N).
     */
    public static function getTopRecommandations(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return self::with(['offre.entreprise', 'demandeur.user'])
            ->where('statut', 'en_attente')
            ->orderBy('score_final', 'desc')
            ->orderBy('rang')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir le taux de conversion (acceptations / total).
     */
    public static function getTauxConversion(): float
    {
        $total = self::count();
        if ($total === 0) {
            return 0;
        }
        
        $acceptees = self::where('statut', 'acceptee')->count();
        return round(($acceptees / $total) * 100, 2);
    }

    /**
     * Obtenir les statistiques globales.
     */
    public static function getGlobalStats(): array
    {
        $total = self::count();
        $enAttente = self::where('statut', 'en_attente')->count();
        $acceptees = self::where('statut', 'acceptee')->count();
        $refusees = self::where('statut', 'refusee')->count();
        
        $scoreMoyen = self::avg('score_final') ?? 0;
        
        return [
            'total' => $total,
            'en_attente' => $enAttente,
            'acceptees' => $acceptees,
            'refusees' => $refusees,
            'taux_acceptation' => $total > 0 ? round(($acceptees / $total) * 100, 2) : 0,
            'score_moyen' => round($scoreMoyen, 2),
        ];
    }

    /**
     * Scopes
     */

    /**
     * Scope pour les recommandations en attente.
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    /**
     * Scope pour les recommandations acceptées.
     */
    public function scopeAcceptees($query)
    {
        return $query->where('statut', 'acceptee');
    }

    /**
     * Scope pour les recommandations refusées.
     */
    public function scopeRefusees($query)
    {
        return $query->where('statut', 'refusee');
    }

    /**
     * Scope pour les recommandations avec score élevé.
     */
    public function scopeScoreEleve($query, float $seuil = 70)
    {
        return $query->where('score_final', '>=', $seuil);
    }

    /**
     * Scope pour les recommandations récentes.
     */
    public function scopeRecente($query, int $jours = 7)
    {
        return $query->where('date_recommandation', '>=', now()->subDays($jours));
    }

    /**
     * Scope pour les recommandations par ordre de score.
     */
    public function scopeMeilleurScore($query)
    {
        return $query->orderBy('score_final', 'desc');
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
}