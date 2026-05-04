<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Localisation extends Model
{
    use HasFactory;

    /**
     * La clé primaire de la table.
     *
     * @var string
     */
    protected $primaryKey = 'id_localisation';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ville',
        'code_postal',
        'pays',
        'region',
        'latitude',
        'longitude',
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
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Accesseurs & Mutateurs
     */

    /**
     * Obtenir le libellé complet de la localisation.
     */
    public function getLibelleCompletAttribute(): string
    {
        $parts = [$this->ville];
        
        if ($this->code_postal) {
            $parts[] = $this->code_postal;
        }
        
        if ($this->region) {
            $parts[] = $this->region;
        }
        
        $parts[] = $this->pays;
        
        return implode(', ', $parts);
    }

    /**
     * Obtenir le libellé court.
     */
    public function getLibelleCourtAttribute(): string
    {
        return "{$this->ville}, {$this->pays}";
    }

    /**
     * Obtenir le nom formaté pour l'affichage.
     */
    public function getNomAttribute(): string
    {
        return $this->libelle_court;
    }

    /**
     * Vérifier si la localisation a des coordonnées GPS.
     */
    public function getHasCoordinatesAttribute(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    /**
     * Obtenir l'URL Google Maps.
     */
    public function getGoogleMapsUrlAttribute(): string
    {
        if ($this->has_coordinates) {
            return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
        }
        
        $query = urlencode($this->libelle_complet);
        return "https://www.google.com/maps/search/?api=1&query={$query}";
    }

    /**
     * Obtenir l'URL Google Maps en iframe.
     */
    public function getGoogleMapsIframeUrlAttribute(): string
    {
        if ($this->has_coordinates) {
            return "https://www.google.com/maps/embed/v1/place?key=YOUR_API_KEY&q={$this->latitude},{$this->longitude}";
        }
        
        $query = urlencode($this->libelle_complet);
        return "https://www.google.com/maps/embed/v1/place?key=YOUR_API_KEY&q={$query}";
    }

    /**
     * Obtenir le drapeau du pays (emoji).
     */
    public function getDrapeauAttribute(): string
    {
        $drapeaux = [
            'Congo' => '🇨🇬',
            'République Démocratique du Congo' => '🇨🇩',
            'France' => '🇫🇷',
            'Canada' => '🇨🇦',
            'Belgique' => '🇧🇪',
            'Suisse' => '🇨🇭',
            'Sénégal' => '🇸🇳',
            'Côte d\'Ivoire' => '🇨🇮',
            'Cameroun' => '🇨🇲',
            'Gabon' => '🇬🇦',
        ];

        return $drapeaux[$this->pays] ?? '🌍';
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
            'offre_localisation',
            'id_localisation',
            'id_offre'
        )->withPivot('est_principale', 'teletravail_possible')->withTimestamps();
    }

    /**
     * Relation avec les offres où c'est la localisation principale.
     */
    public function offresPrincipales(): BelongsToMany
    {
        return $this->offres()->wherePivot('est_principale', true);
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Vérifier si la localisation est utilisée dans au moins une offre.
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
     * Compter le nombre d'offres où c'est la localisation principale.
     */
    public function getOffresPrincipalesCount(): int
    {
        return $this->offresPrincipales()->count();
    }

    /**
     * Obtenir toutes les localisations sous forme de tableau pour les selects.
     */
    public static function getForSelect(): array
    {
        return self::orderBy('pays')
            ->orderBy('ville')
            ->get()
            ->mapWithKeys(fn($localisation) => [
                $localisation->id_localisation => $localisation->libelle_complet
            ])
            ->toArray();
    }

    /**
     * Obtenir les localisations par pays.
     */
    public static function getByPays(string $pays): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('pays', $pays)->orderBy('ville')->get();
    }

    /**
     * Obtenir les localisations populaires (plus d'offres).
     */
    public static function getPopulaires(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return self::withCount('offres')
            ->having('offres_count', '>', 0)
            ->orderBy('offres_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtenir les pays disponibles.
     */
    public static function getPaysDisponibles(): array
    {
        return self::select('pays')
            ->distinct()
            ->orderBy('pays')
            ->pluck('pays')
            ->toArray();
    }

    /**
     * Obtenir les régions disponibles pour un pays.
     */
    public static function getRegionsByPays(string $pays): array
    {
        return self::where('pays', $pays)
            ->whereNotNull('region')
            ->distinct()
            ->orderBy('region')
            ->pluck('region')
            ->toArray();
    }

    /**
     * Calculer la distance entre deux localisations (en km).
     */
    public function distanceTo(self $other): ?float
    {
        if (!$this->has_coordinates || !$other->has_coordinates) {
            return null;
        }
        
        $lat1 = deg2rad($this->latitude);
        $lon1 = deg2rad($this->longitude);
        $lat2 = deg2rad($other->latitude);
        $lon2 = deg2rad($other->longitude);
        
        $earthRadius = 6371; // Rayon de la Terre en km
        
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        
        $a = sin($dlat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($dlon / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return round($earthRadius * $c, 2);
    }

    /**
     * Obtenir la distance formatée.
     */
    public function distanceFormattedTo(self $other): string
    {
        $distance = $this->distanceTo($other);
        
        if ($distance === null) {
            return 'Distance non disponible';
        }
        
        if ($distance < 1) {
            return round($distance * 1000) . ' m';
        }
        
        return $distance . ' km';
    }

    /**
     * Rechercher des localisations proches (rayon en km).
     */
    public static function findNearby(float $latitude, float $longitude, int $radius = 10): \Illuminate\Database\Eloquent\Collection
    {
        return self::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw("*, 
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
                cos(radians(longitude) - radians(?)) + 
                sin(radians(?)) * sin(radians(latitude)))) AS distance", 
                [$latitude, $longitude, $latitude])
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->get();
    }

    /**
     * Scopes
     */

    /**
     * Scope pour un pays spécifique.
     */
    public function scopeEnPays($query, string $pays)
    {
        return $query->where('pays', $pays);
    }

    /**
     * Scope pour une région spécifique.
     */
    public function scopeEnRegion($query, string $region)
    {
        return $query->where('region', $region);
    }

    /**
     * Scope pour les localisations avec coordonnées GPS.
     */
    public function scopeAvecCoordonnees($query)
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }

    /**
     * Scope pour les localisations sans coordonnées GPS.
     */
    public function scopeSansCoordonnees($query)
    {
        return $query->whereNull('latitude')->orWhereNull('longitude');
    }

    /**
     * Scope pour la recherche textuelle.
     */
    public function scopeRecherche($query, string $terme)
    {
        return $query->where('ville', 'LIKE', "%{$terme}%")
            ->orWhere('code_postal', 'LIKE', "%{$terme}%")
            ->orWhere('pays', 'LIKE', "%{$terme}%")
            ->orWhere('region', 'LIKE', "%{$terme}%");
    }

    /**
     * Scope pour les localisations actives (utilisées dans les offres).
     */
    public function scopeActive($query)
    {
        return $query->has('offres');
    }
}