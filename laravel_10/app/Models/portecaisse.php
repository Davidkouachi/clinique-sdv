<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class portecaisse extends Model
{
    use HasFactory;

    protected $fillable = [
        'montant',
        'motif',
        'creer_id',
    ];

    public function user()
    {
        return $this->belongsTo(user::class, 'creer_id');
    }
}
