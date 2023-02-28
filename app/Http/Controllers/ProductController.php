<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
    //    $products = Product::orderby('created_at')->get();
    $keyword = $request->get('search');
    $perPage = 5;

    if(!empty($keyword)){
        $products = Product::where('name','LIKE',"%$keyword%")
                     ->orWhere('category','LIKE',"%$keyword%")
                     ->latest()->paginate($perPage);
    }else{
          $products = Product::latest()->paginate($perPage);
    }

       return view('products.index',['products'=>$products])->with('i',(request()->input('page',1) -1) *5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2028'
        ]);

        $products = new Product;

        $file_name = time().'.'.request()->image->getClientOriginalExtension();
        request()->image->move(public_path('image'), $file_name);

        $products->name = $request->name;
        $products->description = $request->description;
        $products->image = $file_name;
        $products->category = $request->category;
        $products->quantity = $request->quantity;
        $products->price = $request->price;


        $products->save();
        return redirect()->route('product.index')->with('success','Product Added Successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit',['product'=>$product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required'
        ]);

      

        $file_name = $request->hidden_product_image;

        if($request->image != ''){
            $file_name = time().'.'.request()->image->getClientOriginalExtension();
            request()->image->move(public_path('image'), $file_name);
        }
         
        $products = Product::find($request->hidden_id);
        $products->name = $request->name;
        $products->description = $request->description;
        $products->image = $file_name;
        $products->category = $request->category;
        $products->quantity = $request->quantity;
        $products->price = $request->price;


        $products->save();
        return redirect()->route('product.index')->with('success','Product Updated Successfully!');


       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $image_path = public_path()."/image/";
        $image = $image_path. $product->image;
        if(file_exists($image)){
            @unlink($image);
        }
        $product->delete();
        return redirect()->route('product.index')->with('success','Product Deleted !');
    }
}
