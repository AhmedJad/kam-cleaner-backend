<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $guarded = [];
    protected $casts = [
        'features' => 'json',
    ];
    public function getImageAttribute($value)
    {
        return $value ? asset("images/$value") : null;
    }
    public function getImageName()
    {
        return $this->attributes["image"];
    }
}
