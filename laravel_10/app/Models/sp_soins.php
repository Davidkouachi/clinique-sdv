<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sp_soins extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'montant',
        'soinsinfirmier_id',
        'soinspatient_id',
    ];

    public function soinsinfirmier()
    {
        return $this->belongsTo(soinsinfirmier::class, 'soinsinfirmier_id');
    }

    public function soinspatient()
    {
        return $this->belongsTo(soinspatient::class, 'soinspatient_id');
    }
}
