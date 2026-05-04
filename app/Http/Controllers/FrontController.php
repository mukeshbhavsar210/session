<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Seat;
use Illuminate\Support\Facades\DB;

class FrontController extends Controller {
    public function show() {
        $products = Product::orderBy('id','DESC')->get();
        $areas = Area::orderBy('id','DESC')->with('seat')->orderBy('id','DESC')->get();
        $seats = Seat::orderBy('id','DESC')->get();        
      
        $data = [
            'products'=> $products,
            'areas'=> $areas,
            'seats'=> $seats,
        ];

        return view('front.home.index', $data);        
    }

    //Wishlist page
    public function wishlist() {
        $products = Product::orderBy('id','DESC')->with('product_images')->get();
        $data = [
            'products'=> $products,            
        ];        

        return view('front.home.wishlist', $data);        
    }


    //Slug
    public function index(Request $request, $areaSlug = null,) {
        $areaSlug = ' ';

        $products = Product::orderBy('id','DESC')->get();
        $areas = Area::orderBy('id','DESC')->with('seating')->orderBy('id','DESC')->get();
        $seat_number = Seat::orderBy('id','DESC')->get();

        $products = Product::where('status',1);

        if(!empty($areaSlug)) {
            $areas = Area::where('slug',$areaSlug)->first();
            $seat_number = $seat_number->where('area_id',$areas->id);
            $areaSlug = $areas->id;
        }

        $products = $products->paginate(10);

        $data['products'] = $products;
        $data['areaSlug'] = $areaSlug;

        return view('front.shop.index',$data);
    }


    //Area Slug
    public function restaurant(Request $request, $areaSlug = null) {       
        $areaSelected = ' ';

        $products = Product::orderBy('id','DESC')->get();
        $seats = Seat::orderBy("table_name","ASC")->with('area')->get(); 
        $areas = Area::where('status',1);

        // if(!empty($areaSlug)) {
        //     $restaurant = Area::where('area_slug',$areaSlug)->first();
        //     $seats = $seats->where('area_id',$restaurant->id);
        //     $areaSelected = $restaurant->id;
        // }

        //$seatings = $seatings->paginate(10);
        
        $data['seats'] = $seats;  
        $data['products'] = $products;  
        $data['areas'] = $areas;        
        $data['areaSelected'] = $areaSelected;
        
        return view('front.home.restaurant',$data);
    }
    

    //Add to Cart
    public function addToCart($id){
        $product = Product::find($id);

        if (!$product) {
            abort(404);
        }

        $cart = session()->get('cart');

        if (!$cart) {
            $cart = [
                $id => [
                    "name" => $product->name,
                    "quantity" => 1,
                    "price" => $product->price,
                    //"seat" => $product->seat,   
                    "image" => $product->image,                 
                ]
            ];

            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Product added');
        }

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Product added');
        }

        $cart[$id] = [
            "name" => $product->name,
            "quantity" => 1,
            "price" => $product->price,
            //"seat" => $product->seat,
            "image" => $product->image            
        ];

        session()->put('cart', $cart);

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Product added to cart successfully!']);
        }

        return redirect()->route('front.home')->with('success','Product added to cart successfully!');

        //return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    //remove Item from cart
    public function removeCartItem(Request $request) {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }

            session()->flash('success', 'Product removed successfully');
        }
    }

    //Clear Cart
    public function clearCart(){
        session()->forget('cart');
        return redirect()->back();
    }


    //Wishlist
    public function addToWish($id){
        $product = Product::find($id);
        
        if (!$product) {
            abort(404);
        }

        $cart = session()->get('wishlist');

        if (!$cart) {
            $cart = [
                $id => [
                    "name" => $product->name,
                    "quantity" => 1,
                    "price" => $product->price,
                    "image" => $product->image,        
                ]
            ];

            session()->put('wishlist', $cart);
            return redirect()->back()->with('success', 'Product added to wishlist successfully!');
        }

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
            session()->put('wishlist', $cart);
            return redirect()->back()->with('success', 'Product added to wishlist successfully!');
        }

        $cart[$id] = [
            "name" => $product->name,
            "quantity" => 1,
            "price" => $product->price, 
            "image" => $product->image,            
        ];

        session()->put('wishlist', $cart);

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Product added to wishlist successfully!']);
        }
        return redirect()->back()->with('success', 'Product added to wishlist successfully!');
    }


    public function removeWishlistItem(Request $request) {
        if ($request->id) {
            $cart = session()->get('wishlist');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('wishlist', $cart);
            }
            session()->flash('success', 'Product removed successfully');
        }
    }

    public function clearWishlist(){
        session()->forget('wishlist');
        return redirect()->route('front.home')->with('success','Wishlist cleared successfully.');
    }


    public function make_order (Request $request){
        $validator = Validator::make($request->all(), [
             
        ]);

        $session_id = mt_rand(1000000000, 9999999999);        
        Session::put('session_id',$session_id);
        $cart = Session::get('cart');
        $total = 0;

        if (session('cart')) {
            foreach (session('cart') as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        }

        if ($validator->passes()) {
            $order = new Order();
            $order->order_type = $request->order_type;
            $order->session_id = session('session_id');
            $order->notes = $request->notes;
            $order->ready_time = $request->ready_time;

            $order->seat_id = $request->seat_id;
            $order->dinein_time = $request->dinein_time;

            $order->customer_name = $request->name;
            $order->customer_email = $request->email;
            $order->customer_phone = $request->phone;
            
            $order->customer_name = $request->name;
            $order->customer_email = $request->email;
            $order->customer_phone = $request->phone;
            $order->delivery_address = $request->address;

            // Type-specific handling
            if ($request->order_type === 'dinein') {
                $order->seat_id = $request->seat_id;
                //$order->table_number = $request->table_number;
                $order->dinein_time = $request->time;
            }

            if ($request->order_type === 'takeaway') {
                $order->customer_name = $request->name;
                $order->customer_email = $request->email;
                $order->customer_phone = $request->phone;
            }

            if ($request->order_type === 'delivery') {
                $order->customer_name = $request->name;
                $order->customer_email = $request->email;
                $order->customer_phone = $request->phone;
                $order->delivery_address = $request->address;
            }

            $order->total_amount = $total;
            $order->save();

            foreach (session('cart') as $id => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price'],
                ]);
            }
           
            Session::forget('cart');

            $request->session()->flash('success', 'Order placed successfully');

            return response()->json([
                'status' => true,
                'message' => 'Order added successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);

            $request->session()->flash('success', 'Order placed successfully');
        }
    }


    public function increase(Request $request) {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {
            $cart[$request->id]['quantity']++;
        }

        session()->put('cart', $cart);

        return response()->json(['status' => true]);
    }

    public function decrease(Request $request) {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {

            if ($cart[$request->id]['quantity'] > 1) {
                $cart[$request->id]['quantity']--;
            } else {
                unset($cart[$request->id]); // remove item
            }
        }

        session()->put('cart', $cart);

        return response()->json(['status' => true]);
    }
}