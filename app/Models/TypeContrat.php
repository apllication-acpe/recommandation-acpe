<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Offre;

class TypeContrat extends Model
{
    use HasFactory;

    /**
     * La clé primaire de la table.
     *
     * @var string
     */
    protected $primaryKey = 'id_type_cont';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'libelle',
        'code',
        'duree',
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
     * Obtenir le libellé complet avec la durée.
     */
    public function getLibelleCompletAttribute(): string
    {
        if ($this->duree) {
            return "{$this->libelle} ({$this->duree})";
        }
        return $this->libelle;
    }

    /**
     * Obtenir la durée formatée.
     */
    public function getDureeFormateeAttribute(): string
    {
        if (!$this->duree) {
            return 'Non spécifiée';
        }

        $durees = [
            'Indéterminée' => 'Contrat à durée indéterminée',
            'Déterminée' => 'Contrat à durée déterminée',
            'Temporaire' => 'Contrat temporaire',
            'Mission' => 'Contrat de mission',
            'Contrat pro' => 'Contrat professionnel',
        ];

        return $durees[$this->duree] ?? $this->duree;
    }

    /**
     * Vérifier si c'est un CDI.
     */
    public function getIsCDIAttribute(): bool
    {
        return $this->code === 'CDI';
    }

    /**
     * Vérifier si c'est un CDD.
     */
    public function getIsCDDAttribute(): bool
    {
        return $this->code === 'CDD';
    }

    /**
     * Vérifier si c'est un stage.
     */
    public function getIsStageAttribute(): bool
    {
        return $this->code === 'STAGE';
    }

    /**
     * Obtenir la couleur associée au type de contrat (pour l'affichage).
     */
    public function getCouleurAttribute(): string
    {
        $couleurs = [
            'CDI' => 'green',
            'CDD' => 'blue',
            'STAGE' => 'orange',
            'FREELANCE' => 'purple',
            'ALTERNANCE' => 'teal',
        ];

        return $couleurs[$this->code] ?? 'gray';
    }

    /**
     * Obtenir la classe CSS pour le badge.
     */
    public function getBadgeClassAttribute(): string
    {
        $classes = [
            'CDI' => 'badge-success',
            'CDD' => 'badge-info',
            'STAGE' => 'badge-warning',
            'FREELANCE' => 'badge-purple',
            'ALTERNANCE' => 'badge-teal',
        ];

        return $classes[$this->code] ?? 'badge-secondary';
    }

    /**
     * Relations
     */

    /**
     * Relation avec les offres d'emploi.
     */
    public function offres(): HasMany
    {
        return $this->hasMany(Offre::class, 'id_type_cont', 'id_type_cont');
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
     * Méthodes utilitaires
     */

    /**
     * Vérifier si le type de contrat est actif (utilisé dans au moins une offre).
     */
    public function isUsed(): bool
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
     * Compter le nombre d'offres actives associées.
     */
    public function getOffresActivesCount(): int
    {
        return $this->offresActives()->count();
    }

    /**
     * Obtenir tous les types de contrat sous forme de tableau pour les selects.
     */
    public static function getForSelect(): array
    {
        return self::orderBy('libelle')
            ->get()
            ->mapWithKeys(fn($type) => [
                $type->id_type_cont => $type->libelle_complet
            ])
            ->toArray();
    }

    /**
     * Obtenir les types de contrat populaires (les plus utilisés).
     */
    public static function getPopulaires(int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return self::withCount('offres')
            ->having('offres_count', '>', 0)
            ->orderBy('offres_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Rechercher un type de contrat par son code.
     */
    public static function findByCode(string $code): ?self
    {
        return self::where('code', $code)->first();
    }

    /**
     * Scopes
     */

    /**
     * Scope pour les CDI.
     */
    public function scopeCDI($query)
    {
        return $query->where('code', 'CDI');
    }

    /**
     * Scope pour les CDD.
     */
    public function scopeCDD($query)
    {
        return $query->where('code', 'CDD');
    }

    /**
     * Scope pour les stages.
     */
    public function scopeStage($query)
    {
        return $query->where('code', 'STAGE');
    }

    /**
     * Scope pour les contrats précaires (CDD, stage, freelance).
     */
    public function scopePrecaire($query)
    {
        return $query->whereIn('code', ['CDD', 'STAGE', 'FREELANCE']);
    }

    /**
     * Scope pour les contrats durables (CDI, alternance).
     */
    public function scopeDurable($query)
    {
        return $query->whereIn('code', ['CDI', 'ALTERNANCE']);
    }

    /**
     * Scope pour la recherche textuelle.
     */
    public function scopeRecherche($query, string $terme)
    {
        return $query->where('libelle', 'LIKE', "%{$terme}%")
            ->orWhere('code', 'LIKE', "%{$terme}%");
    }
}