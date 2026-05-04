<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\Offre;
use App\Models\Candidature;

class Entreprise extends Model
{
    use HasFactory;

    /**
     * La clé primaire de la table.
     *
     * @var string
     */
    protected $primaryKey = 'id_entreprise';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'raison_sociale',
        'forme_juridique',
        'taille',
        'adresse',
        'email_contact',
        'telephone',
        'logo_path',
        'site_web',
        'verifiee',
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
        'verifiee' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Accesseurs & Mutateurs
     */

    /**
     * Obtenir le chemin complet du logo.
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo_path) {
            return null;
        }
        return asset('storage/' . $this->logo_path);
    }

    /**
     * Obtenir le logo par défaut si aucun n'est défini.
     */
    public function getLogoOrDefaultAttribute(): string
    {
        return $this->logo_url ?? asset('images/default-company-logo.png');
    }

    /**
     * Obtenir le site web formaté (avec https:// si nécessaire).
     */
    public function getSiteWebFormateAttribute(): string
    {
        $site = $this->site_web;
        if ($site && !preg_match('/^https?:\/\//', $site)) {
            $site = 'https://' . $site;
        }
        return $site;
    }

    /**
     * Obtenir le libellé complet de l'entreprise.
     */
    public function getLibelleCompletAttribute(): string
    {
        $parts = [$this->raison_sociale];
        
        if ($this->forme_juridique) {
            $parts[] = '(' . $this->forme_juridique . ')';
        }
        
        return implode(' ', $parts);
    }

    /**
     * Obtenir la taille formatée.
     */
    public function getTailleFormateeAttribute(): string
    {
        $tailles = [
            '1-10' => 'Très petite (1-10 employés)',
            '11-50' => 'Petite (11-50 employés)',
            '51-200' => 'Moyenne (51-200 employés)',
            '201-500' => 'Grande (201-500 employés)',
            '501+' => 'Très grande (501+ employés)',
        ];
        
        return $tailles[$this->taille] ?? $this->taille ?? 'Non spécifiée';
    }

    /**
     * Relations
     */

    /**
     * Relation avec les offres d'emploi.
     */
    public function offres(): HasMany
    {
        return $this->hasMany(Offre::class, 'id_entreprise', 'id_entreprise');
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
     * Relation avec les candidatures (via les offres).
     */
    public function candidatures(): HasManyThrough
    {
        return $this->hasManyThrough(
            Candidature::class,
            Offre::class,
            'id_entreprise',
            'id_offre',
            'id_entreprise',
            'id_offre'
        );
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Vérifier si l'entreprise est vérifiée.
     */
    public function isVerified(): bool
    {
        return $this->verifiee;
    }

    /**
     * Vérifier si l'entreprise a des offres actives.
     */
    public function hasActiveOffers(): bool
    {
        return $this->offresActives()->exists();
    }

    /**
     * Compter le nombre d'offres actives.
     */
    public function getActiveOffersCount(): int
    {
        return $this->offresActives()->count();
    }

    /**
     * Compter le nombre total de candidatures reçues.
     */
    public function getTotalCandidaturesCount(): int
    {
        return $this->candidatures()->count();
    }

    /**
     * Obtenir le nombre total de vues sur les offres.
     */
    public function getTotalVuesOffres(): int
    {
        return $this->offres()->sum('nb_vues') ?? 0;
    }

    /**
     * Obtenir le top 5 des offres les plus consultées.
     */
    public function getTopOffres(int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return $this->offres()
            ->orderBy('nb_vues', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Vérifier si l'entreprise a un numéro de TVA (à adapter selon besoin).
     */
    public function hasNumeroTVA(): bool
    {
        // Tu peux ajouter un champ 'numero_tva' dans la migration
        return !empty($this->numero_tva);
    }

    /**
     * Scopes
     */

    /**
     * Scope pour les entreprises vérifiées.
     */
    public function scopeVerifiee($query)
    {
        return $query->where('verifiee', true);
    }

    /**
     * Scope pour les entreprises non vérifiées.
     */
    public function scopeNonVerifiee($query)
    {
        return $query->where('verifiee', false);
    }

    /**
     * Scope pour les entreprises par taille.
     */
    public function scopeDeTaille($query, string $taille)
    {
        return $query->where('taille', $taille);
    }

    /**
     * Scope pour les entreprises avec des offres actives.
     */
    public function scopeAvecOffresActives($query)
    {
        return $query->whereHas('offresActives');
    }

    /**
     * Scope pour les entreprises sans offres.
     */
    public function scopeSansOffres($query)
    {
        return $query->doesntHave('offres');
    }

    /**
     * Scope pour les entreprises par secteur d'activité (si tu ajoutes cette relation).
     */
    public function scopeParSecteur($query, int $idSecteur)
    {
        return $query->whereHas('offres', function ($q) use ($idSecteur) {
            $q->where('id_sect_act', $idSecteur);
        });
    }

    /**
     * Scope pour la recherche textuelle.
     */
    public function scopeRecherche($query, string $terme)
    {
        return $query->where(function ($q) use ($terme) {
            $q->where('raison_sociale', 'LIKE', "%{$terme}%")
              ->orWhere('adresse', 'LIKE', "%{$terme}%")
              ->orWhere('email_contact', 'LIKE', "%{$terme}%");
        });
    }
}