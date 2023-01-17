<?php

namespace App\Http\Controllers;

use App\Commons\Consts\OrderStatus;
use App\Models\User;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $orderRepository;
    public function __construct(OrderRepository $orderRepository)
    {
        $this->middleware(["auth"])->only(["getUserOrders"]);
        $this->orderRepository = $orderRepository;
    }
    public function getAllOrders()
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        return $this->getOrders(null);
    }
    public function getUserOrders()
    {
        return $this->getOrders(request()->user()->id);
    }
    public function updateOrderStatus(Request $request)
    {
        $this->authorize("role", [User::class, ["Admin"]]);
        $request->merge(["order_status" => $this->getNextOrderStatus($request->order_status)]);
        $order = $this->orderRepository->updateOrderStatus($request->input());
        return $order;
    }
    //Commons
    private function getNextOrderStatus($status)
    {
        switch ($status) {
            case OrderStatus::PENDING;
                return OrderStatus::PROCESSING;
            case OrderStatus::PROCESSING;
                return OrderStatus::SHIPPING;
            case OrderStatus::SHIPPING;
                return OrderStatus::COMPLETED;
        }
    }
    public function getOrders($userId)
    {
        $text = isset(request()->text) ? request()->text : '';
        $orderStatus = isset(request()->order_status) ? request()->order_status : '';
        $paymentStatus = isset(request()->payment_status) ? request()->payment_status : '';
        $paymentMethod = isset(request()->payment_method) ? request()->payment_method : '';
        return $this->orderRepository->getPage(
            request()->page_size,
            $text,
            $userId,
            $orderStatus,
            $paymentStatus,
            $paymentMethod,
        );
    }
}
