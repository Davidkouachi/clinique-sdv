<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class examenpatient extends Model
{
    use HasFactory;

    protected $fillable = [
        'accepte',
        'typeacte_id',
        'examen_id',
    ];

    public function examen()
    {
        return $this->belongsTo(examen::class, 'examen_id');
    }

    public function typeacte()
    {
        return $this->belongsTo(typeacte::class, 'typeacte_id');
    }
}
