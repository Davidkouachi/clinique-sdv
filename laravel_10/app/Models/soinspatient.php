<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class soinspatient extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'code',
        'statut',
        'part_assurance',
        'part_patient',
        'remise',
        'montant',
        'libelle',
        'patient_id',
        'facture_id',
        'typesoins_id',
        'num_bon',
        'assurance_utiliser',
    ];

    public function patient()
    {
        return $this->belongsTo(patient::class, 'patient_id');
    }

    public function facture()
    {
        return $this->belongsTo(facture::class, 'facture_id');
    }

    public function typesoins()
    {
        return $this->belongsTo(typesoins::class, 'typesoins_id');
    }
}
