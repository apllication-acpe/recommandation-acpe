<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Offre;
use App\Models\Demandeur;

class Candidature extends Model
{
    use HasFactory;

    /**
     * La clé primaire de la table.
     *
     * @var string
     */
    protected $primaryKey = 'id_candidature';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_demandeur',
        'id_offre',
        'statut',
        'message_motivation',
        'date_candidature',
        'date_reponse',
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
        'date_candidature' => 'datetime',
        'date_reponse' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Accesseurs & Mutateurs
     */

    /**
     * Obtenir le libellé du statut.
     */
    public function getStatutLibelleAttribute(): string
    {
        $statuts = [
            'en_attente' => 'En attente',
            'acceptee' => 'Acceptée',
            'refusee' => 'Refusée',
            'annulee' => 'Annulée',
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
            'annulee' => 'badge-gray',
        ];

        return $classes[$this->statut] ?? 'badge-secondary';
    }

    /**
     * Obtenir l'icône du statut.
     */
    public function getStatutIconeAttribute(): string
    {
        $icones = [
            'en_attente' => '⏳',
            'acceptee' => '✅',
            'refusee' => '❌',
            'annulee' => '🚫',
        ];

        return $icones[$this->statut] ?? '❓';
    }

    /**
     * Vérifier si la candidature est en attente.
     */
    public function getEstEnAttenteAttribute(): bool
    {
        return $this->statut === 'en_attente';
    }

    /**
     * Vérifier si la candidature est acceptée.
     */
    public function getEstAccepteeAttribute(): bool
    {
        return $this->statut === 'acceptee';
    }

    /**
     * Vérifier si la candidature est refusée.
     */
    public function getEstRefuseeAttribute(): bool
    {
        return $this->statut === 'refusee';
    }

    /**
     * Vérifier si la candidature est annulée.
     */
    public function getEstAnnuleeAttribute(): bool
    {
        return $this->statut === 'annulee';
    }

    /**
     * Obtenir la date de candidature formatée.
     */
    public function getDateCandidatureFormateeAttribute(): string
    {
        return $this->date_candidature->format('d/m/Y à H:i');
    }

    /**
     * Obtenir la date de réponse formatée.
     */
    public function getDateReponseFormateeAttribute(): string
    {
        if (!$this->date_reponse) {
            return 'Non traitée';
        }
        return $this->date_reponse->format('d/m/Y à H:i');
    }

    /**
     * Obtenir le message de motivation tronqué (100 caractères).
     */
    public function getMessageMotivationCourtAttribute(): string
    {
        if (!$this->message_motivation) {
            return 'Aucun message de motivation';
        }
        
        if (strlen($this->message_motivation) <= 100) {
            return $this->message_motivation;
        }
        
        return substr($this->message_motivation, 0, 97) . '...';
    }

    /**
     * Obtenir le temps de réponse (en jours).
     */
    public function getTempsReponseJoursAttribute(): ?float
    {
        if (!$this->date_reponse) {
            return null;
        }
        
        return round($this->date_candidature->diffInDays($this->date_reponse), 1);
    }

    /**
     * Vérifier si la candidature a reçu une réponse.
     */
    public function getAReponseAttribute(): bool
    {
        return $this->date_reponse !== null;
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
     * Accepter la candidature.
     */
    public function accepter(): bool
    {
        return $this->update([
            'statut' => 'acceptee',
            'date_reponse' => now(),
        ]);
    }

    /**
     * Refuser la candidature.
     */
    public function refuser(): bool
    {
        return $this->update([
            'statut' => 'refusee',
            'date_reponse' => now(),
        ]);
    }

    /**
     * Annuler la candidature.
     */
    public function annuler(): bool
    {
        return $this->update([
            'statut' => 'annulee',
            'date_reponse' => now(),
        ]);
    }

    /**
     * Remettre en attente.
     */
    public function remettreEnAttente(): bool
    {
        return $this->update([
            'statut' => 'en_attente',
            'date_reponse' => null,
        ]);
    }

    /**
     * Obtenir les candidatures d'un demandeur.
     */
    public static function getForDemandeur(int $idDemandeur, ?string $statut = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = self::with(['offre.entreprise', 'offre.typeContrat'])
            ->where('id_demandeur', $idDemandeur)
            ->orderBy('date_candidature', 'desc');
        
        if ($statut) {
            $query->where('statut', $statut);
        }
        
        return $query->get();
    }

    /**
     * Obtenir les candidatures d'une offre.
     */
    public static function getForOffre(int $idOffre, ?string $statut = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = self::with(['demandeur.user'])
            ->where('id_offre', $idOffre)
            ->orderBy('date_candidature', 'desc');
        
        if ($statut) {
            $query->where('statut', $statut);
        }
        
        return $query->get();
    }

    /**
     * Obtenir les candidatures en attente.
     */
    public static function getEnAttente(int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        return self::with(['offre.entreprise', 'demandeur.user'])
            ->where('statut', 'en_attente')
            ->orderBy('date_candidature', 'asc')
            ->limit($limit)
            ->get();
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
        $annulees = self::where('statut', 'annulee')->count();
        
        $tempsReponseMoyen = self::whereNotNull('date_reponse')
            ->selectRaw('AVG(TIMESTAMPDIFF(DAY, date_candidature, date_reponse)) as temps_moyen')
            ->value('temps_moyen');
        
        return [
            'total' => $total,
            'en_attente' => $enAttente,
            'acceptees' => $acceptees,
            'refusees' => $refusees,
            'annulees' => $annulees,
            'taux_succes' => $total > 0 ? round(($acceptees / $total) * 100, 2) : 0,
            'temps_reponse_moyen' => round($tempsReponseMoyen ?? 0, 1),
        ];
    }

    /**
     * Obtenir les statistiques par offre.
     */
    public static function getStatsByOffre(int $idOffre): array
    {
        $query = self::where('id_offre', $idOffre);
        
        $total = $query->count();
        $enAttente = (clone $query)->where('statut', 'en_attente')->count();
        $acceptees = (clone $query)->where('statut', 'acceptee')->count();
        $refusees = (clone $query)->where('statut', 'refusee')->count();
        
        return [
            'total' => $total,
            'en_attente' => $enAttente,
            'acceptees' => $acceptees,
            'refusees' => $refusees,
            'taux_conversion' => $total > 0 ? round(($acceptees / $total) * 100, 2) : 0,
        ];
    }

    /**
     * Vérifier si une candidature existe déjà.
     */
    public static function existeDeja(int $idOffre, int $idDemandeur): bool
    {
        return self::where('id_offre', $idOffre)
            ->where('id_demandeur', $idDemandeur)
            ->exists();
    }

    /**
     * Scopes
     */

    /**
     * Scope pour les candidatures en attente.
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    /**
     * Scope pour les candidatures acceptées.
     */
    public function scopeAcceptees($query)
    {
        return $query->where('statut', 'acceptee');
    }

    /**
     * Scope pour les candidatures refusées.
     */
    public function scopeRefusees($query)
    {
        return $query->where('statut', 'refusee');
    }

    /**
     * Scope pour les candidatures récentes.
     */
    public function scopeRecentes($query, int $jours = 30)
    {
        return $query->where('date_candidature', '>=', now()->subDays($jours));
    }

    /**
     * Scope pour les candidatures sans réponse.
     */
    public function scopeSansReponse($query)
    {
        return $query->whereNull('date_reponse');
    }

    /**
     * Scope pour les candidatures avec réponse.
     */
    public function scopeAvecReponse($query)
    {
        return $query->whereNotNull('date_reponse');
    }

    /**
     * Scope pour les candidatures d'une période.
     */
    public function scopePeriode($query, string $debut, string $fin)
    {
        return $query->whereBetween('date_candidature', [$debut, $fin]);
    }
}