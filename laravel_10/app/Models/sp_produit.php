<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sp_produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'quantite',
        'montant',
        'produit_id',
        'soinspatient_id',
    ];

    public function produit()
    {
        return $this->belongsTo(produit::class, 'produit_id');
    }

    public function soinspatient()
    {
        return $this->belongsTo(soinspatient::class, 'soinspatient_id');
    }
}
