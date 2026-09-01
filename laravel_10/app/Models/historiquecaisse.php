<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class historiquecaisse extends Model
{
    use HasFactory;

    protected $fillable = [
        'motif',
        'montant',
        'libelle',
        'solde_avant',
        'solde_apres',
        'typemvt',
        'creer_id',
        'date_ope',
    ];

    public function user()
    {
        return $this->belongsTo(user::class, 'creer_id');
    }
}
