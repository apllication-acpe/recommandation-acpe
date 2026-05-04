<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Offre;

class Message extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_message';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'id_offre',
        'objet',
        'contenu',
        'lu_at',
        'piece_jointe_path',
    ];

    protected $casts = [
        'lu_at' => 'datetime',
    ];

    /**
     * L'expéditeur du message.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Le destinataire du message.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * L'offre liée au message (optionnel).
     */
    public function offre(): BelongsTo
    {
        return $this->belongsTo(Offre::class, 'id_offre', 'id_offre');
    }

    /**
     * Marquer le message comme lu.
     */
    public function markAsRead(): bool
    {
        return $this->update(['lu_at' => now()]);
    }

    /**
     * Vérifier si le message est lu.
     */
    public function isRead(): bool
    {
        return $this->lu_at !== null;
    }
}
