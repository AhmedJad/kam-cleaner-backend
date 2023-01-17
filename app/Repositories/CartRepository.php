<?php

namespace App\Repositories;

use App\Models\CartItem;

class CartRepository
{
    public function create($cartInput)
    {
        return CartItem::create($cartInput);
    }
    public function removeCartItem($orderId, $productId)
    {
        CartItem::where("order_id", $orderId)->where("product_id", $productId)->delete();
    }
}
