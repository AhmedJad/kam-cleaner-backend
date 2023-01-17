<?php

namespace App\Http\Controllers;

use App\Commons\Consts\OrderStatus;
use App\Commons\Consts\PaymentMethod;
use App\Commons\Consts\PaymentStatus;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private $orderRepository;
    public function __construct(OrderRepository $orderRepository)
    {
        $this->middleware("auth")->except(["callback_success", "callback_error"]);
        $this->orderRepository = $orderRepository;
    }
    public function cashPayment()
    {
        $this->orderRepository->cashPayment(request()->user()->id);
    }
    public function onlinePayment()
    {
        $myfatorah = new MyFatoorahController();
        $user = request()->user();
        $order = $this->orderRepository->getCartStatusOrder(request()->user()->id, false);
        $response = $myfatorah->pay(
            $order->total_price,
            $user->first_name,
            $user->phone,
            $user->email,
            $order->id,
            "+2",
            env('APP_URL') . "/api/payment/callback-success",
            env('APP_URL') . "/api/payment/callback-error"
        );
        if ($order && isset($response->IsSuccess) && $response->IsSuccess == 'true') {
            return response()->json(['url' => $response->Data->InvoiceURL], 200);
        }
        return response()->json(['message' => $response], 400);
    }
    public function callback_success(Request $request)
    {
        if (isset($request['paymentId'])) {
            $myfatorah = new MyFatoorahController();
            $postFields = [
                'Key' => $request['paymentId'],
                'KeyType' => 'PaymentId',
            ];
            $data = $myfatorah->callAPI("/v2/GetPaymentStatus", $postFields);
            if (isset($data->Data->CustomerReference) && collect($data->Data->InvoiceTransactions)->last()->TransactionStatus == "Succss") {
                $this->orderRepository->onlinePayment($this->getSuccessPaymentInfo($data->Data));
                return redirect(env("UI_URL") . "/order-success");
            }
        }
    }
    public function callback_error(Request $request)
    {
        if (isset($request['paymentId'])) {
            $myfatorah = new MyFatoorahController();
            $postFields = [
                'Key' => $request['paymentId'],
                'KeyType' => 'PaymentId',
            ];
            $data = $myfatorah->callAPI("/v2/GetPaymentStatus", $postFields);
            if (isset($data->Data->CustomerReference) && collect($data->Data->InvoiceTransactions)->last()->TransactionStatus == "Failed") {
                $this->orderRepository->onlinePayment($this->getFailedPaymentInfo($data->Data));
                return redirect(env("UI_URL") . "/order-error");
            }
        }
    }
    //Commons
    private function getSuccessPaymentInfo($data)
    {
        return [
            "order_id" => $data->CustomerReference,
            "invoice_id" => $data->InvoiceId,
            "transaction_id" => $data->InvoiceTransactions[0]->TransactionId,
            "order_status" => OrderStatus::PENDING,
            "payment_status" => PaymentStatus::PAID,
            "payment_method" => PaymentMethod::ONLINE,
        ];
    }
    private function getFailedPaymentInfo($data)
    {
        return [
            "order_id" => $data->CustomerReference,
            "invoice_id" => $data->InvoiceId,
            "transaction_id" => $data->InvoiceTransactions[0]->TransactionId,
            "order_status" => OrderStatus::FAILD,
            "payment_status" => PaymentStatus::UNPAID,
            "payment_method" => PaymentMethod::ONLINE,
        ];
    }
}
