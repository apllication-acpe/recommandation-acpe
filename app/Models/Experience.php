<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Demandeur;

class Experience extends Model
{
    use HasFactory;

    /**
     * La clé primaire de la table.
     *
     * @var string
     */
    protected $primaryKey = 'id_experience';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_demandeur',
        'poste_occupe',
        'entreprise',
        'date_debut',
        'date_fin',
        'est_en_cours',
        'description',
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
        'date_debut' => 'date',
        'date_fin' => 'date',
        'est_en_cours' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Accesseurs & Mutateurs
     */

    /**
     * Obtenir la date de début formatée.
     */
    public function getDateDebutFormateeAttribute(): string
    {
        return $this->date_debut->format('F Y');
    }

    /**
     * Obtenir la date de début au format court.
     */
    public function getDateDebutCourteAttribute(): string
    {
        return $this->date_debut->format('m/Y');
    }

    /**
     * Obtenir la date de fin formatée.
     */
    public function getDateFinFormateeAttribute(): string
    {
        if ($this->est_en_cours) {
            return 'Présent';
        }
        
        if (!$this->date_fin) {
            return 'Non spécifiée';
        }
        
        return $this->date_fin->format('F Y');
    }

    /**
     * Obtenir la date de fin au format court.
     */
    public function getDateFinCourteAttribute(): string
    {
        if ($this->est_en_cours) {
            return 'Présent';
        }
        
        if (!$this->date_fin) {
            return 'N/A';
        }
        
        return $this->date_fin->format('m/Y');
    }

    /**
     * Obtenir la période complète.
     */
    public function getPeriodeAttribute(): string
    {
        return $this->date_debut_formatee . ' - ' . $this->date_fin_formatee;
    }

    /**
     * Obtenir la période courte.
     */
    public function getPeriodeCourteAttribute(): string
    {
        return $this->date_debut_courte . ' - ' . $this->date_fin_courte;
    }

    /**
     * Obtenir la durée de l'expérience (en années et mois).
     */
    public function getDureeAttribute(): string
    {
        $dateDebut = $this->date_debut;
        $dateFin = $this->est_en_cours ? now() : $this->date_fin;
        
        if (!$dateFin) {
            return 'Durée non spécifiée';
        }
        
        $diff = $dateDebut->diff($dateFin);
        
        $years = $diff->y;
        $months = $diff->m;
        
        $parts = [];
        if ($years > 0) {
            $parts[] = $years . ' ' . ($years > 1 ? 'ans' : 'an');
        }
        if ($months > 0) {
            $parts[] = $months . ' ' . ($months > 1 ? 'mois' : 'mois');
        }
        
        return !empty($parts) ? implode(' et ', $parts) : 'Moins d\'un mois';
    }

    /**
     * Obtenir la durée en mois.
     */
    public function getDureeMoisAttribute(): int
    {
        $dateDebut = $this->date_debut;
        $dateFin = $this->est_en_cours ? now() : $this->date_fin;
        
        if (!$dateFin) {
            return 0;
        }
        
        return $dateDebut->diffInMonths($dateFin);
    }

    /**
     * Obtenir la durée en années (décimale).
     */
    public function getDureeAnneesAttribute(): float
    {
        return round($this->duree_mois / 12, 1);
    }

    /**
     * Obtenir le poste formaté.
     */
    public function getPosteFormateAttribute(): string
    {
        return ucfirst($this->poste_occupe);
    }

    /**
     * Obtenir l'entreprise formatée.
     */
    public function getEntrepriseFormateeAttribute(): string
    {
        return ucfirst($this->entreprise);
    }

    /**
     * Obtenir la description tronquée (150 caractères).
     */
    public function getDescriptionCourteAttribute(): string
    {
        if (!$this->description) {
            return 'Aucune description';
        }
        
        if (strlen($this->description) <= 150) {
            return $this->description;
        }
        
        return substr($this->description, 0, 147) . '...';
    }

    /**
     * Vérifier si l'expérience est récente (moins de 2 ans).
     */
    public function getEstRecenteAttribute(): bool
    {
        $dateFin = $this->est_en_cours ? now() : $this->date_fin;
        
        if (!$dateFin) {
            return false;
        }
        
        return $dateFin->diffInYears(now()) <= 2;
    }

    /**
     * Vérifier si l'expérience est ancienne (plus de 5 ans).
     */
    public function getEstAncienneAttribute(): bool
    {
        $dateFin = $this->est_en_cours ? now() : $this->date_fin;
        
        if (!$dateFin) {
            return false;
        }
        
        return $dateFin->diffInYears(now()) > 5;
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
     * Méthodes utilitaires
     */

    /**
     * Marquer l'expérience comme en cours.
     */
    public function marquerEnCours(): bool
    {
        return $this->update([
            'est_en_cours' => true,
            'date_fin' => null,
        ]);
    }

    /**
     * Terminer l'expérience.
     */
    public function terminer(?string $dateFin = null): bool
    {
        $date = $dateFin ?? now()->format('Y-m-d');
        
        return $this->update([
            'est_en_cours' => false,
            'date_fin' => $date,
        ]);
    }

    /**
     * Obtenir les expériences d'un demandeur.
     */
    public static function getForDemandeur(int $idDemandeur): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('id_demandeur', $idDemandeur)
            ->orderBy('date_debut', 'desc')
            ->orderBy('est_en_cours', 'desc')
            ->get();
    }

    /**
     * Obtenir les expériences récentes d'un demandeur.
     */
    public static function getRecentForDemandeur(int $idDemandeur, int $limit = 3): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('id_demandeur', $idDemandeur)
            ->orderBy('date_debut', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir l'expérience totale en années pour un demandeur.
     */
    public static function getTotalExperienceForDemandeur(int $idDemandeur): float
    {
        $experiences = self::where('id_demandeur', $idDemandeur)->get();
        $totalMois = 0;
        
        foreach ($experiences as $experience) {
            $totalMois += $experience->duree_mois;
        }
        
        return round($totalMois / 12, 1);
    }

    /**
     * Vérifier si le demandeur a de l'expérience dans un domaine spécifique.
     */
    public static function hasExperienceIn(int $idDemandeur, string $keyword): bool
    {
        return self::where('id_demandeur', $idDemandeur)
            ->where(function ($query) use ($keyword) {
                $query->where('poste_occupe', 'LIKE', "%{$keyword}%")
                    ->orWhere('entreprise', 'LIKE', "%{$keyword}%")
                    ->orWhere('description', 'LIKE', "%{$keyword}%");
            })
            ->exists();
    }

    /**
     * Scopes
     */

    /**
     * Scope pour les expériences en cours.
     */
    public function scopeEnCours($query)
    {
        return $query->where('est_en_cours', true);
    }

    /**
     * Scope pour les expériences terminées.
     */
    public function scopeTerminees($query)
    {
        return $query->where('est_en_cours', false);
    }

    /**
     * Scope pour les expériences récentes.
     */
    public function scopeRecentes($query, int $annees = 2)
    {
        $dateLimite = now()->subYears($annees);
        
        return $query->where(function ($q) use ($dateLimite) {
            $q->where('est_en_cours', true)
                ->orWhere('date_fin', '>=', $dateLimite);
        });
    }

    /**
     * Scope pour les expériences par poste.
     */
    public function scopeParPoste($query, string $poste)
    {
        return $query->where('poste_occupe', 'LIKE', "%{$poste}%");
    }

    /**
     * Scope pour les expériences par entreprise.
     */
    public function scopeParEntreprise($query, string $entreprise)
    {
        return $query->where('entreprise', 'LIKE', "%{$entreprise}%");
    }

    /**
     * Scope pour les expériences d'une période spécifique.
     */
    public function scopePeriode($query, string $debut, string $fin)
    {
        return $query->whereBetween('date_debut', [$debut, $fin]);
    }

    /**
     * Scope pour les expériences avec description.
     */
    public function scopeAvecDescription($query)
    {
        return $query->whereNotNull('description')
            ->where('description', '!=', '');
    }
}