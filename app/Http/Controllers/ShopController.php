<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request, $menuSlug = null) {       
        $menuSelected = ' ';

        $categories = Category::orderBy("name","ASC")->with('menus')->get();        
        $products = Product::where('status',1);

        if(!empty($menuSlug)) {
            $menus = Menu::where('slug',$menuSlug)->first();
            $products = $products->where('menu_id',$menus->id);
            $menuSelected = $menus->id;
        }

        // Price slider
        if($request->get('price_max') != '' && $request->get('price_min') != '') {
            if($request->get('price_max') == 1000){
                $products = $products->whereBetween('price',[intval($request->get('price_min')),1000000]);
            } else {
                $products = $products->whereBetween('price',[intval($request->get('price_min')),intval($request->get('price_max'))]);
            }
        }

        //Search main header
        if (!empty($request->get('search'))){
            $products = $products->where('title','like','%'.$request->get('search').'%');
        }

        if($request->get('sort') != ''){
            if($request->get('sort') == 'latest'){
                $products = $products->orderBy('id','DESC');
            } else if($request->get('sort') == 'price_asc') {
                $products = $products->orderBy('price','ASC');
            } else {
                $products = $products->orderBy('price','DESC');
            }
        } else {
            $products = $products->orderBy('id','DESC');
        }

        $products = $products->paginate(10);

        $data['categories'] = $categories;
        $data['products'] = $products;        
        $data['menuSelected'] = $menuSelected;
        $data['priceMax'] = (intval($request->get('price_max')) == 0 ? 1000 : $request->get('price_max'));
        $data['priceMin'] = intval($request->get('price_min'));
        
        return view('front.shop.index',$data);
    }

    public function category(Request $request, $categorySlug = null) {       
        $categorySelected = ' ';

        $categories = Category::orderBy("name","ASC")->with('menus')->get();        
        $products = Product::where('status',1);

        if(!empty($categorySlug)) {
            $menus = Category::where('slug',$categorySlug)->first();
            $products = $products->where('category_id',$menus->id);
            $categorySelected = $menus->id;
        }

        $data['categories'] = $categories;
        $data['products'] = $products;        
        $data['categorySelected'] = $categorySelected;
        
        return view('front.shop.index',$data);
    }


    public function product($slug){
        $product = Product::where('slug',$slug)->first();

        if($product == null){
            abort(404);
        }

        //Fetch Related products
        // $relatedProducts = [];
        // if ($product->related_products != '') {
        //     $productArray = explode(',',$product->related_products);
        
        // }

        $data['product'] = $product;
        //$data['relatedProducts'] = $relatedProducts;

        return view('front.products.index',$data);
    }
}
