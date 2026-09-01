<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'statut',
        'montant_verser',
        'montant_remis',
        'code',
        'date_payer',
        'acte',
        'creer_id',
        'encaisser_id',
    ];

    public function creer()
    {
        return $this->belongsTo(user::class, 'creer_id');
    }

    public function encaisser()
    {
        return $this->belongsTo(user::class, 'encaisser_id');
    }
}
