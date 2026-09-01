<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class soinsinfirmier extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prix',
        'typesoins_id',
    ];

    public function typesoins()
    {
        return $this->belongsTo(typesoins::class, 'typesoins_id');
    }
}
