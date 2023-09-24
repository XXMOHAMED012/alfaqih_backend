<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class OrdersController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'user_id' => 'required',
            'car_id' => 'required',
        ]);

        $previousOrder = Order::where('user_id', $request->user_id)
            ->where('car_id', $request->car_id)
            ->first();

        if ($previousOrder) {
            return response()->json([
                'message' => 'You already ordered this car'
            ], 400);
        }

        $order = new Order();
        $order->user_id = $request->user_id;
        $order->car_id = $request->car_id;
        $order->done = false;
        $order->save();
        Mail::raw(' admin.alfaqihcars.com/orders هناك طلب جديد على الموقع. تحقق الآن', function (Message $message) {
            $message->to('admin@alfaqihcars.com')->subject('طلب جديد');
        });

        return response()->json($order);
    }
}