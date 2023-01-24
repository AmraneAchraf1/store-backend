<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        $products = Product::all();

        if($products){
            return response()->json($products, 200);
        }

        return response()->json([
            "msg" => "Products Not Found",
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductRequest $request)
    {
        $image = $request->file("image");

        $image_name = Carbon::now()->format("d-m-Y-H-i-s") ."_" .$image->getClientOriginalName();

        if($image){
            $image->storeAs("public/uploads", $image_name);
        }else{
            return response()->json(["msg" => "Error"]);
        }

        // Create a new Product
        $product = Product::create([
            "name"=> $request->name,
            "image"=> $image_name,
            "type"=> $request->type,
            "price"=> $request->price,
            "description"=> $request->description,
            "options"=>$request->options,

        ]);

        if ($product){
            return response()->json($product, 201);
        }else{
            return response()->json([
                "msg" => "Product Not Created"
            ], 400);
        }
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
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
