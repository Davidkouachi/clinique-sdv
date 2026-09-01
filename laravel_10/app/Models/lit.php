<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lit extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'code',
        'type',
        'statut',
        'chambre_id',
    ];

    public function chambre()
    {
        return $this->belongsTo(chambre::class, 'chambre_id');
    }
}
