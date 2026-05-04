<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Demandeur;
use App\Models\Offre;

class Appariement extends Model
{
    use HasFactory;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'appariements';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'statut',
        'date_appariement',
        'commentaire',
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
        'date_appariement' => 'date',
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
            'valide' => 'Validé',
            'rejete' => 'Rejeté',
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
            'valide' => 'badge-success',
            'rejete' => 'badge-danger',
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
            'valide' => '✅',
            'rejete' => '❌',
        ];

        return $icones[$this->statut] ?? '❓';
    }

    /**
     * Vérifier si l'appariement est en attente.
     */
    public function getEstEnAttenteAttribute(): bool
    {
        return $this->statut === 'en_attente';
    }

    /**
     * Vérifier si l'appariement est validé.
     */
    public function getEstValideAttribute(): bool
    {
        return $this->statut === 'valide';
    }

    /**
     * Vérifier si l'appariement est rejeté.
     */
    public function getEstRejeteAttribute(): bool
    {
        return $this->statut === 'rejete';
    }

    /**
     * Obtenir l'âge de l'appariement (en jours).
     */
    public function getAgeJoursAttribute(): int
    {
        return $this->date_appariement->diffInDays(now());
    }

    /**
     * Vérifier si l'appariement est récent (moins de 7 jours).
     */
    public function getEstRecenteAttribute(): bool
    {
        return $this->age_jours <= 7;
    }

    /**
     * Obtenir la date formatée.
     */
    public function getDateFormateeAttribute(): string
    {
        return $this->date_appariement->format('d/m/Y');
    }

    /**
     * Obtenir le commentaire tronqué (50 caractères).
     */
    public function getCommentaireCourtAttribute(): string
    {
        if (!$this->commentaire) {
            return 'Aucun commentaire';
        }
        
        if (strlen($this->commentaire) <= 50) {
            return $this->commentaire;
        }
        
        return substr($this->commentaire, 0, 47) . '...';
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
     * Valider l'appariement.
     */
    public function valider(): bool
    {
        return $this->update(['statut' => 'valide']);
    }

    /**
     * Rejeter l'appariement.
     */
    public function rejeter(): bool
    {
        return $this->update(['statut' => 'rejete']);
    }

    /**
     * Remettre en attente.
     */
    public function remettreEnAttente(): bool
    {
        return $this->update(['statut' => 'en_attente']);
    }

    /**
     * Ajouter un commentaire.
     */
    public function ajouterCommentaire(string $commentaire): bool
    {
        return $this->update(['commentaire' => $commentaire]);
    }

    /**
     * Obtenir les appariements pour un demandeur.
     */
    public static function getForDemandeur(int $idDemandeur, ?string $statut = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = self::with(['offre.entreprise', 'offre.typeContrat'])
            ->where('id_demandeur', $idDemandeur)
            ->orderBy('date_appariement', 'desc');
        
        if ($statut) {
            $query->where('statut', $statut);
        }
        
        return $query->get();
    }

    /**
     * Obtenir les appariements pour une offre.
     */
    public static function getForOffre(int $idOffre, ?string $statut = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = self::with(['demandeur.user'])
            ->where('id_offre', $idOffre)
            ->orderBy('date_appariement', 'desc');
        
        if ($statut) {
            $query->where('statut', $statut);
        }
        
        return $query->get();
    }

    /**
     * Obtenir les appariements en attente.
     */
    public static function getEnAttente(int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        return self::with(['offre.entreprise', 'demandeur.user'])
            ->where('statut', 'en_attente')
            ->orderBy('date_appariement', 'asc')
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
        $valides = self::where('statut', 'valide')->count();
        $rejetes = self::where('statut', 'rejete')->count();
        
        $dureeMoyenneAttente = self::where('statut', 'valide')
            ->orWhere('statut', 'rejete')
            ->selectRaw('AVG(DATEDIFF(updated_at, created_at)) as duree_moyenne')
            ->value('duree_moyenne');
        
        return [
            'total' => $total,
            'en_attente' => $enAttente,
            'valides' => $valides,
            'rejetes' => $rejetes,
            'taux_succes' => $total > 0 ? round(($valides / $total) * 100, 2) : 0,
            'duree_moyenne_attente' => round($dureeMoyenneAttente ?? 0, 1),
        ];
    }

    /**
     * Vérifier si un appariement existe déjà.
     */
    public static function existeDeja(int $idOffre, int $idDemandeur): bool
    {
        return self::where('id_offre', $idOffre)
            ->where('id_demandeur', $idDemandeur)
            ->exists();
    }

    /**
     * Créer ou récupérer un appariement.
     */
    public static function createOrGet(int $idOffre, int $idDemandeur): self
    {
        $existant = self::where('id_offre', $idOffre)
            ->where('id_demandeur', $idDemandeur)
            ->first();
        
        if ($existant) {
            return $existant;
        }
        
        return self::create([
            'id_offre' => $idOffre,
            'id_demandeur' => $idDemandeur,
            'date_appariement' => now(),
            'statut' => 'en_attente',
        ]);
    }

    /**
     * Scopes
     */

    /**
     * Scope pour les appariements en attente.
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    /**
     * Scope pour les appariements validés.
     */
    public function scopeValide($query)
    {
        return $query->where('statut', 'valide');
    }

    /**
     * Scope pour les appariements rejetés.
     */
    public function scopeRejete($query)
    {
        return $query->where('statut', 'rejete');
    }

    /**
     * Scope pour les appariements récents.
     */
    public function scopeRecents($query, int $jours = 30)
    {
        return $query->where('date_appariement', '>=', now()->subDays($jours));
    }

    /**
     * Scope pour les appariements par période.
     */
    public function scopePeriode($query, string $debut, string $fin)
    {
        return $query->whereBetween('date_appariement', [$debut, $fin]);
    }

    /**
     * Scope pour les appariements avec commentaire.
     */
    public function scopeAvecCommentaire($query)
    {
        return $query->whereNotNull('commentaire')
            ->where('commentaire', '!=', '');
    }

    /**
     * Scope pour les appariements sans commentaire.
     */
    public function scopeSansCommentaire($query)
    {
        return $query->whereNull('commentaire')
            ->orWhere('commentaire', '');
    }
}