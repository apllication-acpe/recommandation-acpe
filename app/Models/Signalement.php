<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signalement extends Model
{
    protected $fillable = [
        'user_id',
        'signalable_type',
        'signalable_id',
        'motif',
        'description',
        'gravite',
        'statut',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function signalable()
    {
        return $this->morphTo();
    }
}
