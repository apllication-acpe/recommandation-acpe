<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Offre;
use App\Models\Demandeur;

class Qualification extends Model
{
    use HasFactory;

    /**
     * La clé primaire de la table.
     *
     * @var string
     */
    protected $primaryKey = 'id_qualification';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'intitule',
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
     * Obtenir l'intitulé en majuscules.
     */
    public function getIntituleMajusculeAttribute(): string
    {
        return strtoupper($this->intitule);
    }

    /**
     * Obtenir le type de qualification (diplôme, certification, formation).
     */
    public function getTypeAttribute(): string
    {
        $types = [
            'Bac' => 'Diplôme',
            'Licence' => 'Diplôme',
            'Master' => 'Diplôme',
            'Doctorat' => 'Diplôme',
            'Certification' => 'Certification',
            'Formation' => 'Formation',
            'Attestation' => 'Attestation',
        ];

        foreach ($types as $mot => $type) {
            if (str_contains($this->intitule, $mot)) {
                return $type;
            }
        }

        return 'Qualification';
    }

    /**
     * Obtenir la classe CSS pour le badge du type.
     */
    public function getTypeBadgeClassAttribute(): string
    {
        $classes = [
            'Diplôme' => 'badge-primary',
            'Certification' => 'badge-success',
            'Formation' => 'badge-info',
            'Attestation' => 'badge-warning',
        ];

        return $classes[$this->type] ?? 'badge-secondary';
    }

    /**
     * Obtenir le niveau approximatif (années d'études).
     */
    public function getNiveauAttribute(): ?int
    {
        $niveaux = [
            'Bac' => 0,
            'Bac+1' => 1,
            'Bac+2' => 2,
            'BTS' => 2,
            'DUT' => 2,
            'Licence' => 3,
            'Bac+3' => 3,
            'Master' => 5,
            'Bac+5' => 5,
            'Doctorat' => 7,
            'Bac+7' => 7,
        ];

        foreach ($niveaux as $mot => $niveau) {
            if (str_contains($this->intitule, $mot)) {
                return $niveau;
            }
        }

        return null;
    }

    /**
     * Obtenir le niveau formaté.
     */
    public function getNiveauFormateAttribute(): string
    {
        $niveau = $this->niveau;
        
        if ($niveau === null) {
            return 'Non spécifié';
        }
        
        if ($niveau === 0) {
            return 'Baccalauréat';
        }
        
        return "Bac + {$niveau}";
    }

    /**
     * Relations
     */

    /**
     * Relation avec les demandeurs (candidats) via la table pivot.
     */
    public function demandeurs(): BelongsToMany
    {
        return $this->belongsToMany(
            Demandeur::class,
            'qualification_demandeur',
            'id_qualification',
            'id_demandeur'
        )->withPivot('date_obtention', 'organisme', 'niveau_atteint', 'date_expiration', 'numero_reference')
         ->withTimestamps();
    }

    /**
     * Relation avec les offres d'emploi (diplômes requis).
     */
    public function offres(): BelongsToMany
    {
        return $this->belongsToMany(
            Offre::class,
            'diplome_offre',
            'id_diplome',
            'id_offre'
        )->withPivot('obligatoire', 'poids');
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Vérifier si la qualification est utilisée par au moins un demandeur.
     */
    public function isUsed(): bool
    {
        return $this->demandeurs()->exists();
    }

    /**
     * Compter le nombre de demandeurs possédant cette qualification.
     */
    public function getDemandeursCount(): int
    {
        return $this->demandeurs()->count();
    }

    /**
     * Compter le nombre d'offres requérant cette qualification.
     */
    public function getOffresCount(): int
    {
        return $this->offres()->count();
    }

    /**
     * Obtenir toutes les qualifications sous forme de tableau pour les selects.
     */
    public static function getForSelect(): array
    {
        return self::orderBy('intitule')
            ->get()
            ->mapWithKeys(fn($qualification) => [
                $qualification->id_qualification => $qualification->intitule
            ])
            ->toArray();
    }

    /**
     * Obtenir les qualifications par type.
     */
    public static function getByType(string $type): \Illuminate\Database\Eloquent\Collection
    {
        return self::all()->filter(fn($q) => $q->type === $type);
    }

    /**
     * Obtenir les qualifications les plus possédées par les demandeurs.
     */
    public static function getPlusPossedees(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return self::withCount('demandeurs')
            ->having('demandeurs_count', '>', 0)
            ->orderBy('demandeurs_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les qualifications les plus demandées par les offres.
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
     * Rechercher des qualifications par mot-clé.
     */
    public static function search(string $search): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('intitule', 'LIKE', "%{$search}%")->get();
    }

    /**
     * Obtenir les qualifications de niveau supérieur (Bac+3 et plus).
     */
    public static function getSuperieures(): \Illuminate\Database\Eloquent\Collection
    {
        return self::all()->filter(fn($q) => ($q->niveau ?? 0) >= 3);
    }

    /**
     * Vérifier si la qualification est un diplôme.
     */
    public function getIsDiplomeAttribute(): bool
    {
        return in_array($this->type, ['Diplôme']);
    }

    /**
     * Vérifier si la qualification est une certification.
     */
    public function getIsCertificationAttribute(): bool
    {
        return $this->type === 'Certification';
    }

    /**
     * Scopes
     */

    /**
     * Scope pour les diplômes.
     */
    public function scopeDiplome($query)
    {
        $motsDiplomes = ['Bac', 'Licence', 'Master', 'Doctorat', 'BTS', 'DUT', 'Ingénieur'];
        
        return $query->where(function ($q) use ($motsDiplomes) {
            foreach ($motsDiplomes as $mot) {
                $q->orWhere('intitule', 'LIKE', "%{$mot}%");
            }
        });
    }

    /**
     * Scope pour les certifications.
     */
    public function scopeCertification($query)
    {
        $motsCertifications = ['Certification', 'Certifié', 'Certificate', 'TOEIC', 'IELTS', 'PMP', 'CISCO'];
        
        return $query->where(function ($q) use ($motsCertifications) {
            foreach ($motsCertifications as $mot) {
                $q->orWhere('intitule', 'LIKE', "%{$mot}%");
            }
        });
    }

    /**
     * Scope pour les formations.
     */
    public function scopeFormation($query)
    {
        $motsFormations = ['Formation', 'Cours', 'Atelier', 'Séminaire'];
        
        return $query->where(function ($q) use ($motsFormations) {
            foreach ($motsFormations as $mot) {
                $q->orWhere('intitule', 'LIKE', "%{$mot}%");
            }
        });
    }

    /**
     * Scope pour la recherche textuelle.
     */
    public function scopeRecherche($query, string $terme)
    {
        return $query->where('intitule', 'LIKE', "%{$terme}%");
    }

    /**
     * Scope pour les qualifications actives (utilisées par des demandeurs).
     */
    public function scopeActive($query)
    {
        return $query->has('demandeurs');
    }
}