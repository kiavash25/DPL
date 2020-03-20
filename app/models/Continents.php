<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Continents extends Model
{
    protected $table = 'continents';
    protected $primaryKey = 'code';
    public $timestamps = false;
}
