<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductView;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller {

    public function index(Request $request){
        $products = Product::latest('id');
        $categories = Category::orderBy('name','ASC')->get();  
        
        $menuCount = DB::table('products')
                    ->select(DB::raw('count(*) as total_products'))
                    ->get()[0]->total_products;

        $products = $products->paginate();

        $data['products'] = $products;
        $data['categories'] = $categories;  
        $data['menuCount'] = $menuCount;            

        return view ('admin.products.list', $data);
    }

    public function create(){
        $data = [];
        $categories = Category::orderBy('name','ASC')->get();        
        $data['categories'] = $categories;

        return view('admin.products.create', $data);
    }


    public function store(Request $request){
        $rules = [
            'name' => 'required',                            
        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()) {
            $data = new Product;
            $data->name = $request->name;            
            $data->slug = $request->slug;                 
            $data->category_id = $request->category;
            $data->menu_id = $request->menu;
            $data->description = $request->description;
            $data->price = $request->price;
            $data->compare_price = $request->compare_price;
            $data->veg_nonveg = $request->veg_nonveg;

            //Image upload
            if ($request->hasFile('image')) { 
                $file = $request->file('image');
                $extenstion = $file->getClientOriginalExtension();
                $fileName = $data->slug.'_'.time().'.'.$extenstion;
                $path = public_path().'/uploads/product/'.$fileName;
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file);
                $image->toJpeg(80)->save($path);
                $image->cover(500,500)->save($path);
                $data->image = $fileName;
            }
            
            $data->save();

            return redirect()->route('products.index')->with('success','Product added successfully.');
        } else {
            return redirect()->route('products.index')->withInput()->withErrors($validator);
        }
    }


    public function view_store(Request $request){
        $rules = [
                                    
        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()) {
            $product = new ProductView;
            $product->toggleActive()->save();          
            //$product->name = $request->view;  
            //$product->save();

            $request->session()->flash('success','View set successfully');

            return response()->json([
                'status' => true,
                'message' => 'View set successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function edit($id, Request $request){
        $product = Product::find($id);

        if (empty($product)) {
            return redirect()->route('products.index')->with('error','Product not found');
        }

        //Fetch Product Images
        $subCategories = Menu::where('category_id',$product->category_id)->get();
        $categories = Category::orderBy('name','ASC')->get();

        $data = [];
        
        $data['categories'] = $categories;
        $data['product'] = $product;
        $data['subCategories'] = $subCategories;
             
        return view('admin.products.edit',$data);
    }


    public function update($id, Request $request){
        $data = Product::find($id);
       
        $rules = [
            'name' => 'required',
        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()) {
            $data->name = $request->name;            
            $data->slug = $request->slug;     
            $data->category_id = $request->category;
            $data->menu_id = $request->menu;
            $data->description = $request->description;
            $data->price = $request->price;
            $data->compare_price = $request->compare_price;
            $data->veg_nonveg = $request->veg_nonveg;

            //Image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extenstion = $file->getClientOriginalExtension();
                $fileName = time().'.'.$extenstion;
                $path = public_path().'/uploads/product/'.$fileName;
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file);
                $image->toJpeg(80)->save($path);
                $image->cover(500,500)->save($path);
                $data->image = $fileName;
            }

            $data->save();

            return redirect()->route('products.index')->with('success','Product updated successfully.');
        } else {
            return redirect()->route('products.index')->withInput()->withErrors($validator);
        }
    }


    public function delete($id){
        $product = Product::find($id);

        File::delete(public_path('uploads/product/'.$product->image));
        
        $product->delete();

        return redirect()->route('products.index')->with('success','Product deleted successfully.');
    }



    // public function destroy($id, Request $request){
    //     $product = Product::find($id);

    //     if (empty($product)) {
    //         $request->session()->flash('error','Product not found');
    //         return response()->json([
    //             'status' => false,
    //             'notFound' => true,
    //         ]);
    //     }

    //     File::delete(public_path('uploads/product/'.$product->image));

    //     $product->delete();

    //     $request->session()->flash('success','Product deleted successfully');

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Product deleted successfully',
    //     ]);
    // }

    public function getProducts(Request $request){

        $tempProduct = [];

        if($request->term != ""){
            $products = Product::where('title','like','%'.$request->term.'%')->get();

            if ($products != null){
                foreach ($products as $product){
                    $tempProduct[] = array(
                        'id' => $product->id,
                        'text' => $product->title,
                    );
                }
            }
        }

        return response()->json([
            'tags' => $tempProduct,
            'status' => true,
        ]);


    }
}
