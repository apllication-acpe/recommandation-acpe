<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Demandeur;
use App\Models\Offre;

class Competence extends Model
{
    use HasFactory;

    /**
     * La clé primaire de la table.
     *
     * @var string
     */
    protected $primaryKey = 'id_competence';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'libelle',
        'categorie',
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
     * Obtenir le libellé avec sa catégorie.
     */
    public function getLibelleCompletAttribute(): string
    {
        if ($this->categorie) {
            return "{$this->libelle} ({$this->categorie})";
        }
        return $this->libelle;
    }

    /**
     * Obtenir la catégorie formatée.
     */
    public function getCategorieFormateeAttribute(): string
    {
        $categories = [
            'Technique' => 'Compétence technique',
            'Soft skill' => 'Savoir-être / Soft skill',
            'Langue' => 'Compétence linguistique',
            'Management' => 'Management / Leadership',
            'Digital' => 'Compétence digitale',
            'Metier' => 'Compétence métier',
        ];

        return $categories[$this->categorie] ?? $this->categorie ?? 'Non catégorisé';
    }

    /**
     * Obtenir la classe CSS pour le badge de catégorie.
     */
    public function getCategorieBadgeClassAttribute(): string
    {
        $classes = [
            'Technique' => 'badge-primary',
            'Soft skill' => 'badge-success',
            'Langue' => 'badge-info',
            'Management' => 'badge-warning',
            'Digital' => 'badge-purple',
            'Metier' => 'badge-secondary',
        ];

        return $classes[$this->categorie] ?? 'badge-gray';
    }

    /**
     * Obtenir la couleur associée à la catégorie.
     */
    public function getCouleurAttribute(): string
    {
        $couleurs = [
            'Technique' => '#3B82F6',
            'Soft skill' => '#10B981',
            'Langue' => '#06B6D4',
            'Management' => '#F59E0B',
            'Digital' => '#8B5CF6',
            'Metier' => '#6B7280',
        ];

        return $couleurs[$this->categorie] ?? '#9CA3AF';
    }

    /**
     * Vérifier si c'est une compétence technique.
     */
    public function getIsTechniqueAttribute(): bool
    {
        return $this->categorie === 'Technique';
    }

    /**
     * Vérifier si c'est une soft skill.
     */
    public function getIsSoftSkillAttribute(): bool
    {
        return $this->categorie === 'Soft skill';
    }

    /**
     * Relations
     */

    /**
     * Relation avec les offres d'emploi.
     */
    public function offres(): BelongsToMany
    {
        return $this->belongsToMany(
            Offre::class,
            'competence_offre',
            'id_competence',
            'id_offre'
        )->withPivot('poids', 'obligatoire', 'niveau_minimum')->withTimestamps();
    }

    /**
     * Relation avec les offres où cette compétence est obligatoire.
     */
    public function offresObligatoires(): BelongsToMany
    {
        return $this->offres()->wherePivot('obligatoire', true);
    }

    /**
     * Relation avec les demandeurs (candidats).
     */
    public function demandeurs(): BelongsToMany
    {
        return $this->belongsToMany(
            Demandeur::class,
            'competence_demandeur',
            'id_competence',
            'id_demandeur'
        )->withPivot('niveau', 'certification')->withTimestamps();
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Vérifier si la compétence est utilisée dans au moins une offre.
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
     * Compter le nombre d'offres où cette compétence est obligatoire.
     */
    public function getOffresObligatoiresCount(): int
    {
        return $this->offresObligatoires()->count();
    }

    /**
     * Obtenir toutes les compétences sous forme de tableau pour les selects.
     */
    public static function getForSelect(?string $categorie = null): array
    {
        $query = self::orderBy('libelle');
        
        if ($categorie) {
            $query->where('categorie', $categorie);
        }
        
        return $query->get()
            ->mapWithKeys(fn($competence) => [
                $competence->id_competence => $competence->libelle_complet
            ])
            ->toArray();
    }

    /**
     * Obtenir les compétences par catégorie.
     */
    public static function getByCategorie(string $categorie): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('categorie', $categorie)->orderBy('libelle')->get();
    }

    /**
     * Obtenir les compétences les plus demandées.
     */
    public static function getPlusDemandees(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return self::withCount('offres')
            ->having('offres_count', '>', 0)
            ->orderBy('offres_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les compétences par popularité dans les offres actives.
     */
    public static function getTendances(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return self::withCount(['offres' => function ($query) {
            $query->where('active', true)
                ->where('date_expiration', '>=', now())
                ->where('date_publication', '<=', now());
        }])
            ->having('offres_count', '>', 0)
            ->orderBy('offres_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Rechercher des compétences par mots-clés.
     */
    public static function searchByKeywords(string $search): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('libelle', 'LIKE', "%{$search}%")
            ->orWhere('categorie', 'LIKE', "%{$search}%")
            ->get();
    }

    /**
     * Obtenir les soft skills populaires.
     */
    public static function getSoftSkillsPopulaires(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('categorie', 'Soft skill')
            ->withCount('offres')
            ->having('offres_count', '>', 0)
            ->orderBy('offres_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les compétences techniques populaires.
     */
    public static function getTechniquesPopulaires(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('categorie', 'Technique')
            ->withCount('offres')
            ->having('offres_count', '>', 0)
            ->orderBy('offres_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Scopes
     */

    /**
     * Scope pour les compétences techniques.
     */
    public function scopeTechnique($query)
    {
        return $query->where('categorie', 'Technique');
    }

    /**
     * Scope pour les soft skills.
     */
    public function scopeSoftSkill($query)
    {
        return $query->where('categorie', 'Soft skill');
    }

    /**
     * Scope pour les compétences digitales.
     */
    public function scopeDigital($query)
    {
        return $query->where('categorie', 'Digital');
    }

    /**
     * Scope pour les compétences de management.
     */
    public function scopeManagement($query)
    {
        return $query->where('categorie', 'Management');
    }

    /**
     * Scope pour les compétences actives (utilisées dans les offres).
     */
    public function scopeActive($query)
    {
        return $query->has('offres');
    }

    /**
     * Scope pour la recherche textuelle.
     */
    public function scopeRecherche($query, string $terme)
    {
        return $query->where('libelle', 'LIKE', "%{$terme}%")
            ->orWhere('categorie', 'LIKE', "%{$terme}%");
    }

    /**
     * Scope pour les compétences par catégorie.
     */
    public function scopeParCategorie($query, string $categorie)
    {
        return $query->where('categorie', $categorie);
    }
}