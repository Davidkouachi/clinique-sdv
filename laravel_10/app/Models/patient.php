<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class patient extends Model
{
    use HasFactory;

    protected $fillable =[
        'np',
        'email',
        'tel',
        'tel2',
        'assurer',
        'matricule',
        'adresse',
        'datenais',
        'sexe',
        'filiation',
        'matricule_assurance',
        'assurance_id',
        'taux_id',
        'societe_id',
        'societe_id',
    ];

    public function assurance()
    {
        return $this->belongsTo(assurance::class, 'assurance_id');
    }
    public function taux()
    {
        return $this->belongsTo(taux::class, 'taux_id');
    }
    public function societe()
    {
        return $this->belongsTo(societe::class, 'societe_id');
    }
}
