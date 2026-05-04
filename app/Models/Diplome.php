<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Demandeur;
use App\Models\Offre;

class Diplome extends Model
{
    use HasFactory;

    /**
     * La clé primaire de la table.
     *
     * @var string
     */
    protected $primaryKey = 'id_diplome';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'libelle',
        'niveau',
        'specialite',
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
     * Obtenir le libellé complet (niveau + spécialité).
     */
    public function getLibelleCompletAttribute(): string
    {
        return "{$this->niveau} - {$this->libelle} ({$this->specialite})";
    }

    /**
     * Obtenir le libellé court.
     */
    public function getLibelleCourtAttribute(): string
    {
        return "{$this->niveau} en {$this->specialite}";
    }

    /**
     * Obtenir le niveau formaté avec libellé long.
     */
    public function getNiveauFormateAttribute(): string
    {
        $niveaux = [
            'Bac' => 'Baccalauréat',
            'Bac+1' => 'Baccalauréat + 1 an',
            'Bac+2' => 'BTS / DUT / DEUG',
            'Bac+3' => 'Licence',
            'Bac+4' => 'Master 1 / Maîtrise',
            'Bac+5' => 'Master 2 / Diplôme d\'ingénieur',
            'Bac+6' => 'Master spécialisé',
            'Bac+7' => 'Doctorat / Thèse',
            'Bac+8' => 'Post-doctorat',
        ];

        return $niveaux[$this->niveau] ?? $this->niveau;
    }

    /**
     * Obtenir le niveau hiérarchique (années d'études).
     */
    public function getNiveauAnneesAttribute(): int
    {
        $annees = [
            'Bac' => 0,
            'Bac+1' => 1,
            'Bac+2' => 2,
            'Bac+3' => 3,
            'Bac+4' => 4,
            'Bac+5' => 5,
            'Bac+6' => 6,
            'Bac+7' => 7,
            'Bac+8' => 8,
        ];

        return $annees[$this->niveau] ?? 0;
    }

    /**
     * Obtenir la couleur associée au niveau.
     */
    public function getCouleurAttribute(): string
    {
        $couleurs = [
            'Bac' => '#10B981',
            'Bac+1' => '#34D399',
            'Bac+2' => '#6EE7B7',
            'Bac+3' => '#3B82F6',
            'Bac+4' => '#60A5FA',
            'Bac+5' => '#8B5CF6',
            'Bac+6' => '#A78BFA',
            'Bac+7' => '#F59E0B',
            'Bac+8' => '#F97316',
        ];

        return $couleurs[$this->niveau] ?? '#6B7280';
    }

    /**
     * Obtenir la classe CSS pour le badge de niveau.
     */
    public function getNiveauBadgeClassAttribute(): string
    {
        $classes = [
            'Bac' => 'badge-green',
            'Bac+1' => 'badge-green',
            'Bac+2' => 'badge-blue',
            'Bac+3' => 'badge-blue',
            'Bac+4' => 'badge-purple',
            'Bac+5' => 'badge-purple',
            'Bac+6' => 'badge-purple',
            'Bac+7' => 'badge-orange',
            'Bac+8' => 'badge-orange',
        ];

        return $classes[$this->niveau] ?? 'badge-gray';
    }

    /**
     * Vérifier si le diplôme est de niveau Bac+5 ou plus.
     */
    public function getIsSuperieurAttribute(): bool
    {
        return $this->niveau_annees >= 5;
    }

    /**
     * Vérifier si le diplôme est de niveau Bac.
     */
    public function getIsBacAttribute(): bool
    {
        return $this->niveau === 'Bac';
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
            'diplome_offre',
            'id_diplome',
            'id_offre'
        )->withPivot('obligatoire', 'poids')->withTimestamps();
    }

    /**
     * Relation avec les offres où ce diplôme est obligatoire.
     */
    public function offresObligatoires(): BelongsToMany
    {
        return $this->offres()->wherePivot('obligatoire', true);
    }

    /**
     * Relation avec les demandeurs (candidats) via leurs qualifications.
     */
    public function demandeurs(): BelongsToMany
    {
        return $this->belongsToMany(
            Demandeur::class,
            'qualification_demandeur',
            'id_qualification',
            'id_demandeur'
        )->withTimestamps();
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Vérifier si le diplôme est utilisé dans au moins une offre.
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
     * Compter le nombre d'offres où ce diplôme est obligatoire.
     */
    public function getOffresObligatoiresCount(): int
    {
        return $this->offresObligatoires()->count();
    }

    /**
     * Obtenir tous les diplômes sous forme de tableau pour les selects.
     */
    public static function getForSelect(): array
    {
        return self::orderBy('niveau_annees')
            ->orderBy('libelle')
            ->get()
            ->mapWithKeys(fn($diplome) => [
                $diplome->id_diplome => $diplome->libelle_complet
            ])
            ->toArray();
    }

    /**
     * Obtenir les diplômes par niveau hiérarchique.
     */
    public static function getByNiveau(string $niveau): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('niveau', $niveau)->get();
    }

    /**
     * Obtenir les diplômes du supérieur (Bac+3 et plus).
     */
    public static function getSuperieurs(): \Illuminate\Database\Eloquent\Collection
    {
        return self::whereIn('niveau', ['Bac+3', 'Bac+4', 'Bac+5', 'Bac+6', 'Bac+7', 'Bac+8'])->get();
    }

    /**
     * Obtenir les diplômes les plus demandés par les offres.
     */
    public static function getPlusDemandes(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return self::withCount('offres')
            ->having('offres_count', '>', 0)
            ->orderBy('offres_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Comparer deux diplômes.
     */
    public function compare(Diplome $other): array
    {
        return [
            'meme_niveau' => $this->niveau === $other->niveau,
            'meme_specialite' => $this->specialite === $other->specialite,
            'niveau_superieur' => $this->niveau_annees > $other->niveau_annees,
            'niveau_inferieur' => $this->niveau_annees < $other->niveau_annees,
            'difference_annees' => abs($this->niveau_annees - $other->niveau_annees),
        ];
    }

    /**
     * Scopes
     */

    /**
     * Scope pour les diplômes de niveau Bac+5 ou plus.
     */
    public function scopeSuperieur($query)
    {
        return $query->whereIn('niveau', ['Bac+5', 'Bac+6', 'Bac+7', 'Bac+8']);
    }

    /**
     * Scope pour les diplômes de niveau Bac+2 à Bac+4.
     */
    public function scopeMoyen($query)
    {
        return $query->whereIn('niveau', ['Bac+2', 'Bac+3', 'Bac+4']);
    }

    /**
     * Scope pour les diplômes de niveau Bac.
     */
    public function scopeBac($query)
    {
        return $query->where('niveau', 'Bac');
    }

    /**
     * Scope pour les diplômes par spécialité.
     */
    public function scopeParSpecialite($query, string $specialite)
    {
        return $query->where('specialite', 'LIKE', "%{$specialite}%");
    }

    /**
     * Scope pour la recherche textuelle.
     */
    public function scopeRecherche($query, string $terme)
    {
        return $query->where('libelle', 'LIKE', "%{$terme}%")
            ->orWhere('niveau', 'LIKE', "%{$terme}%")
            ->orWhere('specialite', 'LIKE', "%{$terme}%");
    }

    /**
     * Scope pour les diplômes actifs (utilisés dans les offres).
     */
    public function scopeActif($query)
    {
        return $query->has('offres');
    }
}