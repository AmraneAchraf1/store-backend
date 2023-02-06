<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $products = Product::all()->map(function ($product) {
            return [
                'id' => $product->id,
                'type' => $product->type,
                "options" => $product->options,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'image' => $product->image,
                "image_url" => asset("storage/uploads/". $product->image )
            ];
        });

        if($products){
            return response()->json( $products, 200);
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
        // Just admin Can Add products
        $user = Auth::guard('sanctum')->user();
        if(!$user->tokenCan('admin')){
            return response()->json(['msg'=>'Access denied']);
        }



        if (!$request->hasFile("image")){
            return  response()->json(["msg" => "Image not found"]);
        }

        $image = $request->file("image"); // UploadedFile

        $image_name = Carbon::now()->format("d-m-Y-H-i-s") ."_" .$image->getClientOriginalName();
        $path =  null;

        if($image){
            $path = $image->storeAs("uploads", $image_name, "public");
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
            $product['image_url']= asset("storage/". $path);
            return response()->json(  $product , 201);
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
        $product = Product::find($id);

        if($product){
            return  response()->json( [ "product"=>$product ,"image_url"=>asset("storage/uploads/". $product->image ) ]);
        }
            return  response()->json([
                "msg" => "Product Not Found"
            ], 400);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProduct(ProductRequest $request, $id)
    {

        $user = Auth::guard('sanctum')->user();

        if(!$user->tokenCan('admin')){
            return response()->json(['msg'=>'Access denied']);
        }

        $product = Product::find($id);

        if (!$request->hasFile("image")){
            return  response()->json(["msg" => "Image not found"]);
        }

        $oldImage = "uploads/".$product->image;

        $image = $request->file("image");
        $image_name = Carbon::now()->format("d-m-Y-H-i-s") ."_" .$image->getClientOriginalName();
        $path =  null;
        if($image){

            $path = $image->storeAs("uploads", $image_name, "public");

            if($path && $oldImage){
                Storage::disk("public")->delete($oldImage);
            }

        }else{
            return response()->json(["msg" => "Error"]);
        }


        if($product){
           $product->update([
               "name"=> $request->name,
               "image"=> $image_name,
               "type"=> $request->type,
               "price"=> $request->price,
               "description"=> $request->description,

           ]);
           $product["image_url"] = asset("storage/".$path);

           return response()->json($product);
        }
        return  response()->json([
            "msg" => "Product Not Found"
        ], 404);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {

        $user = Auth::guard('sanctum')->user();

        if(!$user->tokenCan('admin')){
            return response()->json(['msg'=>'Access denied']);
        }
        $product = Product::find($id);


        if($product){
            $product->delete();

            Storage::disk("public")->delete("uploads/". $product->image );
            return response()->json([
                "id" => $product->id,
                "msg" => "Product Deleted",
            ]);
        }


        return  response()->json(["msg" => "Can't delete product"]);



    }
}
