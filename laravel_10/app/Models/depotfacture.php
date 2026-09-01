<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class depotfacture extends Model
{
    use HasFactory;

    protected $fillable =[
        'assurance_id',
        'date1',
        'date2',
        'date_depot',
        'type_paiement',
        'num_cheque',
        'date_payer',
        'statut',
        'creer_id',
        'encaisser_id',
    ];

    public function assurance()
    {
        return $this->belongsTo(assurance::class, 'assurance_id');
    }

    public function creer()
    {
        return $this->belongsTo(user::class, 'creer_id');
    }

    public function encaisser()
    {
        return $this->belongsTo(user::class, 'encaisser_id');
    }
}
