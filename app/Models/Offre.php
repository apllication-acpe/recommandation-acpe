<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offre extends Model
{
    use HasFactory;

    /**
     * La clé primaire de la table.
     *
     * @var string
     */
    protected $primaryKey = 'id_offre';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titre',
        'description',
        'mission',
        'profil_recherche',
        'date_publication',
        'date_expiration',
        'salaire_min',
        'salaire_max',
        'statut_salaire',
        'active',
        'debutant_accepte',
        'permis_b_requis',
        'vehicule_requis',
        'travail_nuit',
        'travail_weekend',
        'id_entreprise',
        'id_type_cont',
        'id_sect_act',
        'nb_vues',
        'acpe_id',
        'url_source',
        'source',
        'qualification_requise',
        'departement',
        'derniere_synchro',
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
        'active' => 'boolean',
        'date_publication' => 'date',
        'date_expiration' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'derniere_synchro' => 'datetime',
    ];

    /**
     * Accesseurs & Mutateurs
     */

    /**
     * Obtenir le salaire formaté.
     */
    public function getSalaireFormateAttribute(): string
    {
        if (!$this->salaire_min && !$this->salaire_max) {
            return 'Non spécifié';
        }

        if ($this->salaire_min && $this->salaire_max) {
            $salaire = number_format($this->salaire_min, 0, ',', ' ') . ' - ' . number_format($this->salaire_max, 0, ',', ' ');
        } elseif ($this->salaire_min) {
            $salaire = 'À partir de ' . number_format($this->salaire_min, 0, ',', ' ');
        } else {
            $salaire = 'Jusqu\'à ' . number_format($this->salaire_max, 0, ',', ' ');
        }

        if ($this->statut_salaire) {
            $salaire .= ' ' . strtoupper($this->statut_salaire);
        }

        return $salaire;
    }

    /**
     * Obtenir le salaire moyen (pour les statistiques).
     */
    public function getSalaireMoyenAttribute(): ?float
    {
        if ($this->salaire_min && $this->salaire_max) {
            return ($this->salaire_min + $this->salaire_max) / 2;
        }
        return $this->salaire_min ?? $this->salaire_max;
    }

    /**
     * Vérifier si l'offre est expirée.
     */
    public function getIsExpireeAttribute(): bool
    {
        return $this->date_expiration->isPast();
    }

    /**
     * Vérifier si l'offre est publiée.
     */
    public function getIsPublieeAttribute(): bool
    {
        return $this->date_publication <= now() && $this->active && !$this->is_expiree;
    }

    /**
     * Vérifier si l'offre est récente (moins de 7 jours).
     */
    public function getIsRecenteAttribute(): bool
    {
        return $this->date_publication->diffInDays(now()) <= 7;
    }

    /**
     * Obtenir les jours restants avant expiration.
     */
    public function getJoursRestantsAttribute(): int
    {
        if ($this->is_expiree) {
            return 0;
        }
        return now()->diffInDays($this->date_expiration);
    }

    /**
     * Obtenir le statut de l'offre.
     */
    public function getStatutAttribute(): string
    {
        if (!$this->active) {
            return 'inactive';
        }
        if ($this->is_expiree) {
            return 'expiree';
        }
        if (!$this->date_publication->isPast()) {
            return 'programmee';
        }
        return 'active';
    }

    /**
     * Obtenir la classe CSS pour le badge de statut.
     */
    public function getStatutBadgeClassAttribute(): string
    {
        $classes = [
            'active' => 'badge-success',
            'inactive' => 'badge-danger',
            'expiree' => 'badge-warning',
            'programmee' => 'badge-info',
        ];

        return $classes[$this->statut] ?? 'badge-secondary';
    }

    /**
     * Obtenir le libellé du statut en français.
     */
    public function getStatutLibelleAttribute(): string
    {
        $libelles = [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'expiree' => 'Expirée',
            'programmee' => 'Programmée',
        ];

        return $libelles[$this->statut] ?? 'Inconnu';
    }

    /**
     * Obtenir l'URL de l'offre.
     */
    public function getUrlAttribute(): string
    {
        return route('offres.show', $this->id_offre);
    }

    /**
     * Obtenir le slug pour les URLs SEO.
     */
    public function getSlugAttribute(): string
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->titre)));
        return $slug . '-' . $this->id_offre;
    }

    /**
     * Incrémenter le compteur de vues.
     */
    public function incrementerVues(): void
    {
        $this->increment('nb_vues');
    }

    /**
     * Relations
     */

    /**
     * Relation avec l'entreprise.
     */
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class, 'id_entreprise', 'id_entreprise');
    }

    /**
     * Relation avec le type de contrat.
     */
    public function typeContrat(): BelongsTo
    {
        return $this->belongsTo(TypeContrat::class, 'id_type_cont', 'id_type_cont');
    }

    /**
     * Relation avec le secteur d'activité.
     */
    public function secteurActivite(): BelongsTo
    {
        return $this->belongsTo(SecteurActivite::class, 'id_sect_act', 'id_sect_act');
    }

    /**
     * Relation avec les candidatures.
     */
    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class, 'id_offre', 'id_offre');
    }

    /**
     * Relation avec les diplômes requis.
     */
    public function diplomes(): BelongsToMany
    {
        return $this->belongsToMany(
            Diplome::class,
            'diplome_offre',
            'id_offre',
            'id_diplome'
        )->withPivot('obligatoire', 'poids')->withTimestamps();
    }

    /**
     * Relation avec les langues requises.
     */
    public function langues(): BelongsToMany
    {
        return $this->belongsToMany(
            Langue::class,
            'langue_offre',
            'id_offre',
            'id_langue'
        )->withPivot('niveau_exige', 'poids', 'obligatoire')->withTimestamps();
    }

    /**
     * Relation avec les compétences requises.
     */
    public function competences(): BelongsToMany
    {
        return $this->belongsToMany(
            Competence::class,
            'competence_offre',
            'id_offre',
            'id_competence'
        )->withPivot('poids', 'obligatoire', 'niveau_minimum')->withTimestamps();
    }

    /**
     * Relation avec les localisations.
     */
    public function localisations(): BelongsToMany
    {
        return $this->belongsToMany(
            Localisation::class,
            'offre_localisation',
            'id_offre',
            'id_localisation'
        )->withPivot('est_principale', 'teletravail_possible')->withTimestamps();
    }

    /**
     * Relation avec les recommandations.
     */
    public function recommandations(): HasMany
    {
        return $this->hasMany(Recommandation::class, 'id_offre', 'id_offre');
    }

    /**
     * Relation avec les appariements.
     */
    public function appariements(): HasMany
    {
        return $this->hasMany(Appariement::class, 'id_offre', 'id_offre');
    }

    /**
     * Relation avec les candidats (demandeurs) qui ont postulé.
     */
    public function candidats(): BelongsToMany
    {
        return $this->belongsToMany(
            Demandeur::class,
            'candidatures',
            'id_offre',
            'id_demandeur'
        )->withPivot('statut', 'message_motivation', 'date_candidature', 'date_reponse')
         ->withTimestamps();
    }

    /**
     * Relation avec les demandeurs qui ont mis cette offre en favori.
     */
    public function demandeursFavoris(): BelongsToMany
    {
        return $this->belongsToMany(
            Demandeur::class,
            'favoris',
            'id_offre',
            'id_demandeur'
        )->withTimestamps();
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Vérifier si l'offre est pourvue.
     */
    public function isPourvue(): bool
    {
        return $this->candidatures()->where('statut', 'acceptee')->exists();
    }

    /**
     * Compter le nombre de candidatures.
     */
    public function getCandidaturesCount(): int
    {
        return $this->candidatures()->count();
    }

    /**
     * Compter le nombre de candidatures en attente.
     */
    public function getCandidaturesEnAttenteCount(): int
    {
        return $this->candidatures()->where('statut', 'en_attente')->count();
    }

    /**
     * Compter le nombre de candidatures acceptées.
     */
    public function getCandidaturesAccepteesCount(): int
    {
        return $this->candidatures()->where('statut', 'acceptee')->count();
    }

    /**
     * Compter le nombre de candidatures refusées.
     */
    public function getCandidaturesRefuseesCount(): int
    {
        return $this->candidatures()->where('statut', 'refusee')->count();
    }

    /**
     * Obtenir le taux de conversion (candidatures acceptées / total).
     */
    public function getTauxConversionAttribute(): float
    {
        $total = $this->getCandidaturesCount();
        if ($total === 0) {
            return 0;
        }
        return round(($this->getCandidaturesAccepteesCount() / $total) * 100, 2);
    }

    /**
     * Obtenir les candidats qualifiés (score > 70%).
     */
    public function getCandidatsQualifiesAttribute(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->recommandations()
            ->with('demandeur.user')
            ->where('score_final', '>=', 70)
            ->orderBy('score_final', 'desc')
            ->get()
            ->pluck('demandeur');
    }

    /**
     * Publier l'offre.
     */
    public function publier(): bool
    {
        return $this->update([
            'active' => true,
            'date_publication' => now(),
        ]);
    }

    /**
     * Dépublier l'offre.
     */
    public function depublier(): bool
    {
        return $this->update(['active' => false]);
    }

    /**
     * Dupliquer l'offre.
     */
    public function dupliquer(): self
    {
        $nouvelleOffre = $this->replicate();
        $nouvelleOffre->titre = $this->titre . ' (Copie)';
        $nouvelleOffre->date_publication = now();
        $nouvelleOffre->date_expiration = now()->addDays(30);
        $nouvelleOffre->active = false;
        $nouvelleOffre->nb_vues = 0;
        $nouvelleOffre->save();

        // Copier les relations many-to-many
        foreach ($this->diplomes as $diplome) {
            $nouvelleOffre->diplomes()->attach($diplome->id_diplome, [
                'obligatoire' => $diplome->pivot->obligatoire,
                'poids' => $diplome->pivot->poids,
            ]);
        }

        foreach ($this->langues as $langue) {
            $nouvelleOffre->langues()->attach($langue->id_langue, [
                'niveau_exige' => $langue->pivot->niveau_exige,
                'poids' => $langue->pivot->poids,
                'obligatoire' => $langue->pivot->obligatoire,
            ]);
        }

        foreach ($this->competences as $competence) {
            $nouvelleOffre->competences()->attach($competence->id_competence, [
                'poids' => $competence->pivot->poids,
                'obligatoire' => $competence->pivot->obligatoire,
                'niveau_minimum' => $competence->pivot->niveau_minimum,
            ]);
        }

        foreach ($this->localisations as $localisation) {
            $nouvelleOffre->localisations()->attach($localisation->id_localisation, [
                'est_principale' => $localisation->pivot->est_principale,
                'teletravail_possible' => $localisation->pivot->teletravail_possible,
            ]);
        }

        return $nouvelleOffre;
    }

    /**
     * Scopes
     */

    /**
     * Scope pour les offres actives.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true)
            ->where('date_expiration', '>=', now())
            ->where('date_publication', '<=', now());
    }

    /**
     * Scope pour les offres expirées.
     */
    public function scopeExpiree($query)
    {
        return $query->where('date_expiration', '<', now());
    }

    /**
     * Scope pour les offres programmées.
     */
    public function scopeProgrammee($query)
    {
        return $query->where('date_publication', '>', now());
    }

    /**
     * Scope pour les offres récentes (moins de 7 jours).
     */
    public function scopeRecente($query)
    {
        return $query->where('date_publication', '>=', now()->subDays(7));
    }

    /**
     * Scope pour les offres par entreprise.
     */
    public function scopeParEntreprise($query, int $idEntreprise)
    {
        return $query->where('id_entreprise', $idEntreprise);
    }

    /**
     * Scope pour les offres par type de contrat.
     */
    public function scopeParTypeContrat($query, int $idTypeContrat)
    {
        return $query->where('id_type_cont', $idTypeContrat);
    }

    /**
     * Scope pour les offres par secteur.
     */
    public function scopeParSecteur($query, int $idSecteur)
    {
        return $query->where('id_sect_act', $idSecteur);
    }

    /**
     * Scope pour les offres avec salaire minimum.
     */
    public function scopeSalaireMin($query, float $salaire)
    {
        return $query->where('salaire_min', '>=', $salaire)
            ->orWhere('salaire_max', '>=', $salaire);
    }

    /**
     * Scope pour la recherche textuelle.
     */
    public function scopeRecherche($query, string $terme)
    {
        return $query->where(function ($q) use ($terme) {
            $q->where('titre', 'LIKE', "%{$terme}%")
                ->orWhere('description', 'LIKE', "%{$terme}%")
                ->orWhere('mission', 'LIKE', "%{$terme}%")
                ->orWhere('profil_recherche', 'LIKE', "%{$terme}%");
        })->orWhereHas('entreprise', function ($q) use ($terme) {
            $q->where('raison_sociale', 'LIKE', "%{$terme}%");
        });
    }

    /**
     * Scope pour les offres avec beaucoup de vues.
     */
    public function scopePopulaire($query, int $minVues = 100)
    {
        return $query->where('nb_vues', '>=', $minVues);
    }

    /**
     * Scope pour les offres avec le plus de candidatures.
     */
    public function scopeLesPlusPostulees($query, int $limit = 10)
    {
        return $query->withCount('candidatures')
            ->orderBy('candidatures_count', 'desc')
            ->limit($limit);
    }
}