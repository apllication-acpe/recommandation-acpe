<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\User;
use App\Models\Appariement;
use App\Models\CritereDemandeur;
use App\Models\Nationalite;
use App\Models\Candidature;
use App\Models\Recommandation;
use App\Models\HistoriqueRecommandation;
use App\Models\Experience;
use App\Models\Offre;
use App\Models\Qualification;
use App\Models\Competence;
use App\Models\Langue;
use App\Models\Diplome;

class Demandeur extends Model
{
    use HasFactory;

    /**
     * La clé primaire de la table.
     *
     * @var string
     */
    protected $primaryKey = 'id_demandeur';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'date_naissance',
        'id_nationalite',
        'adresse',
        'cv_path',
        'photo_path',
        'permis_b',
        'vehicule_personnel',
        'disponibilite',
        'travail_nuit',
        'travail_weekend',
        'mobilite_rayon_km',
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
        'date_naissance' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Accesseurs & Mutateurs
     */

    /**
     * Obtenir l'âge du demandeur.
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->date_naissance) {
            return null;
        }
        return $this->date_naissance->age;
    }

    /**
     * Obtenir le chemin complet du CV.
     */
    public function getCvUrlAttribute(): ?string
    {
        if (!$this->cv_path) {
            return null;
        }
        return asset('storage/' . $this->cv_path);
    }

    /**
     * Obtenir le chemin complet de la photo.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo_path) {
            return null;
        }
        return asset('storage/' . $this->photo_path);
    }

    /**
     * Obtenir la photo par défaut si aucune n'est définie.
     */
    public function getPhotoOrDefaultAttribute(): string
    {
        return $this->photo_url ?? asset('images/default-avatar.png');
    }

    /**
     * Obtenir le nom complet du demandeur via l'utilisateur.
     */
    public function getNomCompletAttribute(): string
    {
        return $this->user ? $this->user->nom_complet : 'Non défini';
    }

    /**
     * Obtenir l'email du demandeur.
     */
    public function getEmailAttribute(): ?string
    {
        return $this->user?->email;
    }

    /**
     * Obtenir le téléphone du demandeur.
     */
    public function getTelephoneAttribute(): ?string
    {
        return $this->user?->telephone;
    }

    /**
     * Relations
     */

    /**
     * Relation avec l'utilisateur (User).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation avec la nationalité.
     */
    public function nationalite(): BelongsTo
    {
        return $this->belongsTo(Nationalite::class, 'id_nationalite', 'id_nationalite');
    }

    /**
     * Relation avec les alertes emploi.
     */
    public function alertes(): HasMany
    {
        return $this->hasMany(Alerte::class, 'id_demandeur', 'id_demandeur');
    }

    /**
     * Relation avec les candidatures.
     */
    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class, 'id_demandeur', 'id_demandeur');
    }

    /**
     * Relation avec les recommandations.
     */
    public function recommandations(): HasMany
    {
        return $this->hasMany(Recommandation::class, 'id_demandeur', 'id_demandeur');
    }

    /**
     * Relation avec l'historique des recommandations.
     */
    public function historiqueRecommandations(): HasMany
    {
        return $this->hasMany(HistoriqueRecommandation::class, 'id_demandeur', 'id_demandeur');
    }

    /**
     * Relation avec les appariements (matches).
     */
    public function appariements(): HasMany
    {
        return $this->hasMany(Appariement::class, 'id_demandeur', 'id_demandeur');
    }

    /**
     * Relation avec les qualifications (diplômes, certifications).
     */
    public function qualifications(): BelongsToMany
    {
        return $this->belongsToMany(
            Qualification::class,
            'qualification_demandeur',
            'id_demandeur',
            'id_qualification'
        )->withPivot('date_obtention', 'organisme', 'niveau_atteint', 'date_expiration', 'numero_reference')
         ->withTimestamps();
    }

    /**
     * Relation avec les diplômes (via qualifications).
     */
    public function diplomes(): BelongsToMany
    {
        return $this->belongsToMany(
            Diplome::class,
            'qualification_demandeur',
            'id_demandeur',
            'id_qualification'
        )->withPivot('date_obtention', 'organisme', 'niveau_atteint', 'date_expiration', 'numero_reference')
         ->withTimestamps();
    }

    /**
     * Relation avec les compétences.
     */
    public function competences(): BelongsToMany
    {
        return $this->belongsToMany(
            Competence::class,
            'competence_demandeur',
            'id_demandeur',
            'id_competence'
        )->withPivot('niveau', 'certification')->withTimestamps();
    }

    /**
     * Relation avec les langues.
     */
    public function langues(): BelongsToMany
    {
        return $this->belongsToMany(
            Langue::class,
            'langue_demandeur',
            'id_demandeur',
            'id_langue'
        )->withPivot('niveau')->withTimestamps();
    }

    /**
     * Relation avec les expériences professionnelles.
     */
    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class, 'id_demandeur', 'id_demandeur');
    }

    /**
     * Relation avec les critères (via table pivot ou relation directe).
     */
    public function criteres(): HasMany
    {
        return $this->hasMany(CritereDemandeur::class, 'id_demandeur', 'id_demandeur');
    }

    /**
     * Relation avec les offres via les appariements.
     */
    public function offresAppariees(): HasManyThrough
    {
        return $this->hasManyThrough(
            Offre::class,
            Appariement::class,
            'id_demandeur',
            'id_offre',
            'id_demandeur',
            'id_offre'
        );
    }

    /**
     * Relation avec les secteurs d'activité préférés.
     */
    public function secteursActivitePreferes(): BelongsToMany
    {
        return $this->belongsToMany(
            SecteurActivite::class,
            'demandeur_secteur_activite',
            'id_demandeur',
            'id_sect_act'
        )->withTimestamps();
    }

    /**
     * Relation avec les types de contrat préférés (souhaités par le demandeur).
     */
    public function typesContratPreferes(): BelongsToMany
    {
        return $this->belongsToMany(
            TypeContrat::class,
            'demandeur_type_contrat',
            'id_demandeur',
            'id_type_cont'
        )->withTimestamps();
    }

    /**
     * Relation avec les offres mises en favoris.
     */
    public function favoris(): BelongsToMany
    {
        return $this->belongsToMany(
            Offre::class,
            'favoris',
            'id_demandeur',
            'id_offre'
        )->withTimestamps();
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Vérifier si le profil est complet.
     */
    public function isProfileComplete(): bool
    {
        return !empty($this->date_naissance)
            && !empty($this->adresse)
            && $this->user?->telephone
            && $this->nationalite
            && $this->experiences()->count() > 0
            && $this->qualifications()->count() > 0;
    }

    /**
     * Obtenir le pourcentage de complétion du profil.
     */
    public function getProfileCompletionPercentage(): int
    {
        $fields = [
            'date_naissance' => 15,
            'adresse' => 10,
            'id_nationalite' => 10,
            'user.telephone' => 10,
            'user.email_verified_at' => 15,
        ];

        $completion = 0;
        foreach ($fields as $field => $weight) {
            if (str_contains($field, '.')) {
                [$relation, $column] = explode('.', $field);
                if ($this->$relation && $this->$relation->$column) {
                    $completion += $weight;
                }
            } else {
                if ($this->$field) {
                    $completion += $weight;
                }
            }
        }

        // Ajout des expériences (max 20%)
        $expCount = $this->experiences()->count();
        $completion += min(20, $expCount * 5);

        // Ajout des qualifications (max 20%)
        $qualCount = $this->qualifications()->count();
        $completion += min(20, $qualCount * 4);

        return min(100, $completion);
    }

    /**
     * Obtenir les dernières candidatures.
     */
    public function getLastCandidatures(int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return $this->candidatures()
            ->with('offre')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les recommandations actives.
     */
    public function getActiveRecommandations(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return $this->recommandations()
            ->with('offre')
            ->where('statut', 'en_attente')
            ->orderBy('score_final', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Calculer l'expérience totale en années.
     */
    public function getTotalYearsExperience(): int
    {
        $totalDays = 0;
        
        foreach ($this->experiences as $experience) {
            $start = $experience->date_debut;
            $end = $experience->date_fin ?? now();
            $totalDays += $start->diffInDays($end);
        }
        
        return floor($totalDays / 365);
    }

    /**
     * Obtenir les compétences du demandeur.
     */
    public function getCompetencesDetecteesAttribute(): array
    {
        // Cette méthode peut être améliorée selon ta logique métier
        $competences = [];
        
        // Récupérer les compétences depuis les expériences (mots-clés)
        foreach ($this->experiences as $exp) {
            if ($exp->description) {
                // Extraction simple de mots-clés
                $keywords = ['PHP', 'Laravel', 'JavaScript', 'Python', 'Java', 'React', 'Vue', 'Docker', 'MySQL', 'Node.js'];
                foreach ($keywords as $keyword) {
                    if (stripos($exp->description, $keyword) !== false) {
                        $competences[] = $keyword;
                    }
                }
            }
        }
        
        return array_unique($competences);
    }

    /**
     * Vérifier si le demandeur a postulé à une offre.
     */
    public function hasPostulated(Offre $offre): bool
    {
        return $this->candidatures()
            ->where('id_offre', $offre->id_offre)
            ->exists();
    }

    /**
     * Scope pour les demandeurs actifs.
     */
    public function scopeActif($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->where('actif', true);
        });
    }

    /**
     * Scope pour les demandeurs avec profil complet.
     */
    public function scopeProfilComplet($query)
    {
        return $query->whereNotNull('date_naissance')
            ->whereNotNull('adresse')
            ->whereNotNull('id_nationalite')
            ->whereHas('user', function ($q) {
                $q->whereNotNull('telephone');
            });
    }

    /**
     * Scope pour les demandeurs par âge.
     */
    public function scopeAgeEntre($query, int $min, int $max)
    {
        $dateMin = now()->subYears($max)->format('Y-m-d');
        $dateMax = now()->subYears($min)->format('Y-m-d');
        
        return $query->whereBetween('date_naissance', [$dateMin, $dateMax]);
    }
}