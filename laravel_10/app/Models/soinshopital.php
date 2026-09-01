<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class soinshopital extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'quantite',
        'produit_id',
        'detailhopital_id',
        'montant',
    ];

    public function produit()
    {
        return $this->belongsTo(produit::class, 'produit_id');
    }

    public function detailhopital()
    {
        return $this->belongsTo(detailhopital::class, 'detailhopital_id');
    }
}
