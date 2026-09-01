<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class typemedecin extends Model
{
    use HasFactory;

    protected $fillable = [
        'typeacte_id',
        'user_id',
    ];

    public function typeacte()
    {
        return $this->belongsTo(typeacte::class, 'typeacte_id');
    }

    public function user()
    {
        return $this->belongsTo(user::class, 'user_id');
    }
}
