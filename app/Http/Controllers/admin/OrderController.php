<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Seat;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request){
        $orderItems = OrderItem::latest('order_items.created_at')->with('orders')->get();

        $orderCount = DB::table('orders')
                    ->select(DB::raw('count(*) as count'))
                    ->get()[0]->count;

        $dineinCount = DB::table('orders')
                    ->where('order_type', 'Dinein')
                    ->select(DB::raw('count(*) as count'))
                    ->get()[0]->count;

        $takeawayCount = DB::table('orders')
                    ->where('order_type', 'Takeaway')
                    ->select(DB::raw('count(*) as count'))
                    ->get()[0]->count;

        $deliveryCount = DB::table('orders')
                    ->where('order_type', 'Delivery')
                    ->select(DB::raw('count(*) as count'))
                    ->get()[0]->count;

        $data = [
            'orderItems' => $orderItems,
            'orderCount' => $orderCount,
            'dineinCount' => $dineinCount,
            'takeawayCount' => $takeawayCount,
            'deliveryCount' => $deliveryCount
        ];

        //dd($dineinCount);

        //$orders = $orders->paginate(10);
        return view('admin.orders.list', $data);
    }

    public function detail($orderId){
        $order = Order::with('seat')->findOrFail($orderId);
        $orderItems = OrderItem::where('order_id',$orderId)->get();  
        $products = Product::latest('id');      

        //dd($order);
        
        return view('admin.orders.detail',[
            'order' => $order,
            'orderItems' => $orderItems,
            'products' => $products,
        ]);
    }


    public function changeOrderStatus(Request $request, $id){
        $order = Order::find($id);
        $order->status = $request->status;
        $order->shipped_date = $request->shipped_date;
        $order->save();

        $message = 'Order status updated successfully';

        session()->flash('success',$message);

        return response()->json([
            'status' => true,
            'message' => $message,
        ]);
    }

    public function sendInvoiceEmail(Request $request, $orderId){
        orderEmail($orderId, $request->userType);

        $message = 'Order email sent successfully';

        session()->flash('success',$message);

        return response()->json([
            'status' => true,
            'message' => $message,
        ]);
    }
}
