<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class examen extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_assurance',
        'part_patient',
        'statut',
        'montant',
        'libelle',
        'code',
        'medecin',
        'prelevement',
        'facture_id',
        'patient_id',
        'acte_id',
        'num_bon',
    ];

    public function facture()
    {
        return $this->belongsTo(facture::class, 'facture_id');
    }

    public function patient()
    {
        return $this->belongsTo(patient::class, 'patient_id');
    }

    public function acte()
    {
        return $this->belongsTo(acte::class, 'acte_id');
    }
}
