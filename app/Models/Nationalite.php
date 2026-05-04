<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nationalite extends Model
{
    use HasFactory;

    /**
     * La clé primaire de la table.
     *
     * @var string
     */
    protected $primaryKey = 'id_nationalite';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'libelle',
        'code_iso',
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
     * Relations
     */

    /**
     * Un pays a plusieurs demandeurs.
     */
    public function demandeurs()
    {
        return $this->hasMany(Demandeur::class, 'id_nationalite', 'id_nationalite');
    }

    /**
     * Un pays a plusieurs utilisateurs (si tu ajoutes nationalité aux users)
     */
    public function users()
    {
        return $this->hasMany(User::class, 'id_nationalite', 'id_nationalite');
    }

    /**
     * Accesseurs et mutateurs
     */

    /**
     * Obtenir le libellé en majuscules.
     */
    public function getLibelleMajusculeAttribute(): string
    {
        return strtoupper($this->libelle);
    }

    /**
     * Obtenir le code ISO en minuscules.
     */
    public function getCodeIsoMinusculeAttribute(): string
    {
        return strtolower($this->code_iso);
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Rechercher une nationalité par son code ISO.
     */
    public static function findByCodeIso(string $codeIso): ?self
    {
        return self::where('code_iso', strtoupper($codeIso))->first();
    }

    /**
     * Rechercher une nationalité par son libellé.
     */
    public static function findByLibelle(string $libelle): ?self
    {
        return self::where('libelle', 'LIKE', "%{$libelle}%")->first();
    }

    /**
     * Vérifier si la nationalité est valide.
     */
    public function isValid(): bool
    {
        return !empty($this->libelle) && !empty($this->code_iso);
    }
}