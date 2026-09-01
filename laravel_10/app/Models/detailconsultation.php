<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detailconsultation extends Model
{
    use HasFactory;

    protected $fillable =[
        'consultation_id',
        'typeacte_id',
        'part_assurance',
        'part_patient',
        'remise',
        'motif',
        'type_motif',
        'libelle',
        'periode',
        'appliq_remise',
    ];

    public function typeacte()
    {
        return $this->belongsTo(typeacte::class, 'typeacte_id');
    }

    public function consultation()
    {
        return $this->belongsTo(consultation::class, 'consultation_id');
    }
}
