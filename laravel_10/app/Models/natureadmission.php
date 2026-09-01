<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class natureadmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'typeadmission_id',
    ];

    public function typeadmission()
    {
        return $this->belongsTo(typeadmission::class, 'typeadmission_id');
    }
    
}
