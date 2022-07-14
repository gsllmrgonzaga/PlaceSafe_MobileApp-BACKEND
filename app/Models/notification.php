<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class notification extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'notification';
    
    protected $fillable = [
        'email',

    ];
}
