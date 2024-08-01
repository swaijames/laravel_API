<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::get();
        if ($products->count() > 0) {

            return ProductResource::collection($products);
        } else {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|integer',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'all fields are required',
                'errors' => $validator->message()
            ]);
        }
        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);
        return response()->json([
            'message' => 'Product created successfully',
            'data' => new ProductResource(Product::latest()->first()),
        ], 201);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }


    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|integer',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'all fields are required',
                'errors' => $validator->message()
            ]);
        }
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);
        return response()->json([
            'message' => 'Product updated successfully',
            'data' => new ProductResource(Product::latest()->first()),
        ], 201);
    }



    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            'message' => 'Product deleted successfully',
        ], 201);
    
    }





    // $products = Product::all();
    // return response()->json($products);

}
