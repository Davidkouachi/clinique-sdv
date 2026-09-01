<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class programmemedecin extends Model
{
    use HasFactory;

    protected $fillable = [
        'periode',
        'heure_debut',
        'heure_fin',
        'statut',
        'user_id',
        'jour_id',
    ];

    public function user()
    {
        return $this->belongsTo(user::class, 'user_id');
    }

    public function joursemaine()
    {
        return $this->belongsTo(joursemaine::class, 'jour_id');
    }
}
