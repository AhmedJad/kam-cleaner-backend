<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function products()
    {
        return $this->belongsToMany(Product::class, CartItem::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
