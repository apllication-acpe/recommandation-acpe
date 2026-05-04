<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Demandeur;
use App\Models\Message;

/**
 * @property int $id
 * @property string $nom
 * @property string $prenom
 * @property string $nom_complet
 * @property string $email
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'telephone',
        'actif',
        'provider',
        'provider_id',
        'avatar',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function demandeur(): HasOne
    {
        return $this->hasOne(Demandeur::class, 'user_id', 'id');
    }

    /**
     * Accesseurs
     */

    /**
     * Obtenir le nom complet de l'utilisateur.
     */
    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    /**
     * Messages envoyés.
     */
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Messages reçus.
     */
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Nombre de messages non lus.
     */
    public function unreadMessagesCount(): int
    {
        return $this->receivedMessages()->whereNull('lu_at')->count();
    }
    /**
     * URL de l'avatar (priorité au profil demandeur).
     */
    public function getAvatarUrlAttribute(): string
    {
        $demandeur = $this->demandeur;
        if ($demandeur && $demandeur->photo_path) {
            return asset('storage/' . $demandeur->photo_path);
        }
        
        if (isset($this->avatar) && $this->avatar) {
            return \Illuminate\Support\Str::startsWith($this->avatar, 'http') ? $this->avatar : asset('storage/' . $this->avatar);
        }

        return "https://ui-avatars.com/api/?name=" . urlencode($this->prenom . ' ' . $this->nom) . "&background=f8f9fa&color=204263";
    }
}
