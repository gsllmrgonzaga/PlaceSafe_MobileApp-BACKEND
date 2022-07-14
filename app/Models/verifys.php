<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class verifys extends Model
{
    use HasFactory,  HasApiTokens;

    protected $fillable = [
        'email'
    ];

    protected $hidden = [
        'remember_token',
    ];
}
