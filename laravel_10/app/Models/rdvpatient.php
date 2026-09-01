<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rdvpatient extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'motif',
        'statut',
        'patient_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(user::class, 'user_id');
    }

    public function patient()
    {
        return $this->belongsTo(patient::class, 'patient_id');
    }
}
