<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationsManagement extends Model
{
    use HasFactory;

    protected $table = 'locations';

    protected $fillableCity = [
        'locations_id','locations_name', 'province', 'locations_latitude','locations_longitude'
    ];
}
