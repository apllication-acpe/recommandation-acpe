<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Demandeur;
use App\Models\Qualification;

class QualificationDemandeur extends Pivot
{
    use HasFactory;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'qualification_demandeur';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'date_obtention',
        'organisme',
        'niveau_atteint',
        'date_expiration',
        'numero_reference',
        'id_demandeur',
        'id_qualification',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_obtention' => 'date',
        'date_expiration' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Accesseurs & Mutateurs
     */

    /**
     * Vérifier si la qualification est expirée.
     */
    public function getEstExpireeAttribute(): bool
    {
        if (!$this->date_expiration) {
            return false;
        }
        return $this->date_expiration->isPast();
    }

    /**
     * Vérifier si la qualification est valide (non expirée).
     */
    public function getEstValideAttribute(): bool
    {
        return !$this->est_expiree;
    }

    /**
     * Obtenir le nombre de jours restants avant expiration.
     */
    public function getJoursRestantsAttribute(): ?int
    {
        if (!$this->date_expiration) {
            return null;
        }
        
        if ($this->est_expiree) {
            return 0;
        }
        
        return now()->diffInDays($this->date_expiration);
    }

    /**
     * Obtenir le statut de validité formaté.
     */
    public function getStatutValiditeAttribute(): string
    {
        if (!$this->date_expiration) {
            return 'Permanent';
        }
        
        if ($this->est_expiree) {
            return 'Expirée';
        }
        
        $jours = $this->jours_restants;
        
        if ($jours <= 30) {
            return 'Expire bientôt';
        }
        
        return 'Valide';
    }

    /**
     * Obtenir la classe CSS pour le badge de validité.
     */
    public function getValiditeBadgeClassAttribute(): string
    {
        $classes = [
            'Permanent' => 'badge-info',
            'Valide' => 'badge-success',
            'Expire bientôt' => 'badge-warning',
            'Expirée' => 'badge-danger',
        ];

        return $classes[$this->statut_validite] ?? 'badge-secondary';
    }

    /**
     * Obtenir l'année d'obtention.
     */
    public function getAnneeObtentionAttribute(): ?int
    {
        return $this->date_obtention?->year;
    }

    /**
     * Obtenir le niveau atteint formaté.
     */
    public function getNiveauAtteintFormateAttribute(): string
    {
        $niveaux = [
            'TB' => 'Très Bien',
            'B' => 'Bien',
            'AB' => 'Assez Bien',
            'P' => 'Passable',
            'F' => 'Validé',
            'M' => 'Mention Bien',
            'MTB' => 'Mention Très Bien',
            'DIST' => 'Distinction',
            'HON' => 'Honorable',
            'EXCEL' => 'Excellence',
        ];

        $niveau = strtoupper($this->niveau_atteint ?? '');
        
        return $niveaux[$niveau] ?? $this->niveau_atteint ?? 'Non spécifié';
    }

    /**
     * Obtenir l'organisme formaté.
     */
    public function getOrganismeFormateAttribute(): string
    {
        if (!$this->organisme) {
            return 'Non spécifié';
        }
        
        return strtoupper($this->organisme);
    }

    /**
     * Obtenir l'ancienneté de la qualification (en années).
     */
    public function getAncienneteAttribute(): ?int
    {
        if (!$this->date_obtention) {
            return null;
        }
        
        return $this->date_obtention->diffInYears(now());
    }

    /**
     * Obtenir le libellé complet de la qualification.
     */
    public function getLibelleCompletAttribute(): string
    {
        $parts = [$this->qualification->intitule];
        
        if ($this->organisme) {
            $parts[] = "- {$this->organisme}";
        }
        
        if ($this->annee_obtention) {
            $parts[] = "({$this->annee_obtention})";
        }
        
        return implode(' ', $parts);
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
     * Relation avec la qualification.
     */
    public function qualification(): BelongsTo
    {
        return $this->belongsTo(Qualification::class, 'id_qualification', 'id_qualification');
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Vérifier si la qualification est récente (moins de 2 ans).
     */
    public function isRecente(): bool
    {
        $anciennete = $this->anciennete;
        
        if ($anciennete === null) {
            return false;
        }
        
        return $anciennete <= 2;
    }

    /**
     * Vérifier si la qualification peut être renouvelée.
     */
    public function isRenouvelable(): bool
    {
        // Une qualification est renouvelable si elle expire et a une date d'expiration
        return $this->date_expiration !== null && $this->est_expiree;
    }

    /**
     * Vérifier si le niveau atteint est excellent.
     */
    public function isExcellent(): bool
    {
        $excellents = ['TB', 'MTB', 'EXCEL', 'DIST'];
        return in_array(strtoupper($this->niveau_atteint ?? ''), $excellents);
    }

    /**
     * Prolonger la qualification (ajouter des années).
     */
    public function prolonger(int $annees): bool
    {
        if (!$this->date_expiration) {
            return false;
        }
        
        $this->date_expiration = $this->date_expiration->addYears($annees);
        return $this->save();
    }

    /**
     * Obtenir les qualifications expirées pour un demandeur.
     */
    public static function getExpireesPourDemandeur(int $idDemandeur): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('id_demandeur', $idDemandeur)
            ->whereNotNull('date_expiration')
            ->where('date_expiration', '<', now())
            ->with('qualification')
            ->get();
    }

    /**
     * Obtenir les qualifications bientôt expirées (30 jours).
     */
    public static function getBientotExpirees(): \Illuminate\Database\Eloquent\Collection
    {
        $dateLimite = now()->addDays(30);
        
        return self::whereNotNull('date_expiration')
            ->where('date_expiration', '<=', $dateLimite)
            ->where('date_expiration', '>', now())
            ->with(['demandeur.user', 'qualification'])
            ->get();
    }

    /**
     * Scopes
     */

    /**
     * Scope pour les qualifications valides (non expirées).
     */
    public function scopeValide($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('date_expiration')
              ->orWhere('date_expiration', '>', now());
        });
    }

    /**
     * Scope pour les qualifications expirées.
     */
    public function scopeExpiree($query)
    {
        return $query->whereNotNull('date_expiration')
            ->where('date_expiration', '<', now());
    }

    /**
     * Scope pour les qualifications avec niveau excellent.
     */
    public function scopeExcellent($query)
    {
        $excellents = ['TB', 'MTB', 'EXCEL', 'DIST'];
        
        return $query->whereIn('niveau_atteint', $excellents);
    }

    /**
     * Scope pour les qualifications d'un organisme spécifique.
     */
    public function scopeParOrganisme($query, string $organisme)
    {
        return $query->where('organisme', 'LIKE', "%{$organisme}%");
    }

    /**
     * Scope pour les qualifications obtenues après une année.
     */
    public function scopeApresAnnee($query, int $annee)
    {
        return $query->whereYear('date_obtention', '>=', $annee);
    }

    /**
     * Scope pour les qualifications obtenues avant une année.
     */
    public function scopeAvantAnnee($query, int $annee)
    {
        return $query->whereYear('date_obtention', '<=', $annee);
    }

    /**
     * Scope pour les qualifications récentes (2 dernières années).
     */
    public function scopeRecente($query)
    {
        $dateLimite = now()->subYears(2);
        return $query->where('date_obtention', '>=', $dateLimite);
    }
}