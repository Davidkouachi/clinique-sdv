<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class typeproduit extends Model
{
    use HasFactory;

    protected $fillable =[
        'nom',
        'produit_id',
        'taux_id',
    ];

    public function produit()
    {
        return $this->belongsTo(produit::class, 'produit_id');
    }

    public function taux()
    {
        return $this->belongsTo(taux::class, 'taux_id');
    }
}
