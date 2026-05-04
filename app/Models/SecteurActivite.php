<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Candidature;
use App\Models\Offre;
use App\Models\Entreprise;

class SecteurActivite extends Model
{
    use HasFactory;

    /**
     * La clé primaire de la table.
     *
     * @var string
     */
    protected $primaryKey = 'id_sect_act';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'libelle',
        'code_secteur_description',
        'statut',
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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Accesseurs & Mutateurs
     */

    /**
     * Obtenir le libellé en majuscules.
     */
    public function getLibelleMajusculeAttribute(): string
    {
        return strtoupper($this->libelle);
    }

    /**
     * Obtenir le libellé avec son statut.
     */
    public function getLibelleAvecStatutAttribute(): string
    {
        if ($this->statut) {
            return "{$this->libelle} [{$this->statut}]";
        }
        return $this->libelle;
    }

    /**
     * Obtenir la description courte (120 caractères max).
     */
    public function getDescriptionCourteAttribute(): string
    {
        if (!$this->code_secteur_description) {
            return 'Aucune description disponible';
        }
        
        if (strlen($this->code_secteur_description) <= 120) {
            return $this->code_secteur_description;
        }
        
        return substr($this->code_secteur_description, 0, 117) . '...';
    }

    /**
     * Obtenir la classe CSS pour le badge de statut.
     */
    public function getStatutBadgeClassAttribute(): string
    {
        $classes = [
            'actif' => 'badge-success',
            'inactif' => 'badge-danger',
            'en_attente' => 'badge-warning',
        ];

        $statut = strtolower($this->statut ?? 'inactif');
        return $classes[$statut] ?? 'badge-secondary';
    }

    /**
     * Obtenir la couleur associée au secteur (pour graphiques).
     */
    public function getCouleurAttribute(): string
    {
        $couleurs = [
            'Technologie' => '#3B82F6',
            'Santé' => '#10B981',
            'Finance' => '#F59E0B',
            'Éducation' => '#8B5CF6',
            'Industrie' => '#EF4444',
            'Commerce' => '#EC4899',
            'Agriculture' => '#84CC16',
            'Transport' => '#06B6D4',
            'Construction' => '#F97316',
            'Services' => '#6B7280',
        ];

        foreach ($couleurs as $mot => $couleur) {
            if (str_contains($this->libelle, $mot)) {
                return $couleur;
            }
        }

        return '#6B7280';
    }

    /**
     * Vérifier si le secteur est actif.
     */
    public function getIsActifAttribute(): bool
    {
        return strtolower($this->statut ?? '') === 'actif';
    }

    /**
     * Relations
     */

    /**
     * Relation avec les offres d'emploi.
     */
    public function offres(): HasMany
    {
        return $this->hasMany(Offre::class, 'id_sect_act', 'id_sect_act');
    }

    /**
     * Relation avec les offres actives.
     */
    public function offresActives(): HasMany
    {
        return $this->offres()
            ->where('active', true)
            ->where('date_expiration', '>=', now());
    }

    /**
     * Relation avec les entreprises (via les offres).
     */
    public function entreprises()
    {
        return $this->hasManyThrough(
            Entreprise::class,
            Offre::class,
            'id_sect_act',
            'id_entreprise',
            'id_sect_act',
            'id_entreprise'
        )->distinct();
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Vérifier si le secteur a des offres.
     */
    public function hasOffres(): bool
    {
        return $this->offres()->exists();
    }

    /**
     * Compter le nombre d'offres associées.
     */
    public function getOffresCount(): int
    {
        return $this->offres()->count();
    }

    /**
     * Compter le nombre d'offres actives.
     */
    public function getOffresActivesCount(): int
    {
        return $this->offresActives()->count();
    }

    /**
     * Compter le nombre d'entreprises dans ce secteur.
     */
    public function getEntreprisesCount(): int
    {
        return $this->entreprises()->count();
    }

    /**
     * Compter le nombre total de candidatures dans ce secteur.
     */
    public function getTotalCandidaturesCount(): int
    {
        $offresIds = $this->offres()->pluck('id_offre');
        return Candidature::whereIn('id_offre', $offresIds)->count();
    }

    /**
     * Obtenir les offres les plus récentes du secteur.
     */
    public function getRecentOffres(int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return $this->offres()
            ->with('entreprise')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les offres les plus consultées du secteur.
     */
    public function getTopOffres(int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return $this->offres()
            ->with('entreprise')
            ->orderBy('nb_vues', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les statistiques globales du secteur.
     */
    public function getStatsAttribute(): array
    {
        return [
            'total_offres' => $this->getOffresCount(),
            'offres_actives' => $this->getOffresActivesCount(),
            'total_entreprises' => $this->getEntreprisesCount(),
            'total_candidatures' => $this->getTotalCandidaturesCount(),
            'taux_activite' => $this->getOffresCount() > 0 
                ? round(($this->getOffresActivesCount() / $this->getOffresCount()) * 100, 2)
                : 0,
        ];
    }

    /**
     * Obtenir tous les secteurs sous forme de tableau pour les selects.
     */
    public static function getForSelect(): array
    {
        return self::orderBy('libelle')
            ->get()
            ->mapWithKeys(fn($secteur) => [
                $secteur->id_sect_act => $secteur->libelle
            ])
            ->toArray();
    }

    /**
     * Obtenir les secteurs avec le plus d'offres.
     */
    public static function getPlusActifs(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return self::withCount('offres')
            ->having('offres_count', '>', 0)
            ->orderBy('offres_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Activer le secteur.
     */
    public function activer(): bool
    {
        return $this->update(['statut' => 'actif']);
    }

    /**
     * Désactiver le secteur.
     */
    public function desactiver(): bool
    {
        return $this->update(['statut' => 'inactif']);
    }

    /**
     * Obtenir le nom du secteur pour les URLs SEO.
     */
    public function getSlugAttribute(): string
    {
        return strtolower(str_replace(' ', '-', $this->libelle));
    }

    /**
     * Scopes
     */

    /**
     * Scope pour les secteurs actifs.
     */
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope pour les secteurs inactifs.
     */
    public function scopeInactif($query)
    {
        return $query->where('statut', 'inactif');
    }

    /**
     * Scope pour les secteurs avec des offres.
     */
    public function scopeAvecOffres($query)
    {
        return $query->has('offres');
    }

    /**
     * Scope pour les secteurs sans offre.
     */
    public function scopeSansOffres($query)
    {
        return $query->doesntHave('offres');
    }

    /**
     * Scope pour les secteurs populaires (beaucoup d'offres).
     */
    public function scopePopulaires($query, int $minOffres = 5)
    {
        return $query->withCount('offres')
            ->having('offres_count', '>=', $minOffres);
    }

    /**
     * Scope pour la recherche textuelle.
     */
    public function scopeRecherche($query, string $terme)
    {
        return $query->where('libelle', 'LIKE', "%{$terme}%")
            ->orWhere('code_secteur_description', 'LIKE', "%{$terme}%");
    }
}