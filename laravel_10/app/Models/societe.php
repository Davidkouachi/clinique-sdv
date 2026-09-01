<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class societe extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nom',
        'email',
        'fax',
        'tel',
        'tel2',
        'adresse',
        'sgeo',
    ];
}
