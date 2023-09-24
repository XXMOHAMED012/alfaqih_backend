<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index() {
        $orders = Order::with('car', 'user')->get();
        return response()->json($orders);
    }

    public function show($id) {
        $order = Order::with('car', 'user')->findOrFail($id);
        return response()->json($order);
    }

    public function update(Request $request, $id) {
        $order = Order::findOrFail($id);
        $order->done = $request->done;
        $order->save();
        return response()->json($order);
    }

    public function destroy($id)
    {
        $car = Order::find($id);

        if (!$car) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $car->delete();
        
        return response()->json(['message' => 'Order deleted'], 204);
    }
}
