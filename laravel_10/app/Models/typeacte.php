<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class typeacte extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prix',
        'acte_id',
        'cotation',
        'valeur',
        'montant',
    ];

    public function acte()
    {
        return $this->belongsTo(acte::class, 'acte_id');
    }
}
