<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class consultation extends Model
{
    use HasFactory;

    protected $fillable =[
        'patient_id',
        'user_id',
        'facture_id',
        'matricule_patient',
        'code',
        'num_bon',
        'assurance_utiliser',
    ];

    public function patient()
    {
        return $this->belongsTo(patient::class, 'patient_id');
    }

    public function user()
    {
        return $this->belongsTo(user::class, 'user_id');
    }

    public function facture()
    {
        return $this->belongsTo(facture::class, 'facture_id');
    }
}
