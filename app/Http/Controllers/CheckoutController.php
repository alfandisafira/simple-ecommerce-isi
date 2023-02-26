<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use App\Models\User;
use Midtrans\Config;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\CouponDiscount;
use Illuminate\Routing\Controller;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($data = null)
    {
        return view('checkout');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->full_name;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->phone_number = $request->phone_number;
        $user->save();

        $user_id = $user->id;

        $order = new Order();
        $order->user_id = $user_id;
        $order->coupon_discount = $request->coupon;

        $total_order = 0;
        $items = $request->items;
        foreach ($request->items as $item) {
            $total_order += $item['price'] * $item['qty'];
        }
        $order->total_order = $total_order;

        if ($request->coupon) {
            $discount_amount = CouponDiscount::where('code', $request->coupon)->first();
            $discount_amount = $discount_amount->discount_amount;
            $total_amount = $total_order - ($total_order * ($discount_amount / 100));
            $order->total_amount = $total_amount;
        } else {
            $order->total_amount = $total_order;
        }

        $order->status = 'UNPAID';
        $order->save();

        foreach ($request->items as $item) {
            $order_item = new OrderItem;
            $order_item->order_id = $order->id;
            $order_item->product_id = $item['product_id'];
            $order_item->qty = $item['qty'];
            $order_item->save();
        }

        // Set your Merchant Server Key
        Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = false;
        // Set sanitization on (default)
        Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => $order->id,
                'gross_amount' =>  $order->total_amount,
            ),
            'customer_details' => array(
                'name' => $user->name,
                'email' => $user->email,
                'address' => $user->address,
                'phone' => $user->phone_number,
            ),
        );

        $snapToken = Snap::getSnapToken($params);

        $data = [
            'snapToken' => $snapToken,
            'order_id' => $order->id,
        ];

        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
