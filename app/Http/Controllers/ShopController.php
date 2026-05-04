<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller {
    public function index(Request $request, $menuSlug = null) {
    // Categories with menus
     $categories = getCategories();

    // ✅ Default to 'popular' if no slug
    if (empty($menuSlug)) {
        $menuSlug = Category::whereRaw("LOWER(name) = 'popular'")
            ->value('slug');
    }   

    // Base query
    $products = Product::where('status', 1);

    // ✅ Filter by menu slug (ONLY if exists)
    if (!empty($menuSlug)) {
        $products->whereHas('category', function ($q) use ($menuSlug) {
            $q->where('slug', $menuSlug);
        });
    }

    // ✅ Price filter
    if ($request->filled('price_min') && $request->filled('price_max')) {
        $min = (int) $request->price_min;
        $max = (int) $request->price_max;

        if ($max == 1000) {
            $max = 1000000; // max cap
        }

        $products->whereBetween('price', [$min, $max]);
    }

    // ✅ Search
    if ($request->filled('search')) {
        $products->where('title', 'like', '%' . $request->search . '%');
    }

    // ✅ Sorting
    switch ($request->get('sort')) {
        case 'latest':
            $products->orderBy('id', 'DESC');
            break;

        case 'price_asc':
            $products->orderBy('price', 'ASC');
            break;

        case 'price_desc':
            $products->orderBy('price', 'DESC');
            break;

        default:
            $products->orderBy('id', 'DESC');
            break;
    }

    // Pagination
     $products = $products->orderBy('id', 'DESC')->paginate(10);

    // Pass data
    return view('front.shop.index', [
        'categories'   => $categories,
        'products'     => $products,
        'menuSelected' => $menuSlug, // for active tab
        'priceMax'     => $request->price_max ?? 1000,
        'priceMin'     => $request->price_min ?? 0,
    ]);
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
