<?php

namespace App\Repositories;

use App\Commons\Consts\OrderStatus;
use App\Commons\Consts\PaymentMethod;
use App\Commons\Consts\PaymentStatus;
use App\Models\Order;

class OrderRepository
{
    public function create($orderInput)
    {
        return Order::create($orderInput);
    }
    public function getCartStatusOrder($userId, $withProducts)
    {
        $queryBuilder = Order::where("user_id", $userId)->where("order_status", OrderStatus::CART);
        if ($withProducts) {
            $queryBuilder->with("products");
        }
        return $queryBuilder->first();
    }
    public function updateTotalPrice($order, $cartPrice)
    {
        $order->total_price += $cartPrice;
        $order->save();
    }
    public function delete($id)
    {
        $order = Order::find($id);
        $order->delete();
    }
    public function cashPayment($userId)
    {
        $order = $this->getCartStatusOrder($userId, false);
        $order->order_status = OrderStatus::PENDING;
        $order->payment_status = PaymentStatus::UNPAID;
        $order->payment_method = PaymentMethod::CASH;
        $order->save();
    }
    public function onlinePayment($paymentInfo)
    {
        $order = Order::find($paymentInfo["order_id"]);
        $order->payment_status = $paymentInfo["payment_status"];
        $order->order_status = $paymentInfo["order_status"];
        $order->payment_method = $paymentInfo["payment_method"];
        $order->invoice_id = $paymentInfo["invoice_id"];
        $order->transaction_id = isset($paymentInfo["transaction_id"]) ? $paymentInfo["transaction_id"] : 0;
        $order->save();
    }
    public function getPage($pageSize, $text, $userId, $orderStatus, $paymentStatus, $paymentMethod)
    {
        return Order::where("order_status", "<>", OrderStatus::CART)
            ->where(function ($query) use ($userId) {
                $query->when($userId, function ($q) use ($userId) {
                    $q->where("user_id", $userId);
                });
            })
            ->where(function ($query) use ($paymentStatus) {
                $query->when($paymentStatus, function ($q) use ($paymentStatus) {
                    $q->where('payment_status', $paymentStatus);
                });
            })
            ->where(function ($query) use ($orderStatus) {
                $query->when($orderStatus, function ($q) use ($orderStatus) {
                    $q->where('order_status', $orderStatus);
                });
            })
            ->where(function ($query) use ($paymentMethod) {
                $query->when($paymentMethod, function ($q) use ($paymentMethod) {
                    $q->where('payment_method', $paymentMethod);
                });
            })
            ->where(function ($query) use ($text) {
                $query->when($text, function ($q) use ($text) {
                    $q->where('id', $text);
                    $q->orWhere('total_price', $text);
                    $q->orWhere('transaction_id', $text);
                    $q->orWhere('invoice_id', $text);
                    $q->orWhereHas("user", function ($q) use ($text) {
                        $q->where("phone",$text)
                        ->orWhereRaw(
                            "lower(email) like ?",
                            ["%" . strtolower($text) . '%']
                        )->orWhereRaw(
                            "lower(concat(first_name,' ',last_name)) like ?",
                            ["%" . mb_strtolower($text, 'UTF-8') . '%']
                        );
                    });
                });
            })
            ->with("user")
            ->orderBy("id", "desc")
            ->paginate($pageSize);
    }
    public function updateOrderStatus($input)
    {
        $order = Order::find($input["order_id"]);

        $order->order_status = $input["order_status"];
        if ($input["order_status"] == OrderStatus::COMPLETED) {
            $order->payment_status = PaymentStatus::PAID;
        }
        $order->save();
        return $order;
    }
}
