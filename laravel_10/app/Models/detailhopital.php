<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detailhopital extends Model
{
    use HasFactory;

    protected $fillable = [
        'statut',
        'part_assurance',
        'part_patient',
        'remise',
        'montant',
        'montant_soins',
        'montant_chambre',
        'date_debut',
        'date_fin',
        'natureadmission_id',
        'facture_id',
        'patient_id',
        'lit_id',
        'user_id',
        'num_bon',
    ];

    public function natureadmission()
    {
        return $this->belongsTo(natureadmission::class, 'natureadmission_id');
    }

    public function facture()
    {
        return $this->belongsTo(facture::class, 'facture_id');
    }

    public function patient()
    {
        return $this->belongsTo(patient::class, 'patient_id');
    }

    public function lit()
    {
        return $this->belongsTo(lit::class, 'lit_id');
    }

    public function user()
    {
        return $this->belongsTo(user::class, 'user_id');
    }
}
