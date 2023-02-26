<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;

class PaymentController extends Controller
{
    public function index($token, $order_id)
    {
        $order =
            DB::table('orders')
            ->where('id', $order_id)
            ->first();

        $order_items =
            DB::table('order_items')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->where('order_id', $order_id)
            ->get();

        $user =
            DB::table('users')
            ->select('name', 'email', 'address', 'phone_number')
            ->where('id', $order->user_id)
            ->first();

        return view('payment')->with([
            'snapToken' => $token,
            'order' => $order,
            'user' => $user,
            'order_items' => $order_items
        ]);
    }

    public function webhookMidtrans(Request $request)
    {
        $json = json_decode($request->json);
        if ($json->transaction_status == 'capture' or $json->transaction_status == 'settlement') {
            DB::table('orders')
                ->where('id', $json->order_id)
                ->update([
                    'status' => 'PAID',
                ]);
        } else {
            return [$request->input(), "copy first array and change transaction status to settlement in postman then refresh this page."];
        }

        return $this->index($request->snapToken, $json->order_id);
    }
}
