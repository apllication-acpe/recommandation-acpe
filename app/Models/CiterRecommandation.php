<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class CiterRecommandation extends Model
{
    use HasFactory;

    /**
     * La table associée au modèle.
     *
     * @var string
     */
    protected $table = 'citer_recommandations';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'citer_recherche',
        'id_recommandation',
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
     * Obtenir le texte de la citation tronqué (100 caractères).
     */
    public function getTexteCourtAttribute(): string
    {
        if (!$this->citer_recherche) {
            return 'Aucune citation';
        }
        
        if (strlen($this->citer_recherche) <= 100) {
            return $this->citer_recherche;
        }
        
        return substr($this->citer_recherche, 0, 97) . '...';
    }

    /**
     * Obtenir la citation formatée (avec guillemets).
     */
    public function getCitationFormateeAttribute(): ?string
    {
        if (!$this->citer_recherche) {
            return null;
        }
        
        return '"' . $this->citer_recherche . '"';
    }

    /**
     * Obtenir le nombre de mots de la citation.
     */
    public function getNbMotsAttribute(): int
    {
        if (!$this->citer_recherche) {
            return 0;
        }
        
        return str_word_count($this->citer_recherche);
    }

    /**
     * Vérifier si la citation est longue (plus de 200 caractères).
     */
    public function getEstLongueAttribute(): bool
    {
        return strlen($this->citer_recherche ?? '') > 200;
    }

    /**
     * Vérifier si une citation existe.
     */
    public function getExisteAttribute(): bool
    {
        return !empty($this->citer_recherche);
    }

    /**
     * Relations
     */

    /**
     * Relation avec la recommandation.
     */
    public function recommandation(): BelongsTo
    {
        return $this->belongsTo(Recommandation::class, 'id_recommandation', 'id_recommandation');
    }

    /**
     * Accéder à l'offre via la recommandation.
     */
    public function offre(): HasOneThrough
    {
        return $this->hasOneThrough(
            Offre::class,
            Recommandation::class,
            'id_recommandation',
            'id_offre',
            'id_recommandation',
            'id_offre'
        );
    }

    /**
     * Accéder au demandeur via la recommandation.
     */
    public function demandeur(): HasOneThrough
    {
        return $this->hasOneThrough(
            Demandeur::class,
            Recommandation::class,
            'id_recommandation',
            'id_demandeur',
            'id_recommandation',
            'id_demandeur'
        );
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Récupérer toutes les citations pour une recommandation.
     */
    public static function getForRecommandation(int $idRecommandation): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('id_recommandation', $idRecommandation)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Récupérer la dernière citation pour une recommandation.
     */
    public static function getLastForRecommandation(int $idRecommandation): ?self
    {
        return self::where('id_recommandation', $idRecommandation)
            ->latest()
            ->first();
    }

    /**
     * Récupérer les citations récentes.
     */
    public static function getRecentes(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return self::with(['recommandation.offre', 'recommandation.demandeur.user'])
            ->whereNotNull('citer_recherche')
            ->where('citer_recherche', '!=', '')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Récupérer les citations par mot-clé.
     */
    public static function searchByKeyword(string $keyword): \Illuminate\Database\Eloquent\Collection
    {
        return self::with(['recommandation.offre', 'recommandation.demandeur.user'])
            ->where('citer_recherche', 'LIKE', "%{$keyword}%")
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Créer une citation pour une recommandation.
     */
    public static function createForRecommandation(int $idRecommandation, string $citation): self
    {
        return self::create([
            'id_recommandation' => $idRecommandation,
            'citer_recherche' => $citation,
        ]);
    }

    /**
     * Mettre à jour la citation.
     */
    public function updateCitation(string $nouvelleCitation): bool
    {
        return $this->update(['citer_recherche' => $nouvelleCitation]);
    }

    /**
     * Obtenir les statistiques des citations.
     */
    public static function getStats(): array
    {
        $total = self::count();
        $avecCitation = self::whereNotNull('citer_recherche')
            ->where('citer_recherche', '!=', '')
            ->count();
        $sansCitation = $total - $avecCitation;
        
        $longueurMoyenne = self::whereNotNull('citer_recherche')
            ->selectRaw('AVG(LENGTH(citer_recherche)) as moyenne')
            ->value('moyenne') ?? 0;
        
        return [
            'total' => $total,
            'avec_citation' => $avecCitation,
            'sans_citation' => $sansCitation,
            'taux_remplissage' => $total > 0 ? round(($avecCitation / $total) * 100, 2) : 0,
            'longueur_moyenne' => round($longueurMoyenne, 0),
        ];
    }

    /**
     * Scopes
     */

    /**
     * Scope pour les citations existantes.
     */
    public function scopeAvecCitation($query)
    {
        return $query->whereNotNull('citer_recherche')
            ->where('citer_recherche', '!=', '');
    }

    /**
     * Scope pour les citations vides.
     */
    public function scopeSansCitation($query)
    {
        return $query->whereNull('citer_recherche')
            ->orWhere('citer_recherche', '');
    }

    /**
     * Scope pour les citations longues.
     */
    public function scopeLongue($query, int $minCaracteres = 200)
    {
        return $query->whereRaw('LENGTH(citer_recherche) >= ?', [$minCaracteres]);
    }

    /**
     * Scope pour les citations courtes.
     */
    public function scopeCourte($query, int $maxCaracteres = 100)
    {
        return $query->whereRaw('LENGTH(citer_recherche) <= ?', [$maxCaracteres]);
    }

    /**
     * Scope pour la recherche textuelle.
     */
    public function scopeRecherche($query, string $terme)
    {
        return $query->where('citer_recherche', 'LIKE', "%{$terme}%");
    }

    /**
     * Scope pour les citations d'une période.
     */
    public function scopePeriode($query, string $debut, string $fin)
    {
        return $query->whereBetween('created_at', [$debut, $fin]);
    }
}