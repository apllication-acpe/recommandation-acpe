<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\CritereDemandeur;
use App\Models\Demandeur;

class Critere extends Model
{
    use HasFactory;

    /**
     * La clé primaire de la table.
     *
     * @var string
     */
    protected $primaryKey = 'id_critere';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'description',
        'poids',
        'priorite',
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
        'poids' => 'integer',
        'priorite' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Accesseurs & Mutateurs
     */

    /**
     * Obtenir le nom en majuscules.
     */
    public function getNomMajusculeAttribute(): string
    {
        return strtoupper($this->nom);
    }

    /**
     * Obtenir le niveau de priorité formaté.
     */
    public function getPrioriteLibelleAttribute(): string
    {
        $priorites = [
            1 => 'Très basse',
            2 => 'Basse',
            3 => 'Moyenne',
            4 => 'Haute',
            5 => 'Très haute',
        ];

        return $priorites[$this->priorite] ?? 'Non définie';
    }

    /**
     * Obtenir la classe CSS pour le badge de priorité.
     */
    public function getPrioriteBadgeClassAttribute(): string
    {
        $classes = [
            1 => 'badge-gray',
            2 => 'badge-info',
            3 => 'badge-blue',
            4 => 'badge-warning',
            5 => 'badge-danger',
        ];

        return $classes[$this->priorite] ?? 'badge-secondary';
    }

    /**
     * Obtenir la couleur selon la priorité.
     */
    public function getPrioriteCouleurAttribute(): string
    {
        $couleurs = [
            1 => '#9CA3AF',
            2 => '#3B82F6',
            3 => '#10B981',
            4 => '#F59E0B',
            5 => '#EF4444',
        ];

        return $couleurs[$this->priorite] ?? '#6B7280';
    }

    /**
     * Obtenir le niveau d'importance basé sur le poids.
     */
    public function getImportanceAttribute(): string
    {
        if ($this->poids >= 80) {
            return 'Critique';
        } elseif ($this->poids >= 60) {
            return 'Très important';
        } elseif ($this->poids >= 40) {
            return 'Important';
        } elseif ($this->poids >= 20) {
            return 'Modéré';
        }
        return 'Faible';
    }

    /**
     * Obtenir la classe CSS pour le badge d'importance.
     */
    public function getImportanceBadgeClassAttribute(): string
    {
        $classes = [
            'Critique' => 'badge-danger',
            'Très important' => 'badge-warning',
            'Important' => 'badge-purple',
            'Modéré' => 'badge-info',
            'Faible' => 'badge-gray',
        ];

        return $classes[$this->importance] ?? 'badge-secondary';
    }

    /**
     * Obtenir le score normalisé (0-1).
     */
    public function getNormalizedScoreAttribute(): float
    {
        return $this->poids ? $this->poids / 100 : 0;
    }

    /**
     * Vérifier si le critère est actif (poids > 0).
     */
    public function getEstActifAttribute(): bool
    {
        return ($this->poids ?? 0) > 0;
    }

    /**
     * Vérifier si le critère est prioritaire.
     */
    public function getEstPrioritaireAttribute(): bool
    {
        return ($this->priorite ?? 0) >= 4;
    }

    /**
     * Relations
     */

    /**
     * Relation avec les scores des demandeurs.
     */
    public function scoresDemandeurs(): HasMany
    {
        return $this->hasMany(CritereDemandeur::class, 'id_critere', 'id_critere');
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Vérifier si le critère est utilisé.
     */
    public function isUsed(): bool
    {
        return $this->scoresDemandeurs()->exists();
    }

    /**
     * Compter le nombre de demandeurs évalués sur ce critère.
     */
    public function getDemandeursCount(): int
    {
        return $this->scoresDemandeurs()->count();
    }

    /**
     * Obtenir le score moyen pour ce critère.
     */
    public function getScoreMoyenAttribute(): float
    {
        return $this->scoresDemandeurs()->avg('score') ?? 0;
    }

    /**
     * Obtenir la distribution des scores.
     */
    public function getDistributionScoresAttribute(): array
    {
        $scores = $this->scoresDemandeurs()
            ->selectRaw('FLOOR(score/10)*10 as tranche, COUNT(*) as count')
            ->groupBy('tranche')
            ->pluck('count', 'tranche')
            ->toArray();

        $distribution = [];
        for ($i = 0; $i <= 100; $i += 10) {
            $distribution[$i] = $scores[$i] ?? 0;
        }

        return $distribution;
    }

    /**
     * Obtenir tous les critères actifs.
     */
    public static function getActifs(): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('poids', '>', 0)
            ->orderBy('priorite', 'desc')
            ->orderBy('poids', 'desc')
            ->get();
    }

    /**
     * Obtenir les critères par ordre de priorité.
     */
    public static function getParPriorite(): \Illuminate\Database\Eloquent\Collection
    {
        return self::orderBy('priorite', 'desc')
            ->orderBy('poids', 'desc')
            ->get();
    }

    /**
     * Obtenir les critères pour un formulaire select.
     */
    public static function getForSelect(): array
    {
        return self::orderBy('priorite', 'desc')
            ->orderBy('nom')
            ->get()
            ->mapWithKeys(fn($critere) => [
                $critere->id_critere => sprintf(
                    '%s (Priorité: %s, Poids: %d%%)',
                    $critere->nom,
                    $critere->priorite_libelle,
                    $critere->poids ?? 0
                )
            ])
            ->toArray();
    }

    /**
     * Calculer le score pondéré pour un demandeur.
     */
    public function calculateScoreForDemandeur(Demandeur $demandeur): float
    {
        $scoreDemandeur = $this->scoresDemandeurs()
            ->where('id_demandeur', $demandeur->id_demandeur)
            ->first();

        if (!$scoreDemandeur) {
            return 0;
        }

        // Score pondéré par le poids du critère
        return ($scoreDemandeur->score * ($this->poids ?? 0)) / 100;
    }

    /**
     * Obtenir les critères prédéfinis par défaut.
     */
    public static function getDefaultCriteres(): array
    {
        return [
            ['nom' => 'Compétences techniques', 'description' => 'Maîtrise des compétences requises pour le poste', 'poids' => 25, 'priorite' => 5],
            ['nom' => 'Expérience professionnelle', 'description' => 'Années d\'expérience et pertinence', 'poids' => 20, 'priorite' => 5],
            ['nom' => 'Diplômes et formations', 'description' => 'Niveau d\'étude et formations complémentaires', 'poids' => 15, 'priorite' => 4],
            ['nom' => 'Langues', 'description' => 'Maîtrise des langues requises', 'poids' => 10, 'priorite' => 3],
            ['nom' => 'Soft skills', 'description' => 'Savoir-être, adaptabilité, travail en équipe', 'poids' => 15, 'priorite' => 4],
            ['nom' => 'Localisation', 'description' => 'Proximité géographique avec le lieu de travail', 'poids' => 5, 'priorite' => 2],
            ['nom' => 'Salaire', 'description' => 'Adéquation avec la fourchette salariale proposée', 'poids' => 10, 'priorite' => 4],
        ];
    }

    /**
     * Scopes
     */

    /**
     * Scope pour les critères actifs.
     */
    public function scopeActif($query)
    {
        return $query->where('poids', '>', 0);
    }

    /**
     * Scope pour les critères inactifs.
     */
    public function scopeInactif($query)
    {
        return $query->where('poids', 0);
    }

    /**
     * Scope pour les critères prioritaires (priorité >= 4).
     */
    public function scopePrioritaire($query)
    {
        return $query->where('priorite', '>=', 4);
    }

    /**
     * Scope pour les critères par ordre de priorité.
     */
    public function scopeTrieParPriorite($query)
    {
        return $query->orderBy('priorite', 'desc')->orderBy('poids', 'desc');
    }

    /**
     * Scope pour les critères avec poids élevé.
     */
    public function scopePoidsEleve($query, int $seuil = 70)
    {
        return $query->where('poids', '>=', $seuil);
    }

    /**
     * Scope pour la recherche textuelle.
     */
    public function scopeRecherche($query, string $terme)
    {
        return $query->where('nom', 'LIKE', "%{$terme}%")
            ->orWhere('description', 'LIKE', "%{$terme}%");
    }
}