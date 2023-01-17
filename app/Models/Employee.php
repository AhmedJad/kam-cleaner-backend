<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $guarded = [];
    public function getImageAttribute($value)
    {
        return $value ? asset("images/$value") : null;
    }
    public function getImageName()
    {
        return $this->attributes["image"];
    }
}
