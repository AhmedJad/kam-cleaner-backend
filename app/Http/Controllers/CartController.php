<?php

namespace App\Http\Controllers;

use App\Commons\Consts\OrderStatus;
use App\Http\Requests\CartRequest;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;

class CartController extends Controller
{
    private $cartRepository;
    private $orderRepository;
    public function __construct(
        CartRepository $cartRepository,
        OrderRepository $orderRepository
    ) {
        $this->middleware("auth");
        $this->cartRepository = $cartRepository;
        $this->orderRepository = $orderRepository;
    }
    public function addToCart(CartRequest $request)
    {
        $order = $this->orderRepository->getCartStatusOrder($request->user()->id, false);
        if (!$order) {
            $order = $this->orderRepository->create($this->getOrderInput($request));
        }
        $cart = $this->cartRepository->create($this->getCartInput($order->id, $request));
        $this->orderRepository->updateTotalPrice($order, $cart->price);
    }
    public function removeCartItem($productId)
    {
        $order = $this->orderRepository->getCartStatusOrder(request()->user()->id, true);
        if ($order) {
            $this->cartRepository->removeCartItem($order->id, $productId);
            if (count($order->products) == 1) {
                $this->orderRepository->delete($order->id);
            }
        }
    }
    public function getCartItems()
    {
        $order = $this->orderRepository->getCartStatusOrder(request()->user()->id, true);
        return response()->json([
            "products" => $order ? $order->products : [],
            "total_price" => $order ? $order->total_price : 0
        ]);
    }
    //Commons
    private function getOrderInput($request)
    {
        return [
            "user_id" => $request->user()->id,
            "order_status" => OrderStatus::CART,
        ];
    }
    private function getCartInput($orderId, $request)
    {
        return [
            "order_id" => $orderId,
            "product_id" => $request->product_id,
            "price" => $request->price,
        ];
    }
}
